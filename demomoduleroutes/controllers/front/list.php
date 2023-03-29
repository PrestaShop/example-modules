<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class DemoModuleRoutesListModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        $this->setTemplate('module:demomoduleroutes/views/templates/front/list.tpl');
    }
}