<?php
declare(strict_types=1);

namespace PrestaShop\Module\DemoSymfonyFormSimple\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;

class DemoConfigurationFormType extends TranslatorAwareType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('config_text', TextType::class, [
                'label' => $this->trans('Configuration text', 'Modules.DemoSymfonyFormSimple.Admin'),
            ]);
    }
}