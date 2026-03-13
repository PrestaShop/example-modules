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

namespace PrestaShop\Module\DemoViewOrderHooks\Presenter;

use Currency;
use Order as PrestashopOrder;
use OrderState;
use PrestaShop\Module\DemoViewOrderHooks\Collection\OrderCollection;
use PrestaShop\Module\DemoViewOrderHooks\DTO\Order;
use PrestaShop\PrestaShop\Core\Localization\Locale;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class OrdersPresenter
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly Locale $locale
    ) {
    }

    /**
     * Present a collection of orders for usage in rendering.
     */
    public function present(OrderCollection $orders, int $languageId): array
    {
        $presented = [];

        /** @var Order $order */
        foreach ($orders as $order) {
            $prestashopOrder = new PrestashopOrder($order->getOrderId());
            $orderState = new OrderState($order->getOrderStateId(), $languageId);
            $presented[] = [
                'id' => $order->getOrderId(),
                'reference' => $order->getReference(),
                'link' => $this->urlGenerator->generate('admin_orders_view', [
                    'orderId' => $order->getOrderId(),
                ]),
                'status' => [
                    'name' => $orderState->name,
                    'color' => $orderState->color
                ],
                'placedAt' => $order->getOrderDate(),
                'totalPaid' => $this->locale->formatPrice(
                    $prestashopOrder->getOrdersTotalPaid(),
                    Currency::getIsoCodeById((int) $prestashopOrder->id_currency)
                ),
            ];
        }

        return $presented;
    }
}
