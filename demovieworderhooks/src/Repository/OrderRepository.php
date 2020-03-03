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

namespace PrestaShop\Module\DemoViewOrderHooks\Repository;

use DateTimeImmutable;
use Db;
use DbQuery;
use Order as PrestaShopOrder;
use PrestaShop\Module\DemoViewOrderHooks\Collection\Orders;
use PrestaShop\Module\DemoViewOrderHooks\DTO\Order;

class OrderRepository
{
    /**
     * @var Db
     */
    private $db;

    public function __construct()
    {
        $this->db = Db::getInstance();
    }

    /**
     * Get all orders that a customer has placed.
     */
    public function getCustomerOrders(int $customerId, array $excludeOrderIds = []): Orders
    {
        $orders = PrestaShopOrder::getCustomerOrders($customerId);
        $ordersCollection = new Orders();

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
