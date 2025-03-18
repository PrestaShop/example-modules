<?php
/**
 * 2007-2019 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
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
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

namespace PrestaShop\Module\ExampleModuleMailtheme\Controller\Admin;

use PrestaShop\Module\ExampleModuleMailtheme\DarkThemeSettings;
use PrestaShop\Module\ExampleModuleMailtheme\Form\DarkThemeSettingsType;
use PrestaShop\PrestaShop\Core\Domain\MailTemplate\Command\GenerateThemeMailTemplatesCommand;
use PrestaShop\PrestaShop\Core\Exception\CoreException;
use PrestaShopBundle\Controller\Admin\PrestaShopAdminController;
use PrestaShopBundle\Entity\Repository\LangRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DarkThemeController extends PrestaShopAdminController
{
    public function indexAction(
        Request $request,
        DarkThemeSettings $darkThemeSettings,
    ): Response {
        $form = $this->createForm(DarkThemeSettingsType::class, $darkThemeSettings->getSettings());

        return $this->render('@Modules/example_module_mailtheme/views/templates/admin/index.html.twig', [
            'enableSidebar' => true,
            'darkThemeForm' => $form->createView(),
            'help_link' => $this->generateSidebarLink($request->attributes->get('_legacy_controller')),
        ]);
    }

    public function saveSettingsAction(
        Request $request,
        DarkThemeSettings $darkThemeSettings,
    ): Response {
        $form = $this->createForm(DarkThemeSettingsType::class, $darkThemeSettings->getSettings());
        $form->handleRequest($request);
        if ($form->isValid()) {
            $darkThemeSettings->saveSettings($form->getData());
            $this->addFlash('success', $this->trans('Your settings for Dark Theme are saved.', [], 'Modules.ExampleModuleMailtheme'));

            return $this->redirectToRoute('admin_example_module_mailtheme');
        }

        return $this->render('@Modules/example_module_mailtheme/views/templates/admin/index.html.twig', [
            'enableSidebar' => true,
            'darkThemeForm' => $form->createView(),
            'help_link' => $this->generateSidebarLink($request->attributes->get('_legacy_controller')),
        ]);
    }

    public function resetDefaultSettingsAction(
        DarkThemeSettings $darkThemeSettings,
    ): RedirectResponse {
        $darkThemeSettings->initSettings();

        $this->addFlash('success', $this->trans('The default settings for Dark Theme are reset.', [], 'Modules.ExampleModuleMailtheme'));

        return $this->redirectToRoute('admin_example_module_mailtheme');
    }

    public function generateAction(
        LangRepository $langRepository,
    ): RedirectResponse {
        $this->getConfiguration()->set('PS_MAIL_THEME', 'dark_modern');

        try {
            foreach ($langRepository->getMapping() as $language) {
                $generateCommand = new GenerateThemeMailTemplatesCommand(
                    'dark_modern',
                    $language['locale'],
                    true
                );

                $this->dispatchCommand($generateCommand);
            }
            $this->addFlash('success', $this->trans('The Dark Theme is generated and set as default theme.', [], 'Modules.ExampleModuleMailtheme'));
        } catch (CoreException $e) {
            $this->addFlash('error', $this->trans(
                'The Dark Theme cannot be generated: %error%.',
                ['%error%' => $e->getMessage()],
                'Modules.ExampleModuleMailtheme',
            ));
        }

        return $this->redirectToRoute('admin_example_module_mailtheme');
    }
}
