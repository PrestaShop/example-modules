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

use PrestaShopBundle\Form\Admin\Type\CategoryChoiceTreeType;
use PrestaShopBundle\Form\Admin\Type\CountryChoiceType;
use PrestaShopBundle\Form\Admin\Type\Material\MaterialChoiceTableType;
use PrestaShopBundle\Form\Admin\Type\Material\MaterialChoiceTreeType;
use PrestaShopBundle\Form\Admin\Type\Material\MaterialMultipleChoiceTableType;
use PrestaShopBundle\Form\Admin\Type\ShopChoiceTreeType;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\FormBuilderInterface;

class DemoConfigurationChoiceType extends TranslatorAwareType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $disabledCategories = [5];

        $builder
            ->add(
                'category_choice_tree_type',
                CategoryChoiceTreeType::class,
                [
                    'label' => $this->trans('Category choice type', 'Modules.DemoSymfonyForm.Admin'),
                    'disabled_values' => $disabledCategories,
                ]
            )
            ->add(
                'country_choice_type',
                CountryChoiceType::class,
                [
                    'label' => $this->trans('Country choice type', 'Modules.DemoSymfonyForm.Admin'),
                    'required' => true,
                    'with_dni_attr' => true,
                    'with_postcode_attr' => true,
                ]
            )
            ->add(
                'material_choice_table_type',
                MaterialChoiceTableType::class,
                [
                    'label' => $this->trans('Material choice table type', 'Modules.DemoSymfonyForm.Admin'),
                    'choices' => [
                        $this->trans('Choice 1', 'Modules.DemoSymfonyForm.Admin') => '1',
                        $this->trans('Choice 2', 'Modules.DemoSymfonyForm.Admin') => '2',
                    ],
                ]
            )
            ->add(
                'material_choice_tree_type',
                MaterialChoiceTreeType::class,
                [
                    'label' => $this->trans('Material choice tree type', 'Modules.DemoSymfonyForm.Admin'),
                    'choice_value' => 'id_choice',
                    'choices_tree' => [
                        '1' => [
                            'id_choice' => 1,
                            'name' => $this->trans('Choice 1', 'Modules.DemoSymfonyForm.Admin'),
                        ],
                        '2' => [
                            'id_choice' => 2,
                            'name' => $this->trans('Choice 2', 'Modules.DemoSymfonyForm.Admin'),
                            'children' => [
                                '3' => [
                                    'id_choice' => 3,
                                    'name' => $this->trans('Choice 3', 'Modules.DemoSymfonyForm.Admin'),
                                ],
                                '4' => [
                                    'id_choice' => 4,
                                    'name' => $this->trans('Choice 4', 'Modules.DemoSymfonyForm.Admin'),
                                ],
                            ],
                        ],
                    ],
                ]
            )
            ->add(
                'material_choice_multiple_choices_table',
                MaterialMultipleChoiceTableType::class,
                [
                    'label' => $this->trans('Material choice multiple choices table type', 'Modules.DemoSymfonyForm.Admin'),
                    'choices' => [
                        $this->trans('Vertical choice 1', 'Modules.DemoSymfonyForm.Admin') => '1',
                        $this->trans('Vertical choice 2', 'Modules.DemoSymfonyForm.Admin') => '2',
                        $this->trans('Vertical choice 3', 'Modules.DemoSymfonyForm.Admin') => '3',
                    ],
                    'multiple_choices' => [
                        [
                            'name' => 'choice_1',
                            'label' => $this->trans('Horizontal choice 1', 'Modules.DemoSymfonyForm.Admin'),
                            'multiple' => true,
                            /* You need choices array for the second time to be able to choose which horizontal choices are available for this vertical choice  */
                            'choices' => [
                                $this->trans('Vertical choice 1', 'Modules.DemoSymfonyForm.Admin') => '1',
                                $this->trans('Vertical choice 2', 'Modules.DemoSymfonyForm.Admin') => '2',
                            ],
                        ],
                        [
                            'name' => 'choice_2',
                            'label' => $this->trans('Horizontal choice 2', 'Modules.DemoSymfonyForm.Admin'),
                            'multiple' => true,
                            'choices' => [
                                $this->trans('Vertical choice 1', 'Modules.DemoSymfonyForm.Admin') => '1',
                                $this->trans('Vertical choice 2', 'Modules.DemoSymfonyForm.Admin') => '2',
                                $this->trans('Vertical choice 3', 'Modules.DemoSymfonyForm.Admin') => '3',
                            ],
                        ],
                    ],
                ]
            )
            ->add('shop_choices_tree_type',
                ShopChoiceTreeType::class,
                [
                    'label' => $this->trans('Material choice tree type', 'Modules.DemoSymfonyForm.Admin'),
                ]
            )
            ->add(
                'switch_type',
                SwitchType::class,
                [
                    'label' => $this->trans('Switch type', 'Modules.DemoSymfonyForm.Admin'),
                ]
            );
    }
}
