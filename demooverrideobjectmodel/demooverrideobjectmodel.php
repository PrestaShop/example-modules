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

use PrestaShop\Module\DemoOverrideObjectModel\Install\Installer;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__.'/vendor/autoload.php';

class DemoOverrideObjectModel extends Module
{
    public function __construct()
    {
        $this->name = 'demooverrideobjectmodel';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '9.0.0', 'max' => '9.99.99'];

        parent::__construct();

        $this->displayName = $this->trans('Demo override object model', [], 'Modules.DemmoOverrideObjectModel.Admin');
        $this->description = $this->trans('Shows example how to override object model and add custom field to database table', [], 'Modules.DemmoOverrideObjectModel.Admin');
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

    /**
     * @return bool
     */
    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        $installer = new Installer();

        return $installer->uninstall($this);
    }
}
