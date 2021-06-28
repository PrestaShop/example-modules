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

use PrestaShopBundle\Form\Admin\Type\ShopChoiceTreeType;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use PrestaShop\PrestaShop\Core\ConstraintValidator\Constraints\TypedRegex;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContentBlockType extends TranslatorAwareType
{
    /**
     * @var bool
     */
    private $isMultistoreUsed;

    /**
     * @param TranslatorInterface $translator
     * @param array $locales
     * @param bool $isMultistoreUsed
     */
    public function __construct(
        TranslatorInterface $translator,
        array $locales,
        bool $isMultistoreUsed
    ) {
        parent::__construct($translator, $locales);

        $this->isMultistoreUsed = $isMultistoreUsed;
    }

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
                    'label' => 'Title',
                    'help' => 'Throws error if length is > 50 or text contains <>={}',
                    'constraints' => [
                        new TypedRegex([
                            'type' => 'generic_name',
                        ]),
                        new Length([
                            'max' => 50,
                        ]),
                    ],
                ]
            )
            ->add(
                'description',
                TextType::class,
                [
                    'label' => 'Description',
                    'help' => 'Throws error if length is > 100 or text contains <>={}',
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
            );
        if ($this->isMultistoreUsed) {
            $builder->add(
                'shop_association',
                ShopChoiceTreeType::class,
                [
                    'label' => 'Shop associations',
                    'constraints' => [
                        new NotBlank([
                            'message' => $this->trans(
                                'You have to select at least one shop to associate this item with',
                                'Admin.Notifications.Error'
                            ),
                        ])
                    ]
                ]
            );
        }
    }
}
