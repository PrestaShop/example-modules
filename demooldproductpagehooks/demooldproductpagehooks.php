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

use Symfony\Component\Form\Extension\Core\Type\TextType;

class DemoOldProductPageHooks extends Module
{
    public function __construct()
    {
        $this->name = 'demooldproductpagehooks';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '1.7.8', 'max' => _PS_VERSION_];

        parent::__construct();

        $this->displayName = $this->trans('DemoOldProductPageHooks', [], 'Modules.DemoOldProductPageHooks.Config');
        $this->description = $this->trans('DemoOldProductPageHooks module description', [], 'Modules.DemoOldProductPageHooks.Config');
    }

    /**
     * @return bool
     */
    public function install()
    {
        return parent::install()
          && $this->registerHook(
              [
                  'displayAdminProductsExtra',
                  'displayAdminProductsCombinationBottom',
                  'displayAdminProductsSeoStepBottom',
                  'displayAdminProductsShippingStepBottom',
                  'displayAdminProductsQuantitiesStepBottom',
                  'displayAdminProductsMainStepLeftColumnBottom',
                  'displayAdminProductsMainStepLeftColumnMiddle',
                  'displayAdminProductsMainStepRightColumnBottom',
                  'displayAdminProductsOptionsStepTop',
                  'displayAdminProductsOptionsStepBottom',
                  'displayAdminProductsPriceStepBottom',
              ]
          );
    }

    public function buildForm($fieldName)
    {
        $formFactory = $this->get('form.factory');
        $twig = $this->get('twig');

        $form = $formFactory
            ->createNamedBuilder($fieldName, TextType::class, $fieldName)
            ->getForm();

        $template = '@Modules/demooldproductpagehooks/views/templates/field.html.twig';

        return $twig->render($template, [
            'field_name' => $fieldName,
            'form' => $form->createView(),
        ]);
    }

    public function hookDisplayAdminProductsSeoStepBottom($params)
    {
        return $this->buildForm('displayAdminProductsSeoStepBottom');
    }

    public function hookDisplayAdminProductsExtra($params)
    {
        return $this->buildForm('displayAdminProductsExtra');
    }

    public function hookDisplayAdminProductsCombinationBottom($params)
    {
        return $this->buildForm('displayAdminProductsCombinationBottom');
    }

    public function hookDisplayAdminProductsShippingStepBottom($params)
    {
        return $this->buildForm('displayAdminProductsShippingStepBottom');
    }

    public function hookDisplayAdminProductsQuantitiesStepBottom($params)
    {
        return $this->buildForm('displayAdminProductsQuantitiesStepBottom');
    }

    public function hookDisplayAdminProductsMainStepLeftColumnBottom($params)
    {
        return $this->buildForm('displayAdminProductsMainStepLeftColumnBottom');
    }

    public function hookDisplayAdminProductsMainStepLeftColumnMiddle($params)
    {
        return $this->buildForm('displayAdminProductsMainStepLeftColumnMiddle');
    }

    public function hookDisplayAdminProductsMainStepRightColumnBottom($params)
    {
        return $this->buildForm('displayAdminProductsMainStepRightColumnBottom');
    }

    public function hookDisplayAdminProductsOptionsStepTop($params)
    {
        return $this->buildForm('displayAdminProductsOptionsStepTop');
    }

    public function hookDisplayAdminProductsOptionsStepBottom($params)
    {
        return $this->buildForm('displayAdminProductsOptionsStepBottom');
    }

    public function hookDisplayAdminProductsPriceStepBottom($params)
    {
        return $this->buildForm('displayAdminProductsPriceStepBottom');
    }
}
