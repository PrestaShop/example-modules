<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */

declare(strict_types=1);

if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(__DIR__.'/vendor/autoload.php')) {
    require_once __DIR__.'/vendor/autoload.php';
}

use PrestaShop\PrestaShop\Adapter\SymfonyContainer;
use PrestaShop\Module\DemoMultistoreForm\Database\ContentBlockInstaller;

class DemoMultistoreForm extends Module
{
    public $multistoreCompatibility = self::MULTISTORE_COMPATIBILITY_YES;

    public function __construct()
    {
        $this->name = 'demomultistoreform';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = 'Demo multistore configuration form';
        $this->description = 'Module created for the purpose of showing an example of multistore configuration form';
        $this->ps_versions_compliancy = array('min' => '1.7.8.0', 'max' => '8.99.99');
    }

    /**
     * @return bool
     */
    public function install(): bool
    {
        return $this->getInstaller()->createTables()
            && parent::install()
            && $this->registerHook('displayFooterBefore');
    }

    /**
     * @return bool
     */
    public function uninstall(): bool
    {
        return $this->getInstaller()->dropTables() && parent::uninstall();
    }

    public function getContent(): void
    {
        $route = SymfonyContainer::getInstance()->get('router')->generate('demo_multistore');
        Tools::redirectAdmin($route);
    }

    /**
     * Gets the ContentBlockInstaller from service container if possible (at uninstall),
     * otherwise instantiate class directly (at install)
     *
     * @return ContentBlockInstaller
     */
    private function getInstaller(): ContentBlockInstaller
    {
        try {
            $installer = $this->get('prestashop.module.demo_multistore.content_block_installer');
        } catch (Exception $e) {
            $installer = null;
        }

        if (empty($installer)) {
            $installer = new ContentBlockInstaller(
                $this->get('doctrine.dbal.default_connection'),
                $this->getContainer()->getParameter('database_prefix')
            );
        }
        return $installer;
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public function hookdisplayFooterBefore($params): string
    {
        $this->context->smarty->assign([
            'contentBlockList' => $this->getContentBlocksForFrontOffice(),
            'color' => Configuration::get('PS_DEMO_MULTISTORE_COLOR'),
            'italic' => Configuration::get('PS_DEMO_MULTISTORE_ITALIC'),
            'bold' => Configuration::get('PS_DEMO_MULTISTORE_BOLD'),
        ]);

        return $this->display(__FILE__, 'views/templates/hook/displayBlockContent.tpl');
    }

    /**
     * @return array
     */
    private function getContentBlocksForFrontOffice(): array
    {
        $dbPrefix = $this->getContainer()->getParameter('database_prefix');
        $connection = $this->get('doctrine.dbal.default_connection');
        $qb = $connection->createQueryBuilder()
            ->from($dbPrefix . 'content_block', 'cb')
            ->select('cb.id_content_block, cb.title, cb.description, cb.enable')
            ->join('cb', $dbPrefix . 'content_block_shop', 'cbs', 'cbs.id_content_block = cb.id_content_block')
            ->where('cbs.id_shop = :shopId AND cb.enable = true')
            ->setParameter('shopId', (int) $this->context->shop->id);

        return $qb->execute()->fetchAll();
    }
}
