<?php
/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

declare(strict_types=1);

namespace PrestaShop\Module\DemoMultistoreForm\Form;

use PrestaShopBundle\Form\Admin\Type\ColorPickerType;
use PrestaShopBundle\Form\Admin\Type\CommonAbstractType;
use PrestaShopBundle\Form\Admin\Type\MultistoreConfigurationType;
use Symfony\Component\Form\FormBuilderInterface;
use PrestaShopBundle\Form\Admin\Type\SwitchType;

class ContentBlockConfigurationType extends CommonAbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('color', ColorPickerType::class, [
                'required' => false,
                'label' => 'Color',
                'attr' => [
                    'multistore_configuration_key' => 'PS_DEMO_MULTISTORE_COLOR',
                ],
            ])
            ->add(
                'italic',
                SwitchType::class,
                [
                    'label' => 'Italic',
                    'attr' => [
                        'multistore_configuration_key' => 'PS_DEMO_MULTISTORE_ITALIC',
                    ],
                ]
            )
            ->add(
                'bold',
                SwitchType::class,
                [
                    'label' => 'Bold',
                    'attr' => [
                        'multistore_configuration_key' => 'PS_DEMO_MULTISTORE_BOLD',
                    ],
                ]
            );
    }

    /**
     * {@inheritdoc}
     *
     * @see MultistoreConfigurationTypeExtension
     */
    public function getParent(): string
    {
        return MultistoreConfigurationType::class;
    }
}
