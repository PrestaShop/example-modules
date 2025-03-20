<?php

declare(strict_types=1);

namespace PrestaShop\Module\DemoSymfonyFormSimple\Controller;

use PrestaShop\PrestaShop\Core\Form\FormHandlerInterface;
use PrestaShopBundle\Controller\Admin\PrestaShopAdminController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DemoConfigurationController extends PrestaShopAdminController
{
    public function index(
        Request $request,
        #[Autowire(service: 'prestashop.module.demosymfonyformsimple.form.demo_configuration_text_form_data_handler')]
        FormHandlerInterface $textFormDataHandler,
    ): Response {
        $textForm = $textFormDataHandler->getForm();
        $textForm->handleRequest($request);

        if ($textForm->isSubmitted() && $textForm->isValid()) {
            /** You can return array of errors in form handler, and they can be displayed to user with flashErrors */
            $errors = $textFormDataHandler->save($textForm->getData());

            if (empty($errors)) {
                $this->addFlash('success', $this->trans('Successful update.', [], 'Admin.Notifications.Success'));

                return $this->redirectToRoute('demo_configuration_form_simple');
            }

            $this->addFlashErrors($errors);
        }

        return $this->render('@Modules/demosymfonyformsimple/views/templates/admin/form.html.twig', [
            'demoConfigurationForm' => $textForm->createView(),
        ]);
    }
}
