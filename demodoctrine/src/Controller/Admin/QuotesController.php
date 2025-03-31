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

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Module\DemoDoctrine\Database\QuoteGenerator;
use Module\DemoDoctrine\Grid\Definition\Factory\QuoteGridDefinitionFactory;
use Module\DemoDoctrine\Grid\Filters\QuoteFilters;
use Module\DemoDoctrine\Repository\QuoteRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\Builder\FormBuilderInterface;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\Handler\FormHandlerInterface;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\GridDefinitionFactoryInterface;
use PrestaShop\PrestaShop\Core\Grid\GridFactoryInterface;
use PrestaShopBundle\Controller\Admin\PrestaShopAdminController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QuotesController extends PrestaShopAdminController
{
    public function indexAction(
        QuoteFilters $filters,
        #[Autowire(service: 'prestashop.module.demodoctrine.grid.factory.quotes')]
        GridFactoryInterface $quoteGridFactory,
    ): Response {
        return $this->render(
            '@Modules/demodoctrine/views/templates/admin/index.html.twig',
            [
                'enableSidebar' => true,
                'layoutTitle' => $this->trans('Quotes', [], 'Modules.Demodoctrine.Admin'),
                'layoutHeaderToolbarBtn' => $this->getToolbarButtons(),
                'quoteGrid' => $this->presentGrid($quoteGridFactory->getGrid($filters)),
            ]
        );
    }

    public function searchAction(
        Request $request,
        #[Autowire(service: 'prestashop.module.demodoctrine.grid.definition.factory.quotes')]
        GridDefinitionFactoryInterface $quoteGridDefinitionFactory,
    ): RedirectResponse {
        return $this->buildSearchResponse(
            $quoteGridDefinitionFactory,
            $request,
            QuoteGridDefinitionFactory::GRID_ID,
            'ps_demodoctrine_quote_index'
        );
    }

    public function generateAction(
        Request $request,
        QuoteGenerator $generator,
    ): Response {
        if ($request->isMethod(Request::METHOD_POST)) {
            $generator->generateQuotes();
            $this->addFlash('success', $this->trans('Quotes were successfully generated.', [], 'Modules.Demodoctrine.Admin'));

            return $this->redirectToRoute('ps_demodoctrine_quote_index');
        }

        return $this->render(
            '@Modules/demodoctrine/views/templates/admin/generate.html.twig',
            [
                'enableSidebar' => true,
                'layoutTitle' => $this->trans('Quotes', [], 'Modules.Demodoctrine.Admin'),
                'layoutHeaderToolbarBtn' => $this->getToolbarButtons(),
            ]
        );
    }

    public function createAction(
        Request $request,
        #[Autowire(service: 'prestashop.module.demodoctrine.form.identifiable_object.builder.quote_form_builder')]
        FormBuilderInterface $quoteFormBuilder,
        #[Autowire(service: 'prestashop.module.demodoctrine.form.identifiable_object.handler.quote_form_handler')]
        FormHandlerInterface $quoteFormHandler,
    ): Response {
        $quoteForm = $quoteFormBuilder->getForm();
        $quoteForm->handleRequest($request);
        $result = $quoteFormHandler->handle($quoteForm);

        if (null !== $result->getIdentifiableObjectId()) {
            $this->addFlash(
                'success',
                $this->trans('Successful creation.', [], 'Admin.Notifications.Success')
            );

            return $this->redirectToRoute('ps_demodoctrine_quote_index');
        }

        return $this->render('@Modules/demodoctrine/views/templates/admin/create.html.twig', [
            'quoteForm' => $quoteForm->createView(),
        ]);
    }

    public function editAction(
        Request $request,
        int $quoteId,
        #[Autowire(service: 'prestashop.module.demodoctrine.form.identifiable_object.builder.quote_form_builder')]
        FormBuilderInterface $quoteFormBuilder,
        #[Autowire(service: 'prestashop.module.demodoctrine.form.identifiable_object.handler.quote_form_handler')]
        FormHandlerInterface $quoteFormHandler,
    ): Response {
        $quoteForm = $quoteFormBuilder->getFormFor((int) $quoteId);
        $quoteForm->handleRequest($request);
        $result = $quoteFormHandler->handleFor((int) $quoteId, $quoteForm);

        if ($result->isSubmitted() && $result->isValid()) {
            $this->addFlash('success', $this->trans('Successful update.', [], 'Admin.Notifications.Success'));

            return $this->redirectToRoute('ps_demodoctrine_quote_index');
        }

        return $this->render('@Modules/demodoctrine/views/templates/admin/edit.html.twig', [
            'quoteForm' => $quoteForm->createView(),
        ]);
    }

    public function deleteAction(
        int $quoteId,
        EntityManagerInterface $entityManager,
        QuoteRepository $quoteRepository,
    ): RedirectResponse {
        try {
            $quote = $quoteRepository->findOneById($quoteId);
        } catch (EntityNotFoundException $e) {
            $quote = null;
        }

        if (null !== $quote) {
            $entityManager->remove($quote);
            $entityManager->flush();

            $this->addFlash(
                'success',
                $this->trans('Successful deletion.', [], 'Admin.Notifications.Success'),
            );
        } else {
            $this->addFlash(
                'error',
                $this->trans(
                    'Cannot find quote %quote%',
                    ['%quote%' => $quoteId],
                    'Modules.Demodoctrine.Admin',
                ),
            );
        }

        return $this->redirectToRoute('ps_demodoctrine_quote_index');
    }

    public function deleteBulkAction(
        Request $request,
        EntityManagerInterface $entityManager,
        QuoteRepository $quoteRepository,
    ): RedirectResponse {
        $quoteIds = $request->request->all('quote_bulk');
        try {
            $quotes = $quoteRepository->findById($quoteIds);
        } catch (EntityNotFoundException $e) {
            $quotes = null;
        }

        if (!empty($quotes)) {
            foreach ($quotes as $quote) {
                $entityManager->remove($quote);
            }
            $entityManager->flush();

            $this->addFlash(
                'success',
                $this->trans('The selection has been successfully deleted.', [], 'Admin.Notifications.Success')
            );
        }

        return $this->redirectToRoute('ps_demodoctrine_quote_index');
    }

    private function getToolbarButtons(): array
    {
        return [
            'add' => [
                'desc' => $this->trans('Add new quote', [], 'Modules.Demodoctrine.Admin'),
                'icon' => 'add_circle_outline',
                'href' => $this->generateUrl('ps_demodoctrine_quote_create'),
            ],
            'generate' => [
                'desc' => $this->trans('Generate quotes', [], 'Modules.Demodoctrine.Admin'),
                'icon' => 'add_circle_outline',
                'href' => $this->generateUrl('ps_demodoctrine_quote_generate'),
            ],
        ];
    }
}
