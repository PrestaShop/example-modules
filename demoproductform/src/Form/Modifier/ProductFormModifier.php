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

namespace PrestaShop\Module\DemoProductForm\Form\Modifier;

use PrestaShop\Module\DemoProductForm\CQRS\CommandHandler\SaveMyModuleCustomFieldHandler;
use PrestaShop\Module\DemoProductForm\Entity\CustomProduct;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;

final class ProductFormModifier
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var FormBuilderModifier
     */
    private $formBuilderModifier;

    /**
     * @param TranslatorInterface $translator
     * @param FormBuilderModifier $formBuilderModifier
     */
    public function __construct(
        TranslatorInterface $translator,
        FormBuilderModifier $formBuilderModifier
    ) {
        $this->translator = $translator;
        $this->formBuilderModifier = $formBuilderModifier;
    }

    /**
     * @param ProductId|null $productId
     * @param FormBuilderInterface $productFormBuilder
     * @param $formData
     */
    public function modify(
        ?ProductId $productId,
        FormBuilderInterface $productFormBuilder,
        array $formData
    ): void {
        $this->modifyBasicTab($productId, $productFormBuilder);
        $this->modifyShortcutTab($productFormBuilder, $formData);
        $this->modifyFooter($productFormBuilder);
    }

    /**
     * @param ProductId|null $productId
     * @param FormBuilderInterface $productFormBuilder
     *
     * @see SaveMyModuleCustomFieldHandler to check how the field is handled on form POST
     */
    private function modifyBasicTab(?ProductId $productId, FormBuilderInterface $productFormBuilder): void
    {
        $idValue = $productId ? $productId->getValue() : null;
        $customProduct = new CustomProduct($idValue);

        $basicTabFormBuilder = $productFormBuilder->get('basic');
        $this->formBuilderModifier->addAfter(
            $basicTabFormBuilder,
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
                    'placeholder' => 'Your example text here',
                ],
                // this is just an example, but in real case scenario you could have some data provider class to wrap more complex cases
                'data' => $customProduct->custom_field,
                'empty_data' => '',
            ]
        );
    }

    /**
     * @param FormBuilderInterface $productFormBuilder
     * @param array $formData
     */
    private function modifyShortcutTab(FormBuilderInterface $productFormBuilder, array $formData): void
    {
        // This is just an example of adding a field in the shortcuts column (the data is not saved)
        $shortcutsFormBuilder = $productFormBuilder->get('shortcuts');
        $retailPriceFormBuilder = $shortcutsFormBuilder->get('retail_price');
        $this->formBuilderModifier->addBefore($retailPriceFormBuilder, 'price_tax_included',
            'additional_shipping_cost',
            MoneyType::class,
            [
                'data' => $formData['shipping']['additional_shipping_cost'] ?? 0.0,
            ]
        );
    }

    /**
     * @param FormBuilderInterface $productFormBuilder
     */
    private function modifyFooter(FormBuilderInterface $productFormBuilder): void
    {
        $headerFormBuilder = $productFormBuilder->get('header');
        $headerFormBuilder->add('forms_info', SwitchType::class, [
            'label' => $this->translator->trans('Form infos', [], 'Modules.Demoproductform.Admin'),
            'label_help_box' => $this->translator->trans(
                'You can display information about each form part to see where it is included in the Product form structure, the information is shown when mouse is over the element.',
                [],
                'Modules.Demoproductform.Admin'
            ),
            'data' => false,
            'row_attr' => [
                'class' => 'col-1 m-0 p-0',
            ],
        ]);
    }
}
