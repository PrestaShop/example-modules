<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */

declare(strict_types=1);

namespace PrestaShop\Module\DemoMultistoreForm\Form;

use Symfony\Component\Form\Extension\Core\Type\ColorType;
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
            ->add('color', ColorType::class, [
                'attr' => ['class' => 'col-md-4 col-lg-2 p-1 h-25 w-50'],
                'required' => false,
                'label' => 'Color',
                'multistore_configuration_key' => 'PS_DEMO_MULTISTORE_COLOR',
                ])
            ->add(
                'italic',
                SwitchType::class,
                [
                    'label' => 'Italic',
                    'multistore_configuration_key' => 'PS_DEMO_MULTISTORE_ITALIC',
                ]
            )
            ->add(
                'bold',
                SwitchType::class,
                [
                    'label' => 'Bold',
                    'multistore_configuration_key' => 'PS_DEMO_MULTISTORE_BOLD',
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
