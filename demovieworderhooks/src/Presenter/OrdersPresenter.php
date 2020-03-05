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
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var Locale
     */
    private $locale;

    public function __construct(UrlGeneratorInterface $urlGenerator, Locale $locale)
    {
        $this->urlGenerator = $urlGenerator;
        $this->locale = $locale;
    }

    /**
     * Present a collection of orders for usage in rendering.
     *
     * @return array presented array of orders
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
