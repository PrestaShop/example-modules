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
use PrestaShop\PrestaShop\Adapter\LegacyContext;
use PrestaShop\PrestaShop\Core\CommandBus\CommandBusInterface;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;
use PrestaShop\PrestaShop\Core\Domain\MailTemplate\Command\GenerateThemeMailTemplatesCommand;
use PrestaShop\PrestaShop\Core\Exception\CoreException;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DarkThemeController extends FrameworkBundleAdminController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        /** @var DarkThemeSettings $darkThemeSettings */
        $darkThemeSettings = $this->get('prestashop.module.example_module_mailtheme.dark_theme_settings');

        $form = $this->createForm(DarkThemeSettingsType::class, $darkThemeSettings->getSettings());

        return $this->render('@Modules/example_module_mailtheme/views/templates/admin/index.html.twig', [
            'enableSidebar' => true,
            'darkThemeForm' => $form->createView(),
            'help_link' => $this->generateSidebarLink($request->attributes->get('_legacy_controller')),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function saveSettingsAction(Request $request)
    {
        /** @var DarkThemeSettings $darkThemeSettings */
        $darkThemeSettings = $this->get('prestashop.module.example_module_mailtheme.dark_theme_settings');

        $form = $this->createForm(DarkThemeSettingsType::class, $darkThemeSettings->getSettings());
        $form->handleRequest($request);
        if ($form->isValid()) {
            $darkThemeSettings->saveSettings($form->getData());
            $this->addFlash('success', $this->trans('Your settings for Dark Theme are saved.', 'Modules.ExampleModuleMailtheme'));

            return $this->redirectToRoute('admin_example_module_mailtheme');
        }

        return $this->render('@Modules/example_module_mailtheme/views/templates/admin/index.html.twig', [
            'enableSidebar' => true,
            'darkThemeForm' => $form->createView(),
            'help_link' => $this->generateSidebarLink($request->attributes->get('_legacy_controller')),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function resetDefaultSettingsAction(Request $request)
    {
        /** @var DarkThemeSettings $darkThemeSettings */
        $darkThemeSettings = $this->get('prestashop.module.example_module_mailtheme.dark_theme_settings');
        $darkThemeSettings->initSettings();

        $this->addFlash('success', $this->trans('The default settings for Dark Theme are reset.', 'Modules.ExampleModuleMailtheme'));

        return $this->redirectToRoute('admin_example_module_mailtheme');
    }

    /**
     * @return RedirectResponse
     */
    public function generateAction()
    {
        /** @var ConfigurationInterface $configuration */
        $configuration = $this->get('prestashop.adapter.legacy.configuration');
        $configuration->set('PS_MAIL_THEME', 'dark_modern');

        /** @var LegacyContext $legacyContext */
        $legacyContext = $this->get('prestashop.adapter.legacy.context');
        $languages = $legacyContext->getLanguages();

        /** @var CommandBusInterface $commandBus */
        $commandBus = $this->get('prestashop.core.command_bus');

        try {
            /** @var array $language */
            foreach ($languages as $language) {
                /** @var GenerateThemeMailTemplatesCommand $generateCommand */
                $generateCommand = new GenerateThemeMailTemplatesCommand(
                    'dark_modern',
                    $language['locale'],
                    true
                );

                $commandBus->handle($generateCommand);
            }
            $this->addFlash('success', $this->trans('The Dark Theme is generated and set as default theme.', 'Modules.ExampleModuleMailtheme'));
        } catch (CoreException $e) {
            $this->addFlash('error', $this->trans(
                'The Dark Theme cannot be generated: %error%.',
                'Modules.ExampleModuleMailtheme',
                ['%error%' => $e->getMessage()]
            ));
        }

        return $this->redirectToRoute('admin_example_module_mailtheme');
    }
}
