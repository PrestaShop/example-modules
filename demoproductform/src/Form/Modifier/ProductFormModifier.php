<?php
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

declare(strict_types=1);

namespace PrestaShop\Module\DemoProductForm\Form\Modifier;

use PrestaShop\Module\DemoProductForm\CQRS\Command\AddMyModuleCustomFieldCommand;
use PrestaShop\Module\DemoProductForm\CQRS\CommandBuilder\ModuleProductCommandsBuilder;
use PrestaShop\Module\DemoProductForm\CQRS\CommandHandler\AddMyModuleCustomFieldHandler;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use const http\Client\Curl\POSTREDIR_301;

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
     *
     * @param FormBuilderInterface $productFormBuilder
     */
    public function modify(FormBuilderInterface $productFormBuilder): void
    {
        /** @var FormBuilderInterface $productFormBuilder */
        $basicTabFormBuilder = $productFormBuilder->get('basic');

        $this->modifyBasicTab($basicTabFormBuilder);
    }

    /**
     * @see AddMyModuleCustomFieldHandler to check how the field is handled on form POST
     *
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
