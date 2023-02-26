<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

declare(strict_types=1);

namespace PrestaShop\Module\DemoProductForm\Form\Modifier;

use PrestaShop\Module\DemoProductForm\Entity\CustomCombination;
use PrestaShop\Module\DemoProductForm\Form\Type\CustomTabType;
use PrestaShop\PrestaShop\Core\Domain\Product\Combination\ValueObject\CombinationId;
use PrestaShopBundle\Form\FormBuilderModifier;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;

class CombinationFormModifier
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
     * @param CombinationId|null $combinationId
     * @param FormBuilderInterface $combinationFormBuilder
     */
    public function modify(
        ?CombinationId $combinationId,
        FormBuilderInterface $combinationFormBuilder
    ): void {
        $idValue = $combinationId ? $combinationId->getValue() : null;
        $customCombination = new CustomCombination($idValue);
        $this->addCustomField($customCombination, $combinationFormBuilder);
        $this->addCustomTab($customCombination, $combinationFormBuilder);
    }

    /**
     * @param CustomCombination $customCombination
     * @param FormBuilderInterface $combinationFormBuilder
     *
     * @see demoproductform::hook
     */
    private function addCustomField(CustomCombination $customCombination, FormBuilderInterface $combinationFormBuilder): void
    {
        $this->formBuilderModifier->addAfter(
            $combinationFormBuilder,
            'references',
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
                'data' => $customCombination->custom_field,
                'empty_data' => '',
                'form_theme' => '@PrestaShop/Admin/TwigTemplateForm/prestashop_ui_kit_base.html.twig',
            ]
        );
    }

    /**
     * @param CustomCombination $customCombination
     * @param FormBuilderInterface $combinationFormBuilder
     */
    private function addCustomTab(CustomCombination $customCombination, FormBuilderInterface $combinationFormBuilder): void
    {
        $this->formBuilderModifier->addAfter(
            $combinationFormBuilder,
            'price_impact',
            'custom_tab',
            CustomTabType::class,
            [
                'data' => [
                    'custom_price' => $customCombination->custom_price,
                ],
            ]
        );
    }
}
