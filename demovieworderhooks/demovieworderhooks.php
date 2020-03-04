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

use PrestaShop\Module\DemoViewOrderHooks\Collection\Orders;
use PrestaShop\Module\DemoViewOrderHooks\Install\InstallerFactory;
use PrestaShop\Module\DemoViewOrderHooks\Presenter\OrderLinkPresenter;
use PrestaShop\Module\DemoViewOrderHooks\Presenter\OrderReviewPresenter;
use PrestaShop\Module\DemoViewOrderHooks\Presenter\OrdersPresenter;
use PrestaShop\Module\DemoViewOrderHooks\Presenter\PackageLocationsPresenter;
use PrestaShop\Module\DemoViewOrderHooks\Presenter\SignaturePresenter;
use PrestaShop\Module\DemoViewOrderHooks\Repository\OrderRepository;
use PrestaShop\Module\DemoViewOrderHooks\Repository\OrderReviewRepository;
use PrestaShop\Module\DemoViewOrderHooks\Repository\PackageLocationRepository;
use PrestaShop\Module\DemoViewOrderHooks\Repository\SignatureRepository;

if (!defined('_PS_VERSION_')) {
    exit;
}

// need it because InstallerFactory is not autoloaded during the install
require_once __DIR__.'/vendor/autoload.php';

class DemoViewOrderHooks extends Module
{
    private const DELIVERED_ORDER_STATE_ID = 5;

    public function __construct()
    {
        $this->name = 'demovieworderhooks';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '1.7.7.0', 'max' => _PS_VERSION_];

        parent::__construct();

