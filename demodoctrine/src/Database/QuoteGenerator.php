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

namespace Module\DemoDoctrine\Database;

use Doctrine\ORM\EntityManagerInterface;
use Module\DemoDoctrine\Entity\Quote;
use Module\DemoDoctrine\Entity\QuoteLang;
use Module\DemoDoctrine\Repository\QuoteRepository;
use PrestaShopBundle\Entity\Lang;
use PrestaShopBundle\Entity\Repository\LangRepository;

class QuoteGenerator
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
