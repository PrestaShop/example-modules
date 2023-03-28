<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class DemoModuleRoutesShowModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        $this->context->smarty->assign(
            array(
              'id' => Tools::getValue('id'),
              'slug' => Tools::getValue('slug')
            ));
        
        $this->setTemplate('module:demomoduleroutes/views/templates/front/show.tpl');
    }
}