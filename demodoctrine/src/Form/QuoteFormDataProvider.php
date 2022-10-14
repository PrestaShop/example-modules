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

use Module\DemoDoctrine\Entity\Quote;
use Module\DemoDoctrine\Entity\QuoteLang;
use Module\DemoDoctrine\Repository\QuoteRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;

class QuoteFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var QuoteRepository
     */
    private $repository;

    /**
     * @param QuoteRepository $repository
     */
    public function __construct(QuoteRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function getData($quoteId)
    {
        $quote = $this->repository->findOneById($quoteId);

        $quoteData = [
            'author' => $quote->getAuthor(),
        ];
        foreach ($quote->getQuoteLangs() as $quoteLang) {
            $quoteData['content'][$quoteLang->getLang()->getId()] = $quoteLang->getContent();
        }

        return $quoteData;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultData()
    {
        return [
            'author' => '',
            'content' => [],
        ];
    }
}
