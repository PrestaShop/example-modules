<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */

declare(strict_types=1);

namespace PrestaShop\Module\DemoSymfonyForm\Form;

use PrestaShop\PrestaShop\Core\ConstraintValidator\Constraints\TypedRegex;
use PrestaShopBundle\Form\Admin\Type\FormattedTextareaType;
use PrestaShopBundle\Form\Admin\Type\GeneratableTextType;
use PrestaShopBundle\Form\Admin\Type\GeoCoordinatesType;
use PrestaShopBundle\Form\Admin\Type\TextWithLengthCounterType;
use PrestaShopBundle\Form\Admin\Type\TranslatableType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class DemoConfigurationTextType extends TranslatorAwareType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('formatted_text_area_type', FormattedTextareaType::class, [
                'label' => $this->trans('Formatted text area type', 'Modules.DemoSymfonyForm.Admin'),
            ])
            ->add('generatable_text_type', GeneratableTextType::class, [
                'label' => $this->trans('Generatable text type', 'Modules.DemoSymfonyForm.Admin'),
                'generated_value_length' => 5,
            ])
            ->add('text_with_length_counter_type', TextWithLengthCounterType::class, [
                'max_length' => 50,
                'label' => $this->trans('Text with length counter type', 'Modules.DemoSymfonyForm.Admin'),
            ])
            ->add('number_type_with_unit', NumberType::class, [
                'label' => $this->trans('Number type with Unit extension', 'Modules.DemoSymfonyForm.Admin'),
                'unit' => 'kg',
            ])
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
                'help' => $this->trans('Throws error if length is > 30', 'Modules.DemoSymfonyForm.Admin'),
                'type' => FormattedTextareaType::class,
                'required' => false,
                'options' => [
                    'constraints' => [
                        new Length([
                            'max' => 130,
                        ]),
                    ],
                ],
            ])
            ->add('coordinates', GeoCoordinatesType::class,[
                'label' => $this->trans('Geocoordinates type', 'Modules.DemoSymfonyForm.Admin'),
            ]);
    }
}
