<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */

declare(strict_types=1);

namespace PrestaShop\Module\DemoMultistoreForm\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteria;
use PrestaShop\Module\DemoMultistoreForm\Entity\ContentBlock;
use PrestaShopBundle\Entity\Shop;

class DemoMultistoreController extends FrameworkBundleAdminController
{
    public function index(Request $request): Response
    {
        // content block list in a grid
        $contentBlockGridFactory = $this->get('prestashop.module.demo_multistore.grid.content_block_grid_factory');
        $contentBlockGrid = $contentBlockGridFactory->getGrid(new SearchCriteria());

        // configuration form
        $configurationForm = $this->get('prestashop.module.demo_multistore.content_block_configuration.form_handler')->getForm();

        $contentBlocCount = $this->getDoctrine()
            ->getRepository(ContentBlock::class)
            ->count([]);

        return $this->render('@Modules/demomultistoreform/views/templates/admin/index.html.twig', [
            'title' => 'Content block list',
            'contentBlockGrid' => $this->presentGrid($contentBlockGrid),
            'configurationForm' => $configurationForm->createView(),
            'help_link' => false,
            'displayFixtureGeneratorLink' => $contentBlocCount === 0,
        ]);
    }

    public function create(Request $request): Response
    {
        $formDataHandler = $this->get('prestashop.module.demo_multistore.form.identifiable_object.builder.content_block_form_builder');
        $form = $formDataHandler->getForm();
        $form->handleRequest($request);

        $formHandler = $this->get('prestashop.module.demo_multistore.form.identifiable_object.handler.content_block_form_handler');
        $result = $formHandler->handle($form);

        if (null !== $result->getIdentifiableObjectId()) {
            $this->addFlash(
                'success',
                $this->trans('Successful creation.', 'Admin.Notifications.Success')
            );

            return $this->redirectToRoute('demo_multistore');
        }

        return $this->render('@Modules/demomultistoreform/views/templates/admin/form.html.twig', [
            'contentBlockForm' => $form->createView(),
            'title' => 'Content block creation',
            'help_link' => false,
        ]);
    }

    public function edit(Request $request, int $contentBlockId): Response
    {
        $formBuilder = $this->get('prestashop.module.demo_multistore.form.identifiable_object.builder.content_block_form_builder');
        $form = $formBuilder->getFormFor((int) $contentBlockId);
        $form->handleRequest($request);

        $formHandler = $this->get('prestashop.module.demo_multistore.form.identifiable_object.handler.content_block_form_handler');
        $result = $formHandler->handleFor($contentBlockId, $form);

        if (null !== $result->getIdentifiableObjectId()) {
            $this->addFlash(
                'success',
                $this->trans('Successful edition.', 'Admin.Notifications.Success')
            );

            return $this->redirectToRoute('demo_multistore');
        }

        return $this->render('@Modules/demomultistoreform/views/templates/admin/form.html.twig', [
            'contentBlockForm' => $form->createView(),
            'title' => 'Content block edition',
            'help_link' => false,
        ]);
    }

    public function delete(Request $request, int $contentBlockId): Response
    {
        $contentBlock = $this->getDoctrine()
            ->getRepository(ContentBlock::class)
            ->find($contentBlockId);

        if (!empty($contentBlock)) {
            $multistoreContext = $this->get('prestashop.adapter.shop.context');
            $entityManager = $this->get('doctrine.orm.entity_manager');
            if ($multistoreContext->isAllShopContext()) {
                $contentBlock->clearShops();
                $entityManager->remove($contentBlock);
            } else {
                $shopList = $this->getDoctrine()
                    ->getRepository(Shop::class)
                    ->findBy(['id' => $multistoreContext->getContextListShopID()]);
                foreach ($shopList as $shop) {
                    $contentBlock->removeShop($shop);
                    $entityManager->flush();
                }
                if (count($contentBlock->getShops()) === 0) {
                    $entityManager->remove($contentBlock);
                }
            }
            $entityManager->flush();
            $this->addFlash(
                'success',
                $this->trans('Successful deletion.', 'Admin.Notifications.Success')
            );

            return $this->redirectToRoute('demo_multistore');
        }

        $this->addFlash(
            'error',
            sprintf(
                'Cannot find content block %d',
                $contentBlockId
            )
        );

        return $this->redirectToRoute('demo_multistore');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function saveConfiguration(Request $request): Response
    {
        $redirectResponse = $this->redirectToRoute('demo_multistore');

        $form = $this->get('prestashop.module.demo_multistore.content_block_configuration.form_handler')->getForm();
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return $redirectResponse;
        }

        $data = $form->getData();
        $saveErrors = $this->get('prestashop.module.demo_multistore.content_block_configuration.form_handler')->save($data);

        if (0 === count($saveErrors)) {
            $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));

            return $redirectResponse;
        }

        $this->flashErrors($saveErrors);

        return $redirectResponse;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function generateFixtures(Request $request): Response
    {
        $redirectResponse = $this->redirectToRoute('demo_multistore');

        try {
            $generator = $this->get('prestashop.module.demo_multistore.content_block_generator');
            $generator->generateContentBlockFixtures();
        } catch (\Exception $e) {
            $this->addFlash('error', 'There was a problem while generating context block fixtures');

            return $redirectResponse;
        }

        $this->addFlash('success', 'Successful content block fixtures generation.');


        return $redirectResponse;
    }

    /**
     * @param Request $request
     * @param int $contentBlockId
     *
     * @return Response
     */
    public function toggleStatus(Request $request, int $contentBlockId): Response
    {
        $entityManager = $this->get('doctrine.orm.entity_manager');
        $contentBlock = $entityManager
            ->getRepository(ContentBlock::class)
            ->findOneBy(['id' => $contentBlockId]);

        if (empty($contentBlock)) {
            return $this->json([
                'status' => false,
                'message' => sprintf('Content block %d doesn\'t exist', $contentBlockId)
            ]);
        }

        try {
            $contentBlock->setEnable(!$contentBlock->getEnable());
            $entityManager->flush();
            $response = [
                'status' => true,
                'message' => $this->trans('The status has been successfully updated.', 'Admin.Notifications.Success'),
            ];
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'message' => sprintf(
                    'There was an error while updating the status of content block %d: %s',
                    $contentBlockId,
                    $e->getMessage()
                ),
            ];
        }

        return $this->json($response);
    }
}
