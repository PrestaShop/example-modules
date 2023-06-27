<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
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
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */
declare(strict_types=1);

namespace PrestaShop\Module\DemoExtendTemplates\Controller;

use PrestaShop\PrestaShop\Core\Search\Filters\OrderFilters;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Controller\Admin\Sell\Order\OrderController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DecoratedOrderController extends FrameworkBundleAdminController
{
    /**
     * @var OrderController
     */
    private $orderController;

    /**
     * @param OrderController $orderController
     */
    public function __construct(
        OrderController $orderController
    ) {
        $this->orderController = $orderController;
    }

    public function indexAction(Request $request, OrderFilters $filters)
    {
        $this->addFlash('demoextendtemplates-success', 'Custom success flash from demoextendtemplates module');

        return $this->orderController->indexAction($request, $filters);
    }

    public function createAction(Request $request)
    {
        return $this->orderController->createAction($request);
    }

    public function placeAction(Request $request)
    {
        return $this->orderController->placeAction($request);
    }

    public function generateDeliverySlipPdfAction(int $orderId)
    {
        return $this->orderController->generateDeliverySlipPdfAction($orderId);
    }

    public function generateInvoicePdfAction(int $orderId)
    {
        return $this->orderController->generateInvoicePdfAction($orderId);
    }

    public function changeOrdersStatusAction(Request $request)
    {
        return $this->orderController->changeOrdersStatusAction($request);
    }

    public function exportAction(OrderFilters $filters)
    {
        return $this->orderController->exportAction($filters);
    }

    public function searchAction(Request $request)
    {
        return $this->orderController->searchAction($request);
    }

    public function viewAction(int $orderId, Request $request)
    {
        return $this->orderController->viewAction($orderId, $request);
    }

    public function partialRefundAction(int $orderId, Request $request)
    {
        return $this->orderController->partialRefundAction($orderId, $request);
    }

    public function standardRefundAction(int $orderId, Request $request)
    {
        return $this->orderController->standardRefundAction($orderId, $request);
    }

    public function returnProductAction(int $orderId, Request $request)
    {
        return $this->orderController->returnProductAction($orderId, $request);
    }

    public function addProductAction(int $orderId, Request $request)
    {
        return $this->orderController->addProductAction($orderId, $request);
    }

    public function getProductPricesAction(int $orderId)
    {
        return $this->orderController->getProductPricesAction($orderId);
    }

    public function getInvoicesAction(int $orderId)
    {
        return $this->orderController->getInvoicesAction($orderId);
    }

    public function getDocumentsAction(int $orderId)
    {
        return $this->orderController->getDocumentsAction($orderId);
    }

    public function getShippingAction(int $orderId)
    {
        return $this->orderController->getShippingAction($orderId);
    }

    public function updateShippingAction(int $orderId, Request $request)
    {
        return $this->orderController->updateShippingAction($orderId, $request);
    }

    public function removeCartRuleAction(int $orderId, int $orderCartRuleId)
    {
        return $this->orderController->removeCartRuleAction($orderId, $orderCartRuleId);
    }

    public function updateInvoiceNoteAction(int $orderId, int $orderInvoiceId, Request $request)
    {
        return $this->orderController->updateInvoiceNoteAction($orderId, $orderInvoiceId, $request);
    }

    public function updateProductAction(int $orderId, int $orderDetailId, Request $request)
    {
        return $this->orderController->updateProductAction($orderId, $orderDetailId, $request);
    }

    public function addCartRuleAction(int $orderId, Request $request)
    {
        return $this->orderController->addCartRuleAction($orderId, $request);
    }

    public function updateStatusAction(int $orderId, Request $request)
    {
        return $this->orderController->updateStatusAction($orderId, $request);
    }

    public function updateStatusFromListAction(int $orderId, Request $request)
    {
        return $this->orderController->updateStatusFromListAction($orderId, $request);
    }

    public function addPaymentAction(int $orderId, Request $request)
    {
        return $this->orderController->addPaymentAction($orderId, $request);
    }

    public function previewAction(int $orderId)
    {
        return $this->orderController->previewAction($orderId);
    }

    public function duplicateOrderCartAction(int $orderId)
    {
        return $this->orderController->duplicateOrderCartAction($orderId);
    }

    public function sendMessageAction(Request $request, int $orderId)
    {
        return $this->orderController->sendMessageAction($request, $orderId);
    }

    public function changeCustomerAddressAction(Request $request)
    {
        return $this->orderController->changeCustomerAddressAction($request);
    }

    public function changeCurrencyAction(int $orderId, Request $request)
    {
        return $this->orderController->changeCurrencyAction($orderId, $request);
    }

    public function resendEmailAction(int $orderId, int $orderStatusId, int $orderHistoryId)
    {
        return $this->orderController->resendEmailAction($orderId, $orderStatusId, $orderHistoryId);
    }

    public function deleteProductAction(int $orderId, int $orderDetailId)
    {
        return $this->orderController->deleteProductAction($orderId, $orderDetailId);
    }

    public function getDiscountsAction(int $orderId)
    {
        return $this->orderController->getDiscountsAction($orderId);
    }

    public function getPricesAction(int $orderId)
    {
        return $this->orderController->getPricesAction($orderId);
    }

    public function getPaymentsAction(int $orderId)
    {
        return $this->orderController->getPaymentsAction($orderId);
    }

    public function getProductsListAction(int $orderId)
    {
        return $this->orderController->getProductsListAction($orderId);
    }

    public function generateInvoiceAction(int $orderId)
    {
        return $this->orderController->generateInvoiceAction($orderId);
    }

    public function sendProcessOrderEmailAction(Request $request)
    {
        return $this->orderController->sendProcessOrderEmailAction($request);
    }

    public function cancellationAction(int $orderId, Request $request)
    {
        return $this->orderController->cancellationAction($orderId, $request);
    }

    public function configureProductPaginationAction(Request $request)
    {
        return $this->orderController->configureProductPaginationAction($request);
    }

    public function displayCustomizationImageAction(int $orderId, string $value)
    {
        return $this->orderController->displayCustomizationImageAction($orderId, $value);
    }

    public function setInternalNoteAction(int $orderId, Request $request)
    {
        return $this->orderController->setInternalNoteAction($orderId, $request);
    }

    public function searchProductsAction(Request $request)
    {
        return $this->orderController->searchProductsAction($request);
    }
}
