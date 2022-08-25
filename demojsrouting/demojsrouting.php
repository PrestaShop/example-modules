<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */

declare(strict_types=1);

use PrestaShop\Module\DemoJsRouting\Install\Installer;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

class DemoJsRouting extends Module
{
    public $tabs = [
        [
            'class_name' => 'DemoPageController',
            'visible' => true,
            'name' => 'Demo page',
            'parent_class_name' => 'CONFIGURE',
        ],
    ];

    public function __construct()
    {
        $this->name = 'demojsrouting';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '1.7.7.0', 'max' => '8.99.99'];

        parent::__construct();

        $this->displayName = $this->l('Demo Javascript routing');
        $this->description = $this->l('Example module of Javascript component Router usage in BO');
    }

    /**
     * @return bool
     */
    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        $installer = new Installer();

        return $installer->install($this);
    }

    public function getContent()
    {
        $moduleAdminLink = Context::getContext()->link->getAdminLink('DemoPageController', true);
        Tools::redirectAdmin($moduleAdminLink);
    }
}
