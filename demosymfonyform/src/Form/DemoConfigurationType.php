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

namespace PrestaShop\Module\DemoSymfonyForm\Form;

use PrestaShop\PrestaShop\Core\ConstraintValidator\Constraints\TypedRegex;
use PrestaShopBundle\Form\Admin\Type\FormattedTextareaType;
use PrestaShopBundle\Form\Admin\Type\TranslatableType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class DemoConfigurationType extends TranslatorAwareType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('translatable_type', TranslatableType::class, [
                    'label' => $this->trans('Translatable type', 'Modules.DemoSymfonyForm.Admin'),
                    'help' => $this->trans('Throws error if length is > 10 or text contains <>={}', 'Modules.DemoSymfonyForm.Admin'),
                    'options' => [
                        'constraints' => [
                            new TypedRegex([
                                'type' => 'generic_name',
                            ]),
                            new Length([
                                'max' => 10,
                            ]),
                        ],
                    ],
                ]
            )
            ->add('translatable_text_area_type', TranslatableType::class, [
                    'label' => $this->trans('Translatable text area type', 'Modules.DemoSymfonyForm.Admin'),
                    'help' => $this->trans('Throws error if length is > 10 or text contains <>={}', 'Modules.DemoSymfonyForm.Admin'),
                    'type' => TextareaType::class,
                    'options' => [
                        'constraints' => [
                            new TypedRegex([
                                'type' => 'generic_name',
                            ]),
                            new Length([
                                'max' => 10,
                            ]),
                        ],
                    ],
                ]
            )
            ->add('translatable_formatted_text_area_type', TranslatableType::class, [
                'label' => $this->trans('Translatable formatted text area type', 'Modules.DemoSymfonyForm.Admin'),
                'help' => $this->trans('Throws error if length is > 10', 'Modules.DemoSymfonyForm.Admin'),
                'type' => FormattedTextareaType::class,
                'locales' => $this->locales,
                'required' => false,
                'options' => [
                    'constraints' => [
                        new Length([
                            'max' => 30,
                        ]),
                    ],
                ],
            ]);
    }
}
