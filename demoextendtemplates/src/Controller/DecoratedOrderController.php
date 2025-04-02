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
use PrestaShopBundle\Controller\Admin\PrestaShopAdminController;
use PrestaShopBundle\Controller\Admin\Sell\Order\OrderController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use PrestaShop\PrestaShop\Core\Grid\GridFactory;
use PrestaShop\PrestaShop\Core\Kpi\Row\KpiRowFactoryInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\MapDecorated;
use PrestaShop\PrestaShop\Adapter\LegacyContext;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\Builder\FormBuilderInterface;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\Handler\FormHandlerInterface;
use PrestaShop\PrestaShop\Adapter\Currency\CurrencyDataProvider;
use Symfony\Component\Routing\RouterInterface;
use PrestaShop\PrestaShop\Core\Form\ConfigurableFormChoiceProviderInterface;
use PrestaShop\PrestaShop\Core\Order\OrderSiblingProviderInterface;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\OrderGridDefinitionFactory;
use PrestaShop\PrestaShop\Adapter\PDF\OrderInvoicePdfGenerator;
use PrestaShop\PrestaShop\Core\PDF\PDFGeneratorInterface;
use PrestaShop\PrestaShop\Core\Form\ChoiceProvider\LanguageByIdChoiceProvider;
use PrestaShop\PrestaShop\Core\Form\FormChoiceProviderInterface;

#[AsDecorator(OrderController::class)]
class DecoratedOrderController extends PrestaShopAdminController
{
    public function __construct(
        #[MapDecorated] private readonly OrderController $orderController
    ) {
    }

    public function indexAction(
        Request $request,
        OrderFilters $filters,
        #[Autowire(service: 'prestashop.core.kpi_row.factory.orders')] KpiRowFactoryInterface $orderKpiFactory,
        #[Autowire(service: 'prestashop.core.grid.factory.order')] GridFactory $orderGridFactory,
    )
    {
        $this->addFlash('demoextendtemplates-success', 'Custom success flash from demoextendtemplates module');

        return $this->orderController->indexAction($request, $filters, $orderKpiFactory, $orderGridFactory);
    }

    public function createAction(
        Request $request,
        LanguageByIdChoiceProvider $languageChoiceProvider,
        #[Autowire(service: 'prestashop.core.form.choice_provider.currency_by_id')] FormChoiceProviderInterface $currencyChoiceProvider,
    ) {
        return $this->orderController->createAction($request, $languageChoiceProvider, $currencyChoiceProvider);
    }

    public function placeAction(
        Request $request,
        #[Autowire(service: 'prestashop.core.form.identifiable_object.handler.cart_summary_form_handler')] FormHandlerInterface $formHandler
    ) {
        return $this->orderController->placeAction($request, $formHandler);
    }

    public function generateDeliverySlipPdfAction(
        int $orderId,
        #[Autowire(service: 'prestashop.adapter.pdf.delivery_slip_pdf_generator')] PDFGeneratorInterface $deliverySlipPdfGenerator,
    ) {
        return $this->orderController->generateDeliverySlipPdfAction($orderId, $deliverySlipPdfGenerator);
    }

    public function generateInvoicePdfAction(
        int $orderId,
        #[Autowire(service: 'prestashop.adapter.pdf.order_invoice_pdf_generator')] OrderInvoicePdfGenerator $invoicePdfGenerator,
    ) {
        return $this->orderController->generateInvoicePdfAction($orderId, $invoicePdfGenerator);
    }

    public function changeOrdersStatusAction(Request $request)
    {
        return $this->orderController->changeOrdersStatusAction($request);
    }

    public function exportAction(
        OrderFilters $filters,
        #[Autowire(service: 'prestashop.core.grid.factory.order')] GridFactory $orderGridFactory,
    ) {
        return $this->orderController->exportAction($filters, $orderGridFactory);
    }

    public function searchAction(
        Request $request,
        #[Autowire(service: 'prestashop.core.grid.definition.factory.order')] OrderGridDefinitionFactory $orderGridDefinitionFactory,
    ) {
        return $this->orderController->searchAction($request, $orderGridDefinitionFactory);
    }

    public function viewAction(
        int $orderId,
        Request $request,
        #[Autowire(service: 'prestashop.core.form.identifiable_object.builder.cancel_product_form_builder')] FormBuilderInterface $formBuilder,
        #[Autowire(service: 'prestashop.adapter.order.order_sibling_provider')] OrderSiblingProviderInterface $orderSiblingProvider,
        CurrencyDataProvider $currencyDataProvider,
    ) {
        return $this->orderController->viewAction($orderId, $request, $formBuilder, $orderSiblingProvider, $currencyDataProvider);
    }

