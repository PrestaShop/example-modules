<?php

declare(strict_types=1);

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__.'/vendor/autoload.php';

class DemoConsoleCommand extends Module
{
    public function __construct()
    {
        $this->name = 'democonsolecommand';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '1.7.7.0', 'max' => '8.99.99'];

        parent::__construct();

        $this->displayName = $this->l('Demo - implement Symfony command for console');
        $this->description = $this->l('Shows example how to implement Symfony command for console');
    }
}
