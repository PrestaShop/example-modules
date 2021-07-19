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
        $this->ps_versions_compliancy = ['min' => '1.7.7.0', 'max' => _PS_VERSION_];

        parent::__construct();

        $this->displayName = $this->l('Demo js routing');
        $this->description = $this->l('Example module of javascript router usage in BO');
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
}
