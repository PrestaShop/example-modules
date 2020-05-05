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

namespace PrestaShop\Module\DemoDoctrine\Form;

use PrestaShop\Module\DemoDoctrine\Entity\Quote;
use PrestaShop\Module\DemoDoctrine\Entity\QuoteLang;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;

class QuoteFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var QuoteRepository
     */
    private $repository;

    /**
     * @var array
     */
    private $contextShopIds;

    /**
     * @param QuoteRepository $repository
     * @param array $contextShopIds
     */
    public function __construct(QuoteRepository $repository, array $contextShopIds)
    {
        $this->repository = $repository;
        $this->contextShopIds = $contextShopIds;
    }

    /**
     * {@inheritdoc}
     */
    public function getData($quoteId)
    {
        /** @var Quote $quote */
        $quote = $this->repository->findOneById($quoteId);

        $content = [];
        /** @var QuoteLang $quote */
        foreach ($quote->getQuoteLangs() as $quoteLang) {
            $content[$quoteLang->getLang()->getId()] = $quoteLang->getContent();
        }

        return [
            'author' => $quote->getAuthor(),
            'content' => $content,
        ];
    }

    /**
     * Get default form data.
     *
     * @return mixed
     */
    public function getDefaultData()
    {
        return [
            'author' => '',
            'content' => [],
        ];
    }
}