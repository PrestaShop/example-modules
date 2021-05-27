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

namespace PrestaShop\Module\DemoSymfonyForm\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

class DemoConfigurationMultipleFormsController extends FrameworkBundleAdminController
{
    public function index(Request $request): Response
    {
        $choiceFormDataHandler = $this->get('prestashop.module.demosymfonyform.form.demo_configuration_choice_form_data_handler');
        $choiceForm = $choiceFormDataHandler->getForm();
        $otherFormDataHandler = $this->get('prestashop.module.demosymfonyform.form.demo_configuration_other_form_data_handler');
        $otherForm = $otherFormDataHandler->getForm();

        return $this->render('@Modules/demosymfonyform/views/templates/admin/multipleForms.html.twig', [
            'demoConfigurationChoiceForm' => $choiceForm->createView(),
            'demoConfigurationOtherForm' => $otherForm->createView(),
        ]);
    }

    public function saveChoicesForm(Request $request): Response
    {
        $choiceFormDataHandler = $this->get('prestashop.module.demosymfonyform.form.demo_configuration_choice_form_data_handler');
        $choiceForm = $choiceFormDataHandler->getForm();
        $choiceForm->handleRequest($request);

        if ($choiceForm->isSubmitted() && $choiceForm->isValid()) {
            $errors = $choiceFormDataHandler->save($choiceForm->getData());

            if (empty($errors)) {
                $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));
            } else {
                $this->flashErrors($errors);
            }
        }
        return $this->redirectToRoute('demo_configuration_multiple_forms');

    }

    public function saveOtherForm(Request $request): Response
    {
        $otherFormDataHandler = $this->get('prestashop.module.demosymfonyform.form.demo_configuration_other_form_data_handler');
        $otherForm = $otherFormDataHandler->getForm();
        $otherForm->handleRequest($request);

        if ($otherForm->isSubmitted() && $otherForm->isValid()) {
            $errors = $otherFormDataHandler->save($otherForm->getData());

            if (empty($errors)) {
                $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));
            } else {
                $this->flashErrors($errors);
            }
        }
        return $this->redirectToRoute('demo_configuration_multiple_forms');

    }
}
