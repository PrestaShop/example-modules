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
        $this->ps_versions_compliancy = ['min' => '9.0.0', 'max' => '9.99.99'];

        parent::__construct();

        $this->displayName = $this->trans('Demo Javascript routing', [], 'Modules.Demojsrouting.Admin');
        $this->description = $this->trans('Example module of Javascript component Router usage in BO', [], 'Modules.Demojsrouting.Admin');
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
