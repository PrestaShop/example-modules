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

namespace Module\DemoDoctrine\Database;

use Doctrine\ORM\EntityManagerInterface;
use Module\DemoDoctrine\Entity\Quote;
use Module\DemoDoctrine\Entity\QuoteLang;
use Module\DemoDoctrine\Repository\QuoteRepository;
use PrestaShopBundle\Entity\Lang;
use PrestaShopBundle\Entity\Repository\LangRepository;

class QuoteGenerator
{
    public function __construct(
        private readonly QuoteRepository $quoteRepository,
        private readonly LangRepository $langRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function generateQuotes()
    {
        $this->removeAllQuotes();
        $this->insertQuotes();
    }

    private function removeAllQuotes()
    {
        $quotes = $this->quoteRepository->findAll();
        foreach ($quotes as $quote) {
            $this->entityManager->remove($quote);
        }
        $this->entityManager->flush();
    }

    private function insertQuotes()
    {
        $languages = $this->langRepository->findAll();

        $quotesDataFile = __DIR__ . '/../../Resources/data/quotes.json';
        $quotesData = json_decode(file_get_contents($quotesDataFile), true);
        foreach ($quotesData as $quoteData) {
            $quote = new Quote();
            $quote->setAuthor($quoteData['author']);
            /** @var Lang $language */
            foreach ($languages as $language) {
                $quoteLang = new QuoteLang();
                $quoteLang->setLang($language);
                if (isset($quoteData['quotes'][$language->getIsoCode()])) {
                    $quoteLang->setContent($quoteData['quotes'][$language->getIsoCode()]);
                } else {
                    $quoteLang->setContent($quoteData['quotes']['en']);
                }
                $quote->addQuoteLang($quoteLang);
            }
            $this->entityManager->persist($quote);
        }

        $this->entityManager->flush();
    }
}
