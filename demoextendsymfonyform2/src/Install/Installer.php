<?php
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

declare(strict_types=1);

namespace PrestaShop\Module\DemoExtendSymfonyForm\Install;

use Db;
use Module;
use PrestaShop\Module\DemoExtendSymfonyForm\Sql\SqlQueries;

/**
 * Class Installer
 * @package PrestaShop\Module\DemoExtendSymfonyForm\Install
 */
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

        if (!$this->installDatabase()) {
            return false;
        }

        return true;
    }

    /**
     * Module's uninstallation entry point.
     */
    public function uninstall(): bool
    {
        return $this->uninstallDatabase();
    }

    /**
     * Install the database modifications required for this module.
     */
    private function installDatabase(): bool
    {
        return $this->executeQueries(SqlQueries::installQueries());
    }

    /**
     * Uninstall database modifications.
     */
    private function uninstallDatabase(): bool
    {
        return $this->executeQueries(SqlQueries::uninstallQueries());
    }

    /**
     * Register hooks for the module.
     */
    private function registerHooks(Module $module): bool
    {
        $hooks = [
            'actionSupplierFormBuilderModifier',
            'actionAfterCreateSupplierFormHandler',
            'actionAfterUpdateSupplierFormHandler',
        ];

        return (bool) $module->registerHook($hooks);
    }

    /**
     * A helper that executes multiple database queries.
     */
    private function executeQueries(array $queries): bool
    {
        foreach ($queries as $query) {
            if (!Db::getInstance()->execute($query)) {
                return false;
            }
        }

        return true;
    }
}
