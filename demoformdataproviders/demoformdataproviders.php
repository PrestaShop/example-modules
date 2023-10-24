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

if (!defined('_PS_VERSION_')) {
    exit;
}

class DemoFormDataProviders extends Module
{
    public function __construct()
    {
        $this->name = 'demoformdataproviders';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '8.0.0', 'max' => _PS_VERSION_];

        parent::__construct();

        $this->displayName = $this->trans('DemoFormDataProviders', [], 'Modules.DemoFormDataProviders.Config');
        $this->description = $this->trans('DemoFormDataProviders module description', [], 'Modules.DemoFormDataProviders.Config');
    }

    /**
     * @return bool
     */
    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        $this->registerHook('actionProductFormDataProviderData') 
            && $this->registerHook('actionProductFormDataProviderDefaultData');
    }

    /**
     * @return bool
     */
    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }
    }

    public function hookActionProductFormDataProviderDefaultData(array $params): void
    {
        // set a prefix as default value for field "details > references > mpn"
        $newMpnPrefix = $this->getNewMpnPrefix();
        $params["data"]["details"]["references"]["mpn"] = $newMpnPrefix;
    }

    public function hookActionProductFormDataProviderData(array $params): void
    {
        // add a prefix for field "details > references > mpn" if not prefixed
        $actualMpn = $params["data"]["details"]["references"]["mpn"];

        $newMpnPrefix = $this->getNewMpnPrefix();
        if(substr($actualMpn, 0, strlen($newMpnPrefix)) !== $newMpnPrefix){
            $params["data"]["details"]["references"]["mpn"] = $newMpnPrefix . $actualMpn;
        }
    }

    public function getNewMpnPrefix(): string
    {
        return "NEWMPNPREFIX_";
    }
        
}
