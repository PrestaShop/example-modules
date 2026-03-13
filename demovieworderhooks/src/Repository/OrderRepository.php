<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

declare(strict_types=1);

namespace PrestaShop\Module\DemoViewOrderHooks\Repository;

use DateTimeImmutable;
use Db;
use DbQuery;
use Order as PrestaShopOrder;
use PrestaShop\Module\DemoViewOrderHooks\Collection\OrderCollection;
use PrestaShop\Module\DemoViewOrderHooks\DTO\Order;

/**
 * This repository uses DbCore from PrestaShop for Database Abstraction Layer
 * It does not extend Doctrine EntityRepository
 */
class OrderRepository
{
    private Db $db;

    public function __construct()
    {
        $this->db = Db::getInstance();
    }

    /**
     * Get all orders that a customer has placed.
     */
    public function getCustomerOrders(int $customerId, array $excludeOrderIds = []): OrderCollection
    {
        $orders = PrestaShopOrder::getCustomerOrders($customerId);
        $ordersCollection = new OrderCollection();

        foreach ($orders as $order) {
            if (in_array($order['id_order'], $excludeOrderIds)) {
                continue;
            }

            $ordersCollection->add(new Order(
                (int) $order['id_order'],
                $order['reference'],
                (int) $order['current_state'],
                new DateTimeImmutable($order['date_add'])
            ));
        }

        return $ordersCollection;
    }

    public function getNextOrderId(int $orderId): ?int
    {
        $query = new DbQuery();
        $query
            ->select('id_order')
            ->from('orders')
            ->where('id_order > '.$orderId)
            ->orderBy('id_order ASC')
        ;

        $result = $this->db->getValue($query);

        return $result ? (int) $result : null;
    }

    public function getPreviousOrderId(int $orderId): ?int
    {
        $query = new DbQuery();
        $query
            ->select('id_order')
            ->from('orders')
            ->where('id_order < '.$orderId)
            ->orderBy('id_order DESC')
        ;

        $result = $this->db->getValue($query);

        return $result ? (int) $result : null;
    }
}
