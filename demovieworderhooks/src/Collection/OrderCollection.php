<?php
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

declare(strict_types=1);

namespace PrestaShop\Module\DemoViewOrderHooks\Collection;

use PrestaShop\Module\DemoViewOrderHooks\DTO\Order;
use PrestaShop\PrestaShop\Core\Data\AbstractTypedCollection;

final class OrderCollection extends AbstractTypedCollection
{
    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return Order::class;
    }
}
