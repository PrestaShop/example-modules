<?php
/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */
declare(strict_types=1);

namespace Module\DemoDoctrine\Controller\Admin;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Exception\TableExistsException;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Module\DemoDoctrine\Entity\Quote;
use Module\DemoDoctrine\Entity\QuoteLang;
use Module\DemoDoctrine\Grid\Definition\Factory\QuoteGridDefinitionFactory;
use Module\DemoDoctrine\Grid\Filters\QuoteFilters;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Entity\Lang;
use PrestaShopBundle\Entity\Repository\LangRepository;
use PrestaShopBundle\Service\Grid\ResponseBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QuotesController extends FrameworkBundleAdminController
{
    /**
     * List quotes
     *
     * @param QuoteFilters $filters
     *
     * @return Response
     */
    public function indexAction(QuoteFilters $filters)
    {
        $quoteGridFactory = $this->get('prestashop.module.demodoctrine.grid.factory.quotes');
        $quoteGrid = $quoteGridFactory->getGrid($filters);

        return $this->render(
            '@Modules/demodoctrine/views/templates/admin/index.html.twig',
            [
                'enableSidebar' => true,
                'layoutTitle' => $this->trans('Quotes', 'Modules.Demodoctrine.Admin'),
                'layoutHeaderToolbarBtn' => $this->getToolbarButtons(),
                'quoteGrid' => $this->presentGrid($quoteGrid),
            ]
        );
    }

    /**
     * Provides filters functionality.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function searchAction(Request $request)
    {
        /** @var ResponseBuilder $responseBuilder */
        $responseBuilder = $this->get('prestashop.bundle.grid.response_builder');

        return $responseBuilder->buildSearchResponse(
            $this->get('prestashop.module.demodoctrine.grid.definition.factory.quotes'),
            $request,
            QuoteGridDefinitionFactory::GRID_ID,
            'ps_demodoctrine_quote_index'
        );
    }

    /**
     * List quotes
     *
     * @param Request $request
     *
     * @return Response
     */
    public function generateAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            $generator = $this->get('prestashop.module.demodoctrine.quotes.generator');
            $generator->generateQuotes();
            $this->addFlash('success', $this->trans('Quotes were successfully generated.', 'Modules.Demodoctrine.Admin'));

            return $this->redirectToRoute('ps_demodoctrine_quote_index');
        }

        return $this->render(
            '@Modules/demodoctrine/views/templates/admin/generate.html.twig',
            [
                'enableSidebar' => true,
                'layoutTitle' => $this->trans('Quotes', 'Modules.Demodoctrine.Admin'),
                'layoutHeaderToolbarBtn' => $this->getToolbarButtons(),
            ]
        );
    }

    /**
     * Create quote
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $quoteFormBuilder = $this->get('prestashop.module.demodoctrine.form.identifiable_object.builder.quote_form_builder');
        $quoteForm = $quoteFormBuilder->getForm();
        $quoteForm->handleRequest($request);

        $quoteFormHandler = $this->get('prestashop.module.demodoctrine.form.identifiable_object.handler.quote_form_handler');
        $result = $quoteFormHandler->handle($quoteForm);

        if (null !== $result->getIdentifiableObjectId()) {
            $this->addFlash(
                'success',
                $this->trans('Successful creation.', 'Admin.Notifications.Success')
            );

            return $this->redirectToRoute('ps_demodoctrine_quote_index');
        }

        return $this->render('@Modules/demodoctrine/views/templates/admin/create.html.twig', [
            'quoteForm' => $quoteForm->createView(),
        ]);
    }

    /**
     * Edit quote
     *
     * @param Request $request
     * @param int $quoteId
     *
     * @return Response
     */
    public function editAction(Request $request, $quoteId)
    {
        $quoteFormBuilder = $this->get('prestashop.module.demodoctrine.form.identifiable_object.builder.quote_form_builder');
        $quoteForm = $quoteFormBuilder->getFormFor((int) $quoteId);
        $quoteForm->handleRequest($request);

        $quoteFormHandler = $this->get('prestashop.module.demodoctrine.form.identifiable_object.handler.quote_form_handler');
        $result = $quoteFormHandler->handleFor((int) $quoteId, $quoteForm);

        if ($result->isSubmitted() && $result->isValid()) {
            $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));

            return $this->redirectToRoute('ps_demodoctrine_quote_index');
        }

        return $this->render('@Modules/demodoctrine/views/templates/admin/edit.html.twig', [
            'quoteForm' => $quoteForm->createView(),
        ]);
    }

    /**
     * Delete quote
     *
     * @param int $quoteId
     *
     * @return Response
     */
    public function deleteAction($quoteId)
    {
        $repository = $this->get('prestashop.module.demodoctrine.repository.quote_repository');
        try {
            $quote = $repository->findOneById($quoteId);
        } catch (EntityNotFoundException $e) {
            $quote = null;
        }

        if (null !== $quote) {
            /** @var EntityManagerInterface $em */
            $em = $this->get('doctrine.orm.entity_manager');
            $em->remove($quote);
            $em->flush();

            $this->addFlash(
                'success',
                $this->trans('Successful deletion.', 'Admin.Notifications.Success')
            );
        } else {
            $this->addFlash(
                'error',
                $this->trans(
                    'Cannot find quote %quote%',
                    'Modules.Demodoctrine.Admin',
                    ['%quote%' => $quoteId]
                )
            );
        }

        return $this->redirectToRoute('ps_demodoctrine_quote_index');
    }

    /**
     * Delete bulk quotes
     *
     * @param Request $request
     *
     * @return Response
     */
    public function deleteBulkAction(Request $request)
    {
        $quoteIds = $request->request->get('quote_bulk');
        $repository = $this->get('prestashop.module.demodoctrine.repository.quote_repository');
        try {
            $quotes = $repository->findById($quoteIds);
        } catch (EntityNotFoundException $e) {
            $quotes = null;
        }
        if (!empty($quotes)) {
            /** @var EntityManagerInterface $em */
            $em = $this->get('doctrine.orm.entity_manager');
            foreach ($quotes as $quote) {
                $em->remove($quote);
            }
            $em->flush();

            $this->addFlash(
                'success',
                $this->trans('The selection has been successfully deleted.', 'Admin.Notifications.Success')
            );
        }

        return $this->redirectToRoute('ps_demodoctrine_quote_index');
    }

    /**
     * @return array[]
     */
    private function getToolbarButtons()
    {
        return [
            'add' => [
                'desc' => $this->trans('Add new quote', 'Modules.Demodoctrine.Admin'),
                'icon' => 'add_circle_outline',
                'href' => $this->generateUrl('ps_demodoctrine_quote_create'),
            ],
            'generate' => [
                'desc' => $this->trans('Generate quotes', 'Modules.Demodoctrine.Admin'),
                'icon' => 'add_circle_outline',
                'href' => $this->generateUrl('ps_demodoctrine_quote_generate'),
            ],
        ];
    }
}
