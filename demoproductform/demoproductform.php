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

use PrestaShop\Module\DemoProductForm\Entity\CustomProduct;
use PrestaShop\Module\DemoProductForm\Form\Modifier\ProductFormModifier;
use PrestaShop\Module\DemoProductForm\Install\Installer;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId;
use Symfony\Component\Templating\EngineInterface;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

class DemoProductForm extends Module
{
    public function __construct()
    {
        $this->name = 'demoproductform';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '8.0.0', 'max' => _PS_VERSION_];

        parent::__construct();

        $this->displayName = $this->trans('DemoProductForm', [], 'Modules.Demoproductform.Config');
        $this->description = $this->trans('DemoProductForm module description', [], 'Modules.Demoproductform.Config');
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
     * @see https://devdocs.prestashop.com/8/modules/creation/module-translation/new-system/#translating-your-module
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

        $productFormModifier->modify($productId, $params['form_builder']);
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
}
