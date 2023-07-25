<?php

declare(strict_types=1);

use PrestaShop\PrestaShop\Adapter\SymfonyContainer;

class DemoSymfonyFormSimple extends Module
{
    public function __construct()
    {
        $this->name = 'demosymfonyformsimple';
        $this->author = 'PrestaShop';
        $this->version = '1.1.0';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->trans('Demo symfony form configuration simple', [], 'Modules.DemoSymfonyFormSimple.Admin');
        $this->description = $this->trans(
            'Module created for the purpose of showing existing form types within PrestaShop',
            [],
            'Modules.DemoSymfonyFormSimple.Admin'
        );

        $this->ps_versions_compliancy = ['min' => '8.0.0', 'max' => '8.99.99'];
    }

    public function getContent()
    {
        $route = SymfonyContainer::getInstance()->get('router')->generate('demo_configuration_form_simple');
        Tools::redirectAdmin($route);
    }
}
