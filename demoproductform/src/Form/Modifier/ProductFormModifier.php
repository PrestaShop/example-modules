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
use PrestaShop\Module\DemoProductForm\Form\Type\CustomTabType;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use PrestaShopBundle\Form\FormBuilderModifier;
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
        $idValue = $productId ? $productId->getValue() : null;
        $customProduct = new CustomProduct($idValue);
        $this->modifyDescriptionTab($customProduct, $productFormBuilder);
        $this->addCustomTab($customProduct, $productFormBuilder);
        $this->modifyFooter($productFormBuilder);
    }

    /**
     * @param CustomProduct $customProduct
     * @param FormBuilderInterface $productFormBuilder
     *
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
                    'placeholder' => 'Your example text here',
                ],
                // this is just an example, but in real case scenario you could have some data provider class to wrap more complex cases
                'data' => $customProduct->custom_field,
                'empty_data' => '',
                'form_theme' => '@PrestaShop/Admin/TwigTemplateForm/prestashop_ui_kit_base.html.twig',
            ]
        );
    }

    /**
     * @param CustomProduct $customProduct
     * @param FormBuilderInterface $productFormBuilder
     */
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
    }

    /**
     * @param FormBuilderInterface $productFormBuilder
     */
    private function modifyFooter(FormBuilderInterface $productFormBuilder): void
    {
        $headerFormBuilder = $productFormBuilder->get('footer');
        $headerFormBuilder->add('forms_info', SwitchType::class, [
            'label' => false,
            'choices' => [
                $this->translator->trans('Hide form infos', [], 'Modules.Demoproductform.Admin') => false,
                $this->translator->trans('Show form infos', [], 'Modules.Demoproductform.Admin') => true,
            ],
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
