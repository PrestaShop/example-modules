<?php
/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

namespace PrestaShop\Module\DemoDoctrine;

use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\Module\DemoDoctrine\Entity\Quote;
use PrestaShop\Module\DemoDoctrine\Entity\QuoteLang;
use PrestaShop\Module\DemoDoctrine\Repository\QuoteRepository;
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
        $this->insertQuotes();;
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

        $quotesDataFile = __DIR__ . '/../Resources/data/quotes.json';
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