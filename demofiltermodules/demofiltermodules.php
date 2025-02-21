<?php
/**
 * PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

declare(strict_types=1);

if (!defined('_PS_VERSION_')) {
    exit;
}

class DemoFilterModules extends Module
{
    public function __construct()
    {
        $this->name = 'demofiltermodules';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '8.2.1', 'max' => '9.99.99'];

        parent::__construct();

        $this->displayName = $this->l('Demo filter modules');
        $this->description = $this->l('Demonstration of filtering modules in the front office');
    }
}
