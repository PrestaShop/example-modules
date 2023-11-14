<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

declare(strict_types=1);

if (!defined('_PS_VERSION_')) {
    exit;
}

class DemoProductExtraContent extends Module
{
    public function __construct()
    {
        $this->name = 'demoproductextracontent';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '1.7.0', 'max' => _PS_VERSION_];

        parent::__construct();

        $this->displayName = $this->trans('DemoProductExtraContent', [], 'Modules.DemoProductExtraContent.Config');
        $this->description = $this->trans('DemoProductExtraContent module description', [], 'Modules.DemoProductExtraContent.Config');
    }

    /**
     * @return bool
     */
    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        return $this->registerHook('displayProductExtraContent');
    }

    /**
     * Add extra content to the product page, by default, it renders as tabs on the product page.
     */
    public function hookDisplayProductExtraContent($params) {
        return [
            (new PrestaShop\PrestaShop\Core\Product\ProductExtraContent())
                ->setTitle('First custom tab')
                ->setContent('Content of the first custom tab'),
            (new PrestaShop\PrestaShop\Core\Product\ProductExtraContent())
                ->setTitle('Second custom tab')
                ->setContent('Content of the second custom tab')
        ];
    }
        
}
