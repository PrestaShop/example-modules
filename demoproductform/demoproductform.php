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

use PrestaShop\Module\DemoProductForm\Form\Handler\ProductFormHandler;
use PrestaShop\Module\DemoProductForm\Form\Modifier\ProductFormModifier;
use PrestaShop\Module\DemoProductForm\Install\Installer;
use Symfony\Component\Translation\TranslatorInterface;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

class DemoProductForm extends Module
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct()
    {
        $this->name = 'demoproductform';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '1.7.7.0', 'max' => _PS_VERSION_];

        parent::__construct();

        $this->translator = $this->get('translator');
        $this->displayName = $this->translator->trans('DemoProductForm', [], 'Modules.Demoproductform.Config');
        $this->description = $this->translator->trans('DemoProductForm module description', [], 'Modules.Demoproductform.Config');
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
     * @see https://devdocs.prestashop.com/1.7/modules/creation/module-translation/new-system/#translating-your-module
     *
     * @return bool
     */
    public function isUsingNewTranslationSystem(): bool
    {
        return true;
    }

    /**
     * Modify product form builder
     *
     * @param array $params
     */
    public function hookActionProductFormBuilderModifier(array $params): void
    {
        /** @var ProductFormModifier $productFormModifier */
        $productFormModifier = $this->get(ProductFormModifier::class);
        $productFormModifier->modify($params['form_builder']);
    }

    /**
     * Handle custom fields update after product update has been performed
     * @todo: there is another way to do it with product (using CQRS and commands collector)
     *
     * @param array $params
     */
    public function hookActionAfterUpdateProductFormHandler(array $params): void
    {
        /** @var ProductFormHandler $productFormHandler */
        $productFormHandler = $this->get(ProductFormHandler::class);
        $productFormHandler->handleUpdate($params['form_data']);
    }
}
