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
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteria;
use PrestaShop\Module\DemoMultistoreForm\Entity\ContentBlock;
use PrestaShopBundle\Entity\Shop;
use PrestaShopBundle\Controller\Admin\PrestaShopAdminController;
use PrestaShop\PrestaShop\Core\Grid\GridFactory;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use PrestaShop\PrestaShop\Core\Form\Handler;
use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\Builder\FormBuilder;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\Handler\FormHandler;
use PrestaShop\Module\DemoMultistoreForm\Database\ContentBlockGenerator;

class DemoMultistoreController extends PrestaShopAdminController
{
    public function index(
        #[Autowire(service: 'prestashop.module.demo_multistore.grid.content_block_grid_factory')]
        GridFactory $contentBlockGridFactory,
        #[Autowire(service: 'prestashop.module.demo_multistore.content_block_configuration.form_handler')]
        Handler $configurationFormHandler,
        EntityManagerInterface $entityManager,
    ): Response
    {
        // content block list in a grid
        $contentBlockGrid = $contentBlockGridFactory->getGrid(new SearchCriteria());

        // configuration form
        $configurationForm = $configurationFormHandler->getForm();

        $contentBlocCount = $entityManager->getRepository(ContentBlock::class)->count([]);

        return $this->render('@Modules/demomultistoreform/views/templates/admin/index.html.twig', [
            'title' => 'Content block list',
            'contentBlockGrid' => $this->presentGrid($contentBlockGrid),
            'configurationForm' => $configurationForm->createView(),
            'help_link' => false,
            'displayFixtureGeneratorLink' => $contentBlocCount === 0,
        ]);
    }

    public function create(
        Request $request,
        #[Autowire(service: 'prestashop.module.demo_multistore.form.identifiable_object.builder.content_block_form_builder')]
        FormBuilder $formBuilder,
        #[Autowire(service: 'prestashop.module.demo_multistore.form.identifiable_object.handler.content_block_form_handler')]
        FormHandler $formHandler

    ): Response
    {
        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        $result = $formHandler->handle($form);

        if (null !== $result->getIdentifiableObjectId()) {
            $this->addFlash(
                'success',
                $this->trans('Successful creation.', [], 'Admin.Notifications.Success')
            );

            return $this->redirectToRoute('demo_multistore');
        }

        return $this->render('@Modules/demomultistoreform/views/templates/admin/form.html.twig', [
            'contentBlockForm' => $form->createView(),
            'title' => 'Content block creation',
            'help_link' => false,
        ]);
    }

    public function edit(
        Request $request,
        int $contentBlockId,
        #[Autowire(service: 'prestashop.module.demo_multistore.form.identifiable_object.builder.content_block_form_builder')]
        FormBuilder $formBuilder,
        #[Autowire(service: 'prestashop.module.demo_multistore.form.identifiable_object.handler.content_block_form_handler')]
        FormHandler $formHandler
    ): Response
    {
        $form = $formBuilder->getFormFor((int) $contentBlockId);
        $form->handleRequest($request);

        $result = $formHandler->handleFor($contentBlockId, $form);

        if (null !== $result->getIdentifiableObjectId()) {
            $this->addFlash(
                'success',
                $this->trans('Successful edition.', [], 'Admin.Notifications.Success')
            );

            return $this->redirectToRoute('demo_multistore');
        }

        return $this->render('@Modules/demomultistoreform/views/templates/admin/form.html.twig', [
            'contentBlockForm' => $form->createView(),
            'title' => 'Content block edition',
            'help_link' => false,
        ]);
    }

    public function delete(
        int $contentBlockId,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $shopContext = $this->getShopContext();
        $contentBlock = $entityManager->getRepository(ContentBlock::class)->find($contentBlockId);

        if (!empty($contentBlock)) {
            if ($shopContext->isAllShopContext()) {
                $contentBlock->clearShops();
                $entityManager->remove($contentBlock);
            } else {
                $shopList = $entityManager->getRepository(Shop::class)
                    ->findBy(['id' => $shopContext->getAssociatedShopIds()]);
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
                $this->trans('Successful deletion.', [], 'Admin.Notifications.Success')
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

    public function saveConfiguration(
        Request $request,
        #[Autowire(service: 'prestashop.module.demo_multistore.content_block_configuration.form_handler')]
        Handler $configurationFormHandler,
    ): Response
    {
        $redirectResponse = $this->redirectToRoute('demo_multistore');

        $form = $configurationFormHandler->getForm();
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return $redirectResponse;
        }

        $data = $form->getData();
        $saveErrors = $configurationFormHandler->save($data);

        if (0 === count($saveErrors)) {
            $this->addFlash('success', $this->trans('Successful update.', [], 'Admin.Notifications.Success'));

            return $redirectResponse;
        }

        $this->addFlashErrors($saveErrors);

        return $redirectResponse;
    }

    public function generateFixtures(
        #[Autowire(service: 'prestashop.module.demo_multistore.content_block_generator')]
        ContentBlockGenerator $generator
    ): Response
    {
        $redirectResponse = $this->redirectToRoute('demo_multistore');

        try {
            $generator->generateContentBlockFixtures();
        } catch (\Exception $e) {
            $this->addFlash('error', 'There was a problem while generating context block fixtures');

            return $redirectResponse;
        }

        $this->addFlash('success', 'Successful content block fixtures generation.');


        return $redirectResponse;
    }

    public function toggleStatus(
        int $contentBlockId,
        EntityManagerInterface $entityManager
    ): Response
    {
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
                'message' => $this->trans('The status has been successfully updated.', [], 'Admin.Notifications.Success'),
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
