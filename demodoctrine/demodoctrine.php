<?php
/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */
declare(strict_types=1);

use Module\DemoDoctrine\Database\QuoteInstaller;

if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(__DIR__.'/vendor/autoload.php')) {
    require_once __DIR__.'/vendor/autoload.php';
}

class DemoDoctrine extends Module
{
    public function __construct()
    {
        $this->name = 'demodoctrine';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '1.7.7', 'max' => '8.99.99'];

        parent::__construct();

        $this->displayName = $this->l('Demo doctrine');
        $this->description = $this->l('Demonstration of Doctrine entities in PrestaShop');
    }

    public function install()
    {
        return $this->installTables() && parent::install() && $this->registerHook('displayHome');
    }

    public function uninstall()
    {
        return $this->removeTables() && parent::uninstall();
    }

    public function getContent()
    {
        Tools::redirectAdmin(
            $this->context->link->getAdminLink('AdminDemodoctrineQuote')
        );
    }

    public function hookDisplayHome()
    {
        $repository = $this->get('prestashop.module.demodoctrine.repository.quote_repository');
        $langId = $this->context->language->id;
        $quotes = $repository->getRandom($langId, 3);

        $this->smarty->assign(['quotes' => $quotes]);

        return $this->fetch('module:demodoctrine/views/templates/front/home.tpl');
    }

    /**
     * @return bool
     */
    private function installTables()
    {
        /** @var QuoteInstaller $installer */
        $installer = $this->getInstaller();
        $errors = $installer->createTables();

        return empty($errors);
    }

    /**
     * @return bool
     */
    private function removeTables()
    {
        /** @var QuoteInstaller $installer */
        $installer = $this->getInstaller();
        $errors = $installer->dropTables();

        return empty($errors);
    }

    /**
     * @return QuoteInstaller
     */
    private function getInstaller()
    {
        try {
            $installer = $this->get('prestashop.module.demodoctrine.quotes.install');
        } catch (Exception $e) {
            // Catch exception in case container is not available, or service is not available
            $installer = null;
        }

        // During install process the modules's service is not available yet so we build it manually
        if (!$installer) {
            $installer = new QuoteInstaller(
                $this->get('doctrine.dbal.default_connection'),
                $this->getContainer()->getParameter('database_prefix')
            );
        }

        return $installer;
    }
}
