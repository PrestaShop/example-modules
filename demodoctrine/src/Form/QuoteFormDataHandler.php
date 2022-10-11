<?php
/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
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
    /**
     * @var QuoteRepository
     */
    private $quoteRepository;

    /**
     * @var LangRepository
     */
    private $langRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param QuoteRepository $quoteRepository
     * @param LangRepository $langRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        QuoteRepository $quoteRepository,
        LangRepository $langRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->langRepository = $langRepository;
        $this->entityManager = $entityManager;
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
