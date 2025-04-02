<?php

declare(strict_types=1);

use PrestaShop\PrestaShop\Adapter\SymfonyContainer;

class DemoSymfonyFormSimple extends Module
{
    public function __construct()
    {
        $this->name = 'demosymfonyformsimple';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->trans('Demo of the Symfony-based configuration form', [], 'Modules.DemoSymfonyFormSimple.Admin');
        $this->description = $this->trans(
            'Module demonstrates a simple module\'s configuration page made with Symfony.',
            [],
            'Modules.DemoSymfonyFormSimple.Admin'
        );

        $this->ps_versions_compliancy = ['min' => '9.0.0', 'max' => '9.99.99'];
    }

    public function getContent()
    {
        $route = $this->get('router')->generate('demo_configuration_form_simple');
        Tools::redirectAdmin($route);
    }
}
