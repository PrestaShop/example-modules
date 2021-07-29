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

use PrestaShopBundle\Form\Admin\Type\DatePickerType;
use PrestaShopBundle\Form\Admin\Type\DateRangeType;
use PrestaShopBundle\Form\Admin\Type\IntegerMinMaxFilterType;
use PrestaShopBundle\Form\Admin\Type\MoneyWithSuffixType;
use PrestaShopBundle\Form\Admin\Type\NumberMinMaxFilterType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\FormBuilderInterface;

class DemoConfigurationOtherType extends TranslatorAwareType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'money_with_suffix_type',
                MoneyWithSuffixType::class,
                [
                    'label' => $this->trans('Money with suffix', 'Modules.DemoSymfonyForm.Admin'),
                ]
            )
            ->add(
                'date_picker_type',
                DatePickerType::class,
                [
                    'label' => $this->trans('Date picker type', 'Modules.DemoSymfonyForm.Admin'),
                ]
            )
            ->add(
                'date_range_type',
                DateRangeType::class,
                [
                    'label' => $this->trans('Date range type', 'Modules.DemoSymfonyForm.Admin'),
                ]
            )
            ->add(
                'integer_min_max_filter_type',
                IntegerMinMaxFilterType::class,
                [
                    'label' => $this->trans('Integer min max filter type', 'Modules.DemoSymfonyForm.Admin'),
                    'min_field_options' => [
                        'attr' => [
                            'placeholder' => $this->trans('Min', 'Admin.Global'),
                            'min' => 0,
                            'step' => 1,
                        ],
                    ],
                    'max_field_options' => [
                        'attr' => [
                            'placeholder' => $this->trans('Max', 'Admin.Global'),
                            'min' => 100,
                            'step' => 3,
                        ],
                    ],
                ]
            )
            ->add(
                'number_min_max_filter_type',
                NumberMinMaxFilterType::class,
                [
                    'label' => $this->trans('Number min max filter type', 'Modules.DemoSymfonyForm.Admin'),
                    'min_field_options' => [
                        'attr' => [
                            'placeholder' => $this->trans('Min', 'Admin.Global'),
                        ],
                    ],
                    'max_field_options' => [
                        'attr' => [
                            'placeholder' => $this->trans('Max', 'Admin.Global'),
                        ],
                    ],
                ]
            );
    }
}