    public function partialRefundAction(
        int $orderId,
        Request $request,
        #[Autowire(service: 'prestashop.core.form.identifiable_object.builder.cancel_product_form_builder')] FormBuilderInterface $formBuilder,
        #[Autowire(service: 'prestashop.core.form.identifiable_object.partial_refund_form_handler')] FormHandlerInterface $formHandler,
    ) {
        return $this->orderController->partialRefundAction($orderId, $request, $formBuilder, $formHandler);
    }

    public function standardRefundAction(
        int $orderId,
        Request $request,
        #[Autowire(service: 'prestashop.core.form.identifiable_object.builder.cancel_product_form_builder')] FormBuilderInterface $formBuilder,
        #[Autowire(service: 'prestashop.core.form.identifiable_object.standard_refund_form_handler')] FormHandlerInterface $formHandler,
    ) {
        return $this->orderController->standardRefundAction($orderId, $request, $formBuilder, $formHandler);
    }

    public function returnProductAction(
        int $orderId, 
        Request $request,
        #[Autowire(service: 'prestashop.core.form.identifiable_object.builder.cancel_product_form_builder')] FormBuilderInterface $formBuilder,
        #[Autowire(service: 'prestashop.core.form.identifiable_object.return_product_form_handler')] FormHandlerInterface $formHandler,
    ) {
        return $this->orderController->returnProductAction($orderId, $request, $formBuilder, $formHandler);
    }

    public function addProductAction(
        int $orderId,
        Request $request,
        #[Autowire(service: 'prestashop.core.form.identifiable_object.builder.cancel_product_form_builder')] FormBuilderInterface $formBuilder,
        CurrencyDataProvider $currencyDataProvider
    ) {
        return $this->orderController->addProductAction($orderId, $request, $formBuilder, $currencyDataProvider);
    }

    public function getProductPricesAction(int $orderId)
    {
        return $this->orderController->getProductPricesAction($orderId);
    }

    public function getInvoicesAction(
        int $orderId,
        #[Autowire(service: 'prestashop.adapter.form.choice_provider.order_invoice_by_id')] ConfigurableFormChoiceProviderInterface $choiceProvider,
    ) {
        return $this->orderController->getInvoicesAction($orderId, $choiceProvider);
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

    public function updateProductAction(
        int $orderId,
        int $orderDetailId,
        Request $request,
        #[Autowire(service: 'prestashop.core.form.identifiable_object.builder.cancel_product_form_builder')] FormBuilderInterface $formBuilder,
        CurrencyDataProvider $currencyDataProvider
    ) {
        return $this->orderController->updateProductAction($orderId, $orderDetailId, $request, $formBuilder, $currencyDataProvider);
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

    public function sendMessageAction(Request $request, int $orderId, RouterInterface $router)
    {
        return $this->orderController->sendMessageAction($request, $orderId, $router);
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

    public function getProductsListAction(
        int $orderId,
        #[Autowire(service: 'prestashop.core.form.identifiable_object.builder.cancel_product_form_builder')] FormBuilderInterface $formBuilder,
        CurrencyDataProvider $currencyDataProvider
    ) {
        return $this->orderController->getProductsListAction($orderId, $formBuilder, $currencyDataProvider);
    }

    public function generateInvoiceAction(int $orderId)
    {
        return $this->orderController->generateInvoiceAction($orderId);
    }

    public function sendProcessOrderEmailAction(Request $request)
    {
        return $this->orderController->sendProcessOrderEmailAction($request);
    }

    public function cancellationAction(
        int $orderId,
        Request $request,
        #[Autowire(service: 'prestashop.core.form.identifiable_object.builder.cancel_product_form_builder')] FormBuilderInterface $formBuilder,
        #[Autowire(service: 'prestashop.core.form.identifiable_object.cancellation_form_handler')] FormHandlerInterface $formHandler,
    ) {
        return $this->orderController->cancellationAction($orderId, $request, $formBuilder, $formHandler);
    }

    public function configureProductPaginationAction(Request $request)
    {
        return $this->orderController->configureProductPaginationAction($request);
    }

    public function displayCustomizationImageAction(int $orderId, string $value, LegacyContext $context)
    {
        return $this->orderController->displayCustomizationImageAction($orderId, $value, $context);
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
