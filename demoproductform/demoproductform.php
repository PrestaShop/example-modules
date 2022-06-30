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

use PrestaShop\Module\DemoProductForm\Entity\CustomProduct;
use PrestaShop\Module\DemoProductForm\Form\Modifier\ProductFormModifier;
use PrestaShop\Module\DemoProductForm\Install\Installer;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId;
use Symfony\Component\Templating\EngineInterface;
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
        $this->ps_versions_compliancy = ['min' => '1.7.8.0', 'max' => _PS_VERSION_];

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
     * @return bool
     */
    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        $installer = new Installer();

        return $installer->uninstall($this);
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
        $formData = $params['data'];

        $productId = isset($params['id']) ? new ProductId((int) $params['id']) : null;

        $productFormModifier->modify($productId, $params['form_builder'], $formData);
    }

    public function hookDisplayAdminProductsExtra(array $params): string
    {
        $productId = $params['id_product'];
        $customProduct = new CustomProduct($productId);

        /** @var EngineInterface $twig */
        $twig = $this->get('twig');

        return $twig->render('@Modules/demoproductform/views/templates/admin/extra_module.html.twig', [
            'customProduct' => $customProduct,
        ]);
    }

    public function hookActionAfterUpdateProductFormHandler(array $params): void
    {
        $productId = $params['id'];

        // We use the same ID for CustomProduct as the product ID this way the relation is direct
        $customProduct = new CustomProduct($productId);
        $formData = $params['form_data'];
        if (isset($formData['description']['demo_module_custom_field'])) {
            $customProduct->custom_field = $formData['description']['demo_module_custom_field'];
        }

        if (isset($formData['custom_tab']['custom_price'])) {
            $customProduct->custom_price = $formData['custom_tab']['custom_price'];
        }

        if (empty($customProduct->id)) {
            // If custom is not found it has not been created yet, so we force its ID to match the product ID
            $customProduct->id = $productId;
            $customProduct->force_id = true;
            $customProduct->add();
        } else {
            $customProduct->update();
        }
    }
}
