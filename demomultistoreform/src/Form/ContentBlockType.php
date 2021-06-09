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

use PrestaShopBundle\Form\Admin\Type\CommonAbstractType;
use PrestaShopBundle\Form\Admin\Type\ShopChoiceTreeType;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use PrestaShop\PrestaShop\Core\ConstraintValidator\Constraints\TypedRegex;
use Symfony\Component\Validator\Constraints\Length;

class ContentBlockType extends CommonAbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'title',
                    'help' => 'Throws error if length is > 20 or text contains <>={}',
                    'constraints' => [
                        new TypedRegex([
                            'type' => 'generic_name',
                        ]),
                        new Length([
                            'max' => 20,
                        ]),
                    ],
                ]
            )
            ->add(
                'description',
                TextType::class,
                [
                    'label' => 'Description',
                    'help' => 'Throws error if length is > 50 or text contains <>={}',
                    'constraints' => [
                        new TypedRegex([
                            'type' => 'generic_name',
                        ]),
                        new Length([
                            'max' => 100,
                        ]),
                    ],
                ]
            )
            ->add(
                'enable',
                SwitchType::class,
                [
                    'label' => 'Enable',
                ]
            )
            ->add(
                'shop_association',
                ShopChoiceTreeType::class,
                [
                    'label' => 'Shop associations',
                ]
            );
    }
}
