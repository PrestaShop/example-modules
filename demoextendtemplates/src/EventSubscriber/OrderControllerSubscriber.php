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

namespace PrestaShop\Module\DemoExtendTemplates\EventSubscriber;

use PrestaShopBundle\Controller\Admin\Sell\Order\OrderController;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class OrderControllerSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly LoggerInterface $logger,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onController',
        ];
    }

    private function onIndexAction(): void
    {
        $this->requestStack->getSession()->getFlashBag()->add(
            'demoextendtemplates-success',
            'Custom success flash from demoextendtemplates module'
        );
    }

    private function onDeleteProductAction(ControllerEvent $event): void
    {
        $request = $event->getRequest();

        $this->logger->warning('Attempt to delete a product from an order was blocked.', [
            'orderId' => $request->attributes->get('orderId'),
            'orderDetailId' => $request->attributes->get('orderDetailId'),
        ]);

        $event->setController(
            fn () => new JsonResponse(
                ['message' => 'Deleting products from orders is not allowed.'],
                Response::HTTP_FORBIDDEN
            )
        );
    }

    public function onController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        [$instance, $method] = $controller;

        if (!$instance instanceof OrderController) {
            return;
        }

        match ($method) {
            'indexAction' => $this->onIndexAction(),
            'deleteProductAction' => $this->onDeleteProductAction($event),
            default => null,
        };
    }
}
