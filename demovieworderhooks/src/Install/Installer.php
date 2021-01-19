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

namespace PrestaShop\Module\DemoViewOrderHooks\Install;

use Db;
use Module;

/**
 * Class responsible for modifications needed during installation/uninstallation of the module.
 */
class Installer
{
    /**
     * @var FixturesInstaller
     */
    private $fixturesInstaller;

    public function __construct(FixturesInstaller $fixturesInstaller)
    {
        $this->fixturesInstaller = $fixturesInstaller;
    }

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

        if (!$this->installDatabase()) {
            return false;
        }

        $this->fixturesInstaller->install();

        return true;
    }

    /**
     * Module's uninstallation entry point.
     *
     * @return bool
     */
    public function uninstall(): bool
    {
        return $this->uninstallDatabase();
    }

    /**
     * Install the database modifications required for this module.
     *
     * @return bool
     */
    private function installDatabase(): bool
    {
        $queries = [
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'order_signature` (
              `id_signature` int(11) NOT NULL AUTO_INCREMENT,
              `id_order` int(11) NOT NULL,
              `filename` varchar(64) NOT NULL,
              PRIMARY KEY (`id_signature`),
              UNIQUE KEY (`id_order`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;',

            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'order_review` (
              `id_order_review` int(11) NOT NULL AUTO_INCREMENT,
              `id_order` int(11) NOT NULL,
              `score` int(11) NOT NULL,
              `comment` text DEFAULT NULL,
              PRIMARY KEY (`id_order_review`),
              UNIQUE KEY (`id_order`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;',

            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'package_location` (
              `id_package_location` int(11) NOT NULL AUTO_INCREMENT,
              `id_order` int(11) NOT NULL,
              `location` varchar(255) NOT NULL,
              `position` int(11) NOT NULL,
              `date` datetime DEFAULT NULL,
              PRIMARY KEY (`id_package_location`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;',
        ];

        return $this->executeQueries($queries);
    }

    /**
     * Uninstall database modifications.
     *
     * @return bool
     */
    private function uninstallDatabase(): bool
    {
        $queries = [
            'DROP TABLE IF EXISTS `'._DB_PREFIX_.'order_signature`',
            'DROP TABLE IF EXISTS `'._DB_PREFIX_.'order_review`',
            'DROP TABLE IF EXISTS `'._DB_PREFIX_.'package_location`',
        ];

        return $this->executeQueries($queries);
    }

    /**
     * Register hooks for the module.
     *
     * @param Module $module
     *
     * @return bool
     */
    private function registerHooks(Module $module): bool
    {
        // All hooks in the order view page.
        $hooks = [
            'displayAdminOrderTabContent',
            'displayAdminOrderTabLink',
            'displayAdminOrderMain',
            'displayAdminOrderSide',
            'displayAdminOrderSideBottom',
            'displayAdminOrder',
            'displayOrderPreview',
            'displayAdminOrderTop',
            'actionGetAdminOrderButtons',
        ];

        return (bool) $module->registerHook($hooks);
    }

    /**
     * A helper that executes multiple database queries.
     *
     * @param array $queries
     *
     * @return bool
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
