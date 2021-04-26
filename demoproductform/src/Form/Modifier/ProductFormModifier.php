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
     * @param TranslatorInterface $translator
     */
    public function __construct(
        TranslatorInterface $translator
    ) {
        $this->translator = $translator;
    }

    /**
     * @param FormBuilderInterface $productFormBuilder
     */
    public function modify(FormBuilderInterface $productFormBuilder): void
    {
        /** @var FormBuilderInterface $productFormBuilder */
        $basicTabFormBuilder = $productFormBuilder->get('basic');

        $this->modifyBasicTab($basicTabFormBuilder);
    }

    /**
     * @param FormBuilderInterface $basicTabFormBuilder
     */
    private function modifyBasicTab(FormBuilderInterface $basicTabFormBuilder): void
    {
        // adds simple text field at the end of Basic tab in product form
        $basicTabFormBuilder->add('demo_module_custom_field', TextType::class, [
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
        ]);
    }
}
