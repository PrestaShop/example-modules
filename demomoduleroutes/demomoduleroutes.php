<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

declare(strict_types=1);

class DemoModuleRoutes extends Module
{
    public function __construct()
    {
        $this->name = 'demomoduleroutes';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '8.0.0', 'max' => _PS_VERSION_];

        parent::__construct();

        $this->displayName = $this->trans('DemoModuleRoutes', [], 'Modules.DemoModuleRoutes.Config');
        $this->description = $this->trans('DemoModuleRoutes module description', [], 'Modules.DemoModuleRoutes.Config');
    }

    /**
     * @return bool
     */
    public function install()
    {
        return parent::install() && $this->registerHook('moduleRoutes');
    }


    public function hookModuleRoutes()
    {
        return [
          'module-demomoduleroutes-list' => [
            'rule' => 'demomoduleroutes/list',
            'keywords' => [],
            'controller' => 'list',
            'params' => [
                'fc' => 'module',
                'module' => 'demomoduleroutes'
            ]
          ],
          'module-demomoduleroutes-show' => [
            'rule' => 'demomoduleroutes/show/{id}/{slug}',
            'keywords' => [
              'id' => [
                'regexp' => '[0-9]*',
                'param' => 'id'
              ],
              'slug' => [
                'regexp' => '.*',
                'param' => 'slug'
              ]
            ],
            'controller' => 'show',
            'params' => [
                'fc' => 'module',
                'module' => 'demomoduleroutes'
            ]
          ]
        ];
    }
    
}