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

use Module\DemoDoctrine\Entity\Quote;
use Module\DemoDoctrine\Entity\QuoteLang;
use Module\DemoDoctrine\Repository\QuoteRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;

class QuoteFormDataProvider implements FormDataProviderInterface
{
    public function __construct(
        private readonly QuoteRepository $repository
    ) {
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
