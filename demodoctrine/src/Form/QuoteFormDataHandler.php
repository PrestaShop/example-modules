<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0  Academic Free License (AFL 3.0)
 */
declare(strict_types=1);

namespace Module\DemoDoctrine\Form;

use Doctrine\ORM\EntityManagerInterface;
use Module\DemoDoctrine\Entity\Quote;
use Module\DemoDoctrine\Entity\QuoteLang;
use Module\DemoDoctrine\Repository\QuoteRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;
use PrestaShopBundle\Entity\Repository\LangRepository;

class QuoteFormDataHandler implements FormDataHandlerInterface
{
    public function __construct(
        private readonly QuoteRepository $quoteRepository,
        private readonly LangRepository $langRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data)
    {
        $quote = new Quote();
        $quote->setAuthor($data['author']);
        foreach ($data['content'] as $langId => $langContent) {
            $lang = $this->langRepository->findOneById($langId);
            $quoteLang = new QuoteLang();
            $quoteLang
                ->setLang($lang)
                ->setContent($langContent)
            ;
            $quote->addQuoteLang($quoteLang);
        }
        $this->entityManager->persist($quote);
        $this->entityManager->flush();

        return $quote->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data)
    {
        $quote = $this->quoteRepository->findOneById($id);
        $quote->setAuthor($data['author']);
        foreach ($data['content'] as $langId => $content) {
            $quoteLang = $quote->getQuoteLangByLangId($langId);
            if (null === $quoteLang) {
                continue;
            }
            $quoteLang->setContent($content);
        }
        $this->entityManager->flush();

        return $quote->getId();
    }
}
