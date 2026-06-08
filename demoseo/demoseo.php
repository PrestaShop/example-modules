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

if (!defined('_PS_VERSION_')) {
    exit;
}

class demoseo extends Module
{
    public function __construct()
    {
        // Basic information
        $this->name = 'demoseo';
        $this->tab = 'others';
        $this->version = '1.0.0';
        $this->author = 'Daniel Hlavacek';
        $this->bootstrap = true;
        $this->need_instance = 0;
        $this->ps_versions_compliancy = ['min' => '9.2.0'];
        $this->is_configurable = 0;

        parent::__construct();

        // Visible information
        $this->displayName = $this->trans('Demo SEO', [], 'Modules.Demoseo.Admin');
        $this->description = $this->trans('Showcases some options to alter structured data and SEO-related information in the front office.', [], 'Modules.Demoseo.Admin');
    }

    public function install()
    {
        return parent::install() && $this->registerHook('actionFrontControllerSetVariables');
    }

    public function hookActionFrontControllerSetVariables($params)
    {
        /*
         * Example 1
         * Add or change structured data of the product. You can easily add data from external modules
         * or change the current ones, to complement your possible modifications done in other hooks.
         */
        if ($this->context->controller->php_self === 'product') {
            if (isset($params['templateVars']['structured_data']['product'])) {
                // Adding aggregate rating to the product structured data, from any review module or custom source
                $params['templateVars']['structured_data']['product']['aggregateRating'] = [
                    '@type' => 'AggregateRating',
                    'ratingValue' => 4.8,
                    'reviewCount' => 27,
                    'bestRating' => 5,
                    'worstRating' => 1,
                ];
            }
            if (isset($params['templateVars']['structured_data']['product']['offers'])) {
                // Add return policy depending on your other shop logic
                $params['templateVars']['structured_data']['product']['offers']['hasMerchantReturnPolicy'] = [
                    '@type' => 'MerchantReturnPolicy',
                    'applicableCountry' => 'PL',
                    'returnPolicyCategory' => 'https://schema.org/MerchantReturnFiniteReturnWindow',
                    'merchantReturnDays' => 30,
                    'returnMethod' => 'https://schema.org/ReturnByMail',
                    'returnFees' => 'https://schema.org/FreeReturn',
                ];

                // Override availability
                $params['templateVars']['structured_data']['product']['offers']['availability'] = 'https://schema.org/LimitedAvailability';
            }
        }

        /*
         * Example 2
         * Add contact points to the organization structured data.
         */
        if (isset($params['templateVars']['structured_data']['organization'])) {
            $params['templateVars']['structured_data']['organization']['contactPoint'] = [
                [
                    '@type' => 'ContactPoint',
                    'telephone' => '+420123456789',
                    'contactType' => 'customer support',
                    'areaServed' => 'PL',
                    'availableLanguage' => [
                        'pl',
                        'cs',
                        'sk',
                    ],
                ],
            ];
        }

        /*
         * Example 3
         * You can also easily change meta title and descriptions of any pages, for example to alter the way automatic
         * meta is generated, append shop name to the end and so on.
         */
        if ($this->context->controller->getPageName() === 'category') {
            $categoryObject = new Category(Tools::getValue('id_category'), $this->context->language->id, $this->context->shop->id);
            $params['templateVars']['page']['meta']['title'] = $categoryObject->name . ' for the best prices | ' . $this->context->shop->name;
        }
    }
}
