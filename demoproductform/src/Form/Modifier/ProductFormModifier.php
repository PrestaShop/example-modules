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

namespace PrestaShop\Module\DemoProductForm\Form\Modifier;

use PrestaShop\Module\DemoProductForm\CQRS\CommandHandler\UpdateCustomProductCommandHandler;
use PrestaShop\Module\DemoProductForm\Entity\CustomProduct;
use PrestaShop\Module\DemoProductForm\Form\Type\CustomTabContentType;
use PrestaShop\Module\DemoProductForm\Form\Type\CustomTabType;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId;
use PrestaShopBundle\Form\Admin\Type\IconButtonType;
use PrestaShopBundle\Form\FormBuilderModifier;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ProductFormModifier
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly FormBuilderModifier $formBuilderModifier
    ) {
    }

    public function modify(
        ?ProductId $productId,
        FormBuilderInterface $productFormBuilder
    ): void {
        $idValue = $productId ? $productId->getValue() : null;
        $customProduct = new CustomProduct($idValue);
        $this->modifyDescriptionTab($customProduct, $productFormBuilder);
        $this->addCustomTab($customProduct, $productFormBuilder);
        $this->modifyFooter($productFormBuilder);
    }

    /**
     * @see UpdateCustomProductCommandHandler to check how the field is handled on form POST
     */
    private function modifyDescriptionTab(CustomProduct $customProduct, FormBuilderInterface $productFormBuilder): void
    {
        $descriptionTabFormBuilder = $productFormBuilder->get('description');
        $this->formBuilderModifier->addAfter(
            $descriptionTabFormBuilder,
            'description',
            'demo_module_custom_field',
            TextType::class,
            [
                // you can remove the label if you dont need it by passing 'label' => false
                'label' => $this->translator->trans('Demo custom field', [], 'Modules.Demoproductform.Admin'),
                // customize label by any html attribute
                'label_attr' => [
                    'title' => 'h2',
                    'class' => 'text-info',
                ],
                'attr' => [
                    'placeholder' => $this->translator->trans('Your example text here', [], 'Modules.Demoproductform.Admin'),
                ],
                // this is just an example, but in real case scenario you could have some data provider class to wrap more complex cases
                'data' => $customProduct->custom_field,
                'empty_data' => '',
                'form_theme' => '@PrestaShop/Admin/TwigTemplateForm/prestashop_ui_kit_base.html.twig',
            ]
        );
    }

    private function addCustomTab(CustomProduct $customProduct, FormBuilderInterface $productFormBuilder): void
    {
        $this->formBuilderModifier->addAfter(
            $productFormBuilder,
            'pricing',
            'custom_tab',
            CustomTabType::class,
            [
                'data' => [
                    'custom_price' => $customProduct->custom_price,
                ],
            ]
        );
        $this->formBuilderModifier->addAfter(
            $productFormBuilder,
            'custom_tab',
            'custom_tab_content',
            CustomTabContentType::class,
            [
                'data' => [
                    'custom_price' => $customProduct->custom_price,
                ],
            ],
        );
    }

    private function modifyFooter(FormBuilderInterface $productFormBuilder): void
    {
        $headerFormBuilder = $productFormBuilder->get('footer');
        $headerFormBuilder->add('forms_info', IconButtonType::class, [
            'label' => $this->translator->trans('Open supplier website'),
            'type' => 'link',
            'attr' => [
                'href' => 'http://www.prestashop.com',
                'target' => '_blank',
            ],
        ]);
    }
}
