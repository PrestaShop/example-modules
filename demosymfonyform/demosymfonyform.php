<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */

declare(strict_types=1);

use PrestaShop\PrestaShop\Adapter\SymfonyContainer;

class DemoSymfonyForm extends Module
{
    public function __construct()
    {
        $this->name = 'demosymfonyform';
        $this->author = 'PrestaShop';
        $this->version = '1.1.0';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->trans('Demo symfony form configuration', [], 'Modules.DemoSymfonyForm.Admin');
        $this->description = $this->trans(
            'Module created for the purpose of showing existing form types within PrestaShop',
            [],
            'Modules.DemoSymfonyForm.Admin'
        );

        $this->ps_versions_compliancy = ['min' => '8.0.0', 'max' => '9.99.99'];
    }

    public function getTabs()
    {
        return [
            [
                'class_name' => 'AdminDemoSymfonyForm',
                'visible' => true,
                'name' => 'Admin symfony form single',
                'parent_class_name' => 'CONFIGURE',
            ],
            [
                'class_name' => 'AdminDemoSymfonyFormMultipleForms',
                'visible' => true,
                'name' => 'Admin symfony form multiple forms',
                'parent_class_name' => 'CONFIGURE',
            ],
        ];
    }

    public function getContent()
    {
        $route = SymfonyContainer::getInstance()->get('router')->generate('demo_configuration_form');
        Tools::redirectAdmin($route);
    }
}
