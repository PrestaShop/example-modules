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

use PrestaShop\Module\DemoProductForm\Install\Installer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

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
        $this->ps_versions_compliancy = ['min' => '1.7.7.0', 'max' => _PS_VERSION_];

        parent::__construct();

        $this->displayName = $this->l('DemoProductForm');
        $this->description = $this->l('DemoProductForm module description');
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
     * Add custom field to product form at the end of tabs
     *
     * @param array $params
     */
    public function hookActionProductFormBuilderModifier(array $params): void
    {
        /** @var FormBuilderInterface $productFormBuilder */
        $productFormBuilder = $params['form_builder'];
        $basicTabFormBuilder = $productFormBuilder->get('basic');

        // adds simple text field add the end of Basic tab
        $basicTabFormBuilder->add('demo_module_custom_field', TextType::class, [
            // you can remove the label if you dont need it by passing 'label' => false
            'label' => $this->l('Demo custom field'),
            // customize label by any html attribute
            'label_attr' => [
                'title' => 'h2',
                'class' => 'text-info',
            ],
            'attr' => [
                'placeholder' => 'Your example text here',
            ],
        ]);
    }
}
