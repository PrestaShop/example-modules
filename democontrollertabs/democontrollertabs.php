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

class democontrollertabs extends Module
{
    public function __construct()
    {
        $this->name = 'democontrollertabs';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '1.7.7', 'max' => _PS_VERSION_];

        parent::__construct();

        $this->displayName = $this->l('Controller Tabs');
        $this->description = $this->l('Demonstration of Symfony Controllers, Tabs and Permissions');

        // See https://devdocs.prestashop.com/1.7/modules/concepts/controllers/admin-controllers/tabs/
        $tabNames = [];
        foreach (Language::getLanguages(true) as $lang) {
            $tabNames[$lang['locale']] = $this->trans('Demo Controller Tabs', array(), 'Modules.Democontrollertabs.Admin', $lang['locale']);
        }
        $this->tabs = [
            [
                'route_name' => 'ps_controller_tabs_configure_index',
                'class_name' => 'AdminDemoControllerTabsConfigure',
                'visible' => true,
                'name' => $tabNames,
                'icon' => 'school',
                'parent_class_name' => 'IMPROVE',
            ],
        ];
    }

    public function getContent()
    {
        // This uses the matching with the route ps_controller_tabs_configure_index via the _legacy_link property
        // See https://devdocs.prestashop.com/1.7/development/architecture/migration-guide/controller-routing
        Tools::redirectAdmin(
            $this->context->link->getAdminLink('AdminDemoControllerTabsConfigure')
        );
    }
}
