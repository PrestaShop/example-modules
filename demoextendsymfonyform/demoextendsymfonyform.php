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

use PrestaShop\Module\DemoExtendSymfonyForm\Uploader\SupplierExtraImageUploader;
use PrestaShop\PrestaShop\Core\Image\Uploader\ImageUploaderInterface;
use PrestaShopBundle\Form\Admin\Type\CustomContentType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__.'/vendor/autoload.php';

class demoextendsymfonyform extends Module
{
    private const SUPPLIER_EXTRA_IMAGE_PATH = '/img/su/'.SupplierExtraImageUploader::EXTRA_IMAGE_NAME;

    public function __construct()
    {
        $this->name = 'demoextendsymfonyform';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '1.7.7.0', 'max' => _PS_VERSION_];

        parent::__construct();

        $this->displayName = $this->l('Demo Symfony Forms');
        $this->description = $this->l('Demonstration of how to add an image upload field inside the Symfony form');
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('actionSupplierFormBuilderModifier')
            && $this->registerHook('actionAfterUpdateSupplierFormHandler');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookActionSupplierFormBuilderModifier(array $params)
    {
        $translator = $this->getTranslator();
        /** @var FormBuilderInterface $formBuilder */
        $formBuilder = $params['form_builder'];
        $formBuilder
            ->add('upload_image_file', FileType::class, [
                'label' => $translator->trans('Upload image file', [], 'Modules.DemoExtendSymfonyForm'),
                'required' => false,
            ]);

        $supplierExtraImageUrl = _PS_SUPP_IMG_DIR_. SupplierExtraImageUploader::EXTRA_IMAGE_NAME . $params['id'] . '.jpg';
        if (file_exists($supplierExtraImageUrl)) {
            $formBuilder
                ->add('image_file', CustomContentType::class, [
                    'required' => false,
                    'template' => '@Modules/demoextendsymfonyform/src/View/upload_image.html.twig',
                    'data' => [
                        'supplierId' => $params['id'],
                        'imageUrl' => self::SUPPLIER_EXTRA_IMAGE_PATH . $params['id'] . '.jpg',
                    ],
                ]);

        }
    }

    public function hookActionAfterUpdateSupplierFormHandler(array $params)
    {
        /** @var ImageUploaderInterface supplierExtraImageUploader */
        $supplierExtraImageUploader = $this->get(
            'prestashop.module.demoextendsymfonyform.uploader.supplier_extra_image_uploader'
        );

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $params['form_data']['upload_image_file'];

        if ($uploadedFile instanceof UploadedFile) {
            $supplierExtraImageUploader->upload($params['id'], $uploadedFile);
        }
    }

}