        $this->displayName = $this->l('Demo view order hooks');
        $this->description = $this->l('Demonstration of new hooks in PrestaShop 1.7.7 order view page');
    }

    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        $installer = InstallerFactory::create();

        return $installer->install($this);
    }

    public function uninstall()
    {
        $installer = InstallerFactory::create();

        return $installer->uninstall() && parent::uninstall();
    }

    /**
     * Add buttons to main buttons bar
     */
    public function hookActionGetAdminOrderButtons(array $params)
    {
        $order = new Order($params['id_order']);

        /** @var \Symfony\Bundle\FrameworkBundle\Routing\Router $router */
        $router = $this->get('router');

        /** @var \PrestaShopBundle\Controller\Admin\Sell\Order\ActionsBarButtonsCollection $bar */
        $bar = $params['actions_bar_buttons_collection'];

        $viewCustomerUrl = $router->generate('admin_customers_view', ['customerId'=> (int)$order->id_customer]);
        $bar->add(
            new \PrestaShopBundle\Controller\Admin\Sell\Order\ActionsBarButton(
                'btn-secondary', ['href' => $viewCustomerUrl], 'View customer'
            )
        );
        $bar->add(
            new \PrestaShopBundle\Controller\Admin\Sell\Order\ActionsBarButton(
                'btn-info', ['href' => 'https://www.prestashop.com/'], 'Go to prestashop'
            )
        );
        $bar->add(
            new \PrestaShopBundle\Controller\Admin\Sell\Order\ActionsBarButton(
                'btn-dark', ['href' => 'https://github.com/PrestaShop/example-modules/tree/master/demovieworderhooks'], 'Go to GitHub'
            )
        );
        $createAnOrderUrl = $router->generate('admin_orders_create');
        $bar->add(
            new \PrestaShopBundle\Controller\Admin\Sell\Order\ActionsBarButton(
                'btn-link', ['href' => $createAnOrderUrl], 'Create an order'
            )
        );
    }

    /**
     * Displays customer's signature.
     */
    public function hookDisplayBackOfficeOrderActions(array $params)
    {
        /** @var SignatureRepository $signatureRepository */
        $signatureRepository = $this->get('prestashop.module.demovieworderhooks.repository.signature_repository');

        /** @var SignaturePresenter $signaturePresenter */
        $signaturePresenter = $this->get('prestashop.module.demovieworderhooks.presenter.signature_presenter');

        $signature = $signatureRepository->findOneBy(['orderId' => $params['id_order']]);

        if (!$signature) {
            return '';
        }

        return $this->render($this->getModuleTemplatePath() . 'customer_signature.html.twig', [
            'signature' => $signaturePresenter->present($signature, (int) $this->context->language->id),
        ]);
    }

    /**
     * Display shipment tracking information.
     */
    public function hookDisplayAdminOrderTabContent(array $params)
    {
        /** @var PackageLocationRepository $locationRepository */
        $locationRepository = $this->get('prestashop.module.demovieworderhooks.repository.package_location_repository');

        $locations = $locationRepository->findBy(
            ['orderId' => $params['id_order']],
            ['position' => 'asc']
        );

        /** @var PackageLocationsPresenter $locationsPresenter */
        $locationsPresenter = $this->get('prestashop.module.demovieworderhooks.presenter.package_locations_presenter');

        return $this->render($this->getModuleTemplatePath() . 'tracking.html.twig', [
            'packageLocations' => $locationsPresenter->present($locations),
        ]);
    }

    /**
     * Display tracking tab link.
     */
    public function hookDisplayAdminOrderTabLink(array $params)
    {
        return $this->render($this->getModuleTemplatePath() . 'tracking_link.html.twig');
    }

    public function hookDisplayAdminOrderMain(array $params)
    {
        /** @var OrderRepository $orderRepository */
        $orderRepository = $this->get('prestashop.module.demovieworderhooks.repository.order_repository');
        /** @var OrdersPresenter $ordersPresenter */
        $ordersPresenter = $this->get('prestashop.module.demovieworderhooks.presenter.orders_presenter');

        $order = new Order($params['id_order']);
        /** @var Orders $customerOrdersCollection */
        $customerOrdersCollection = $orderRepository->getCustomerOrders((int)$order->id_customer, [$order->id]);
        $onlyDeliveredOrders = $customerOrdersCollection->filter(
            function (\PrestaShop\Module\DemoViewOrderHooks\DTO\Order $order) {
                return $order->getOrderStateId() === self::DELIVERED_ORDER_STATE_ID;
            }
        );

        return $this->render($this->getModuleTemplatePath() . 'customer_delivered_orders.html.twig', [
            'currentOrderId' => (int) $params['id_order'],
            'orders' => $ordersPresenter->present(
            // Get all customer orders wit status 'Delivered' except currently viewed order
                $onlyDeliveredOrders,
                (int) $this->context->language->id
            ),
        ]);
    }

    /**
     * Displays customer's review about the order.
     */
    public function hookDisplayAdminOrderSide(array $params)
    {
        /** @var OrderReviewRepository $orderReviewRepository */
        $orderReviewRepository = $this->get('prestashop.module.demovieworderhooks.repository.order_review_repository');

        /** @var OrderReviewPresenter $orderReviewPresenter */
        $orderReviewPresenter = $this->get('prestashop.module.demovieworderhooks.presenter.order_review_presenter');

        $orderReview = $orderReviewRepository->findOneBy(['orderId' => $params['id_order']]);

        if (!$orderReview) {
            return '';
        }

        return $this->render($this->getModuleTemplatePath() . 'customer_satisfaction.html.twig', [
            'orderReview' => $orderReviewPresenter->present($orderReview),
        ]);
    }

    /**
     * Displays other orders from the same customer in a block.
     */
    public function hookDisplayAdminOrder(array $params)
    {
        /** @var OrderRepository $orderRepository */
        $orderRepository = $this->get('prestashop.module.demovieworderhooks.repository.order_repository');

        /** @var OrdersPresenter $ordersPresenter */
        $ordersPresenter = $this->get('prestashop.module.demovieworderhooks.presenter.orders_presenter');

        $order = new Order($params['id_order']);

        return $this->render($this->getModuleTemplatePath() . 'customer_orders.html.twig', [
            'currentOrderId' => (int) $params['id_order'],
            'orders' => $ordersPresenter->present(
            // Get all customer orders except currently viewed order
                $orderRepository->getCustomerOrders((int) $order->id_customer, [$order->id]),
                (int) $this->context->language->id
            ),
        ]);
    }

    /**
     * Displays previous/next order buttons.
     */
    public function hookDisplayAdminOrderTop(array $params)
    {
        /** @var OrderRepository $orderRepository */
        $orderRepository = $this->get('prestashop.module.demovieworderhooks.repository.order_repository');

        /** @var OrderLinkPresenter $orderLinkPresenter */
        $orderLinkPresenter = $this->get('prestashop.module.demovieworderhooks.presenter.order_link_presenter');

        $nextOrderId = $orderRepository->getNextOrderId((int) $params['id_order']);
        $previousOrderId = $orderRepository->getPreviousOrderId((int) $params['id_order']);

        return $this->render($this->getModuleTemplatePath() . 'order_navigation.html.twig', [
            'previousOrder' => $orderLinkPresenter->present($previousOrderId),
            'nextOrder' => $orderLinkPresenter->present($nextOrderId),
        ]);
    }

    /**
     * Render a twig template.
     */
    private function render(string $template, array $params = []): string
    {
        /** @var Twig_Environment $twig */
        $twig = $this->get('twig');

        return $twig->render($template, $params);
    }

    /**
     * Get path to this module's template directory
     */
    private function getModuleTemplatePath(): string
    {
        return "@Modules/$this->name/views/templates/admin/";
    }
}
