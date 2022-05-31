<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0  Academic Free License (AFL 3.0)
 */
declare(strict_types=1);

if (!defined('_PS_VERSION_')) {
    exit;
}

// Needed for install process
require_once __DIR__ . '/vendor/autoload.php';

use PrestaShop\Module\DemoControllerTabs\Controller\Admin\ConfigureController;
use PrestaShop\Module\DemoControllerTabs\Controller\Admin\ManualTabController;

class democontrollertabs extends Module
{
    public function __construct()
    {
        $this->name = 'democontrollertabs';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '1.7.7', 'max' => '8.99.99'];

        parent::__construct();

        $this->displayName = $this->l('Demo Controller Tabs');
        $this->description = $this->l('Demonstration of Symfony Controllers, Tabs and Permissions');

        // See https://devdocs.prestashop.com/1.7/modules/concepts/controllers/admin-controllers/tabs/
        $tabNames = [];
        foreach (Language::getLanguages(true) as $lang) {
            $tabNames[$lang['locale']] = $this->trans('Demo Controller Tabs', [], 'Modules.Democontrollertabs.Admin', $lang['locale']);
        }
        $this->tabs = [
            [
                'route_name' => 'ps_controller_tabs_configure',
                'class_name' => ConfigureController::TAB_CLASS_NAME,
                'visible' => true,
                'name' => $tabNames,
                'icon' => 'school',
                'parent_class_name' => 'IMPROVE',
            ],
        ];
    }

    public function getContent()
    {
        // This uses the matching with the route ps_controller_tabs_configure via the _legacy_link property
        // See https://devdocs.prestashop.com/1.7/development/architecture/migration-guide/controller-routing
        Tools::redirectAdmin(
            $this->context->link->getAdminLink(ConfigureController::TAB_CLASS_NAME)
        );
    }

    public function install()
    {
        return parent::install() && $this->manuallyInstallTab();
    }

    /**
     * @return bool
     */
    private function manuallyInstallTab(): bool
    {
        // Add Tab for ManualTabController
        $controllerClassName = ManualTabController::TAB_CLASS_NAME;
        $tabId = (int) Tab::getIdFromClassName($controllerClassName);
        if (!$tabId) {
            $tabId = null;
        }

        $tab = new Tab($tabId);
        $tab->active = 1;
        $tab->class_name = $controllerClassName;
        $tab->route_name = 'ps_controller_tabs_manual_tab';
        $tab->name = [];
        foreach (Language::getLanguages() as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans('Manual Tab controller', [], 'Modules.Democontrollertabs.Admin', $lang['locale']);
        }
        $tab->icon = 'build';
        $tab->id_parent = (int) Tab::getIdFromClassName('IMPROVE');
        $tab->module = $this->name;

        return (bool) $tab->save();
    }
}
