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

use PrestaShop\PrestaShop\Adapter\SymfonyContainer;

class DemoSymfonyForm extends Module
{
    public function __construct()
    {
        $this->name = 'demosymfonyform';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->trans('Demo symfony form configuration', array(), 'Modules.DemoSymfonyForm.Admin');
        $this->description = $this->trans(
            'Module created for the purpose of showing existing form types within PrestaShop',
            array(),
            'Modules.DemoSymfonyForm.Admin'
        );

        $this->ps_versions_compliancy = array('min' => '1.7.8.0', 'max' => _PS_VERSION_);
    }

    public function getContent()
    {
        $route = SymfonyContainer::getInstance()->get('router')->generate('demo_configuration_form');
        Tools::redirectAdmin($route);
    }
}
