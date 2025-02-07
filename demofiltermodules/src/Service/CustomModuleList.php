<?php

namespace Module\DemoFilterModules\Service;

use PrestaShop\PrestaShop\Core\Hook\HookModuleFilterInterface;

class CustomModuleList implements HookModuleFilterInterface
{
    public function filterHookModuleExecList(array $modules, string $hookName): array
    {
        // Skip the check in the back office
        if (defined('_PS_ADMIN_DIR_')) {
            return $modules;
        }

        if ($hookName == 'displayFooter') {
            foreach ($modules as $index => $module) {
                if ($module['module'] == 'ps_linklist') {
                    unset($modules[$index]);
                }

                if (\Tools::getValue('controller') == 'contact') {
                    if ($module['module'] == 'ps_contactinfo' || $module['module'] == 'ps_customeraccountlinks') {
                        unset($modules[$index]);
                    }
                }
            }
        }

        return $modules;
    }
}
