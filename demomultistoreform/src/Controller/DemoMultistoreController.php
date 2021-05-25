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

namespace PrestaShop\Module\DemoMultistoreForm\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteria;
use PrestaShop\Module\DemoMultistoreForm\Entity\ContentBlock;

class DemoMultistoreController extends FrameworkBundleAdminController
{
    public function index(Request $request): Response
    {
        // content block list in a grid
        $contentBlockGridFactory = $this->get('prestashop.module.demo_multistore.grid.content_block_grid_factory');
        $contentBlockGrid = $contentBlockGridFactory->getGrid(new SearchCriteria());

        // configuration form
        $configurationForm = $this->get('prestashop.module.demo_multistore.content_block_configuration.form_handler')->getForm();

        return $this->render('@Modules/demomultistoreform/views/templates/admin/index.html.twig', [
            'title' => 'Content block list',
            'contentBlockGrid' => $this->presentGrid($contentBlockGrid),
            'configurationForm' => $configurationForm->createView(),
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
            'title' => 'Content block creation'
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
            'title' => 'Content block edition'
        ]);
    }

    public function delete(Request $request, int $contentBlockId): Response
    {
        $contentBlock = $this->getDoctrine()
            ->getRepository(ContentBlock::class)
            ->find($contentBlockId);

        if (!empty($contentBlock)) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->remove($contentBlock);
            $em->flush();
            $this->addFlash(
                'success',
                $this->trans('Successful deletion.', 'Admin.Notifications.Success')
            );

            return $this->redirectToRoute('demo_multistore');
        }

        $this->addFlash(
            'error',
            $this->trans(
                'Cannot find content block %contentBlock%',
                'Modules.DemoMultistoreForm.Admin',
                ['%contentBlock%' => $contentBlockId]
            )
        );

        return $this->redirectToRoute('demo_multistore');
    }

    public function saveConfiguration(Request $request)
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
}
