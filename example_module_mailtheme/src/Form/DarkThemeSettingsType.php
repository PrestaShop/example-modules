<?php
/**
 * 2007-2019 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
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
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

namespace PrestaShop\Module\ExampleModuleMailtheme\Form;

use PrestaShop\Module\ExampleModuleMailtheme\DarkThemeSettings;
use PrestaShop\PrestaShop\Core\ConstraintValidator\Constraints\DefaultLanguage;
use PrestaShopBundle\Form\Admin\Type\TranslatableType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class DarkThemeSettingsType extends TranslatorAwareType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('custom_message', TranslatableType::class, [
                'constraints' => [
                    new DefaultLanguage([
                        'message' => $this->trans(
                            'The field %field_name% is required at least in your default language.',
                            'Admin.Notifications.Error',
                            [
                                '%field_name%' => sprintf(
                                    '"%s"',
                                    $this->trans('Custom message', 'Modules.ExampleModuleMailtheme')
                                ),
                            ]
                        ),
                    ]),
                ],
                'options' => [
                    'attr' => [
                        'class' => 'js-copier-source-title',
                    ],
                    'constraints' => [
                        new Length([
                            'max' => DarkThemeSettings::CUSTOM_MESSAGE_MAX_SIZE,
                            'maxMessage' => $this->trans(
                                'This field cannot be longer than %limit% characters',
                                'Admin.Notifications.Error',
                                ['%limit%' => DarkThemeSettings::CUSTOM_MESSAGE_MAX_SIZE]
                            ),
                        ]),
                    ],
                ],
            ])
            ->add('primary_background_color', TextType::class, [
                'attr' => [
                    'class' => 'color-picker',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/#[0-9A-F]{6}/',
                        'message' => $this->trans(
                            'The color must use the hexadecimal syntax (ex: #ffffff)',
                            'Modules.ExampleModuleMailtheme'
                        ),
                    ]),
                ],
            ])
            ->add('secondary_background_color', TextType::class, [
                'attr' => [
                    'class' => 'color-picker',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/#[0-9A-F]{6}/',
                        'message' => $this->trans(
                            'The color must use the hexadecimal syntax (ex: #ffffff)',
                            'Modules.ExampleModuleMailtheme'
                        ),
                    ]),
                ],
            ])
            ->add('primary_text_color', TextType::class, [
                'attr' => [
                    'class' => 'color-picker',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/#[0-9A-F]{6}/',
                        'message' => $this->trans(
                            'The color must use the hexadecimal syntax (ex: #ffffff)',
                            'Modules.ExampleModuleMailtheme'
                        ),
                    ]),
                ],
            ])
            ->add('secondary_text_color', TextType::class, [
                'attr' => [
                    'class' => 'color-picker',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/#[0-9A-F]{6}/',
                        'message' => $this->trans(
                            'The color must use the hexadecimal syntax (ex: #ffffff)',
                            'Modules.ExampleModuleMailtheme'
                        ),
                    ]),
                ],
            ])
        ;
    }
}
