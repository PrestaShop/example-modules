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

namespace PrestaShop\Module\DemoOverrideObjectModel\Install;

use Db;
use Exception;
use Module;
use Tools;

class Installer
{
    /**
     * Module's installation entry point.
     */
    public function install(Module $module): bool
    {
        if (!$this->registerHooks($module)) {
            return false;
        }

        $this->installDb($module);

        return true;
    }

    public function uninstall(Module $module): bool
    {
        $this->uninstallDb($module);

        return true;
    }

    /**
     * Register hooks for the module.
     *
     * @see https://devdocs.prestashop-project.org/9/modules/concepts/hooks/
     */
    private function registerHooks(Module $module): bool
    {
        $hooks = [];

        return (bool) $module->registerHook($hooks);
    }

    private function installDb(Module $module): void
    {
        $this->executeSqlFromFile($module->getLocalPath() . 'src/Install/install.sql');
    }

    private function uninstallDb(Module $module): void
    {
        $this->executeSqlFromFile($module->getLocalPath() . 'src/Install/uninstall.sql');

    }

    private function executeSqlFromFile(string $path): void
    {
        $database = Db::getInstance();
        $sqlStatements = Tools::file_get_contents($path);
        $sqlStatements = str_replace(['_DB_PREFIX_', '_MYSQL_ENGINE_'], [_DB_PREFIX_, _MYSQL_ENGINE_], $sqlStatements);

        try {
            $database->execute($sqlStatements);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
