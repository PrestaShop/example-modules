<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */
declare(strict_types=1);

namespace PrestaShop\Module\DemoJsRouting\Install;

use Module;

class Installer
{
    /**
     * Module's installation entry point.
     *
     * @param Module $module
     *
     * @return bool
     */
    public function install(Module $module): bool
    {
        if (!$this->registerHooks($module)) {
            return false;
        }

        return true;
    }

    /**
     * Register hooks for the module.
     *
     * @see https://devdocs.prestashop.com/1.7/modules/concepts/hooks/
     *
     * @param Module $module
     *
     * @return bool
     */
    private function registerHooks(Module $module): bool
    {
        $hooks = [];

        return (bool) $module->registerHook($hooks);
    }
}
