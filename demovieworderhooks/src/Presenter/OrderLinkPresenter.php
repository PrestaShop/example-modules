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

use Order;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class OrderLinkPresenter
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function present(?int $orderId): array
    {
        $order = new Order($orderId);

        return [
            'href' => $orderId ? $this->urlGenerator->generate('admin_orders_view', ['orderId' => $orderId]) : null,
            'enabled' => $orderId !== null,
            'text' => $order->reference,
        ];
    }
}
