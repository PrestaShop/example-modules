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

use PrestaShop\Module\DemoExtendSymfonyForm\Entity\SupplierExtraImage;
use PrestaShop\Module\DemoExtendSymfonyForm\Install\Installer;
use PrestaShop\Module\DemoExtendSymfonyForm\Repository\SupplierExtraImageRepository;
use PrestaShop\PrestaShop\Core\Image\Uploader\ImageUploaderInterface;
use PrestaShopBundle\Form\Admin\Type\CustomContentType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use PrestaShop\Module\DemoExtendSymfonyForm\Uploader\SupplierExtraImageUploader;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Class demoextendsymfonyform
 */
class DemoExtendSymfonyForm2 extends Module
{
    private const SUPPLIER_EXTRA_IMAGE_PATH = '/img/su/';

    public function __construct()
    {
        $this->name = 'demoextendsymfonyform2';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '9.0.0', 'max' => '9.99.99'];

        parent::__construct();

        $this->displayName = $this->trans('Demo Symfony Forms #2', [], 'Modules.DemoExtendSymfonyForm2.Admin');
        $this->description = $this->trans('Demonstration of how to add an image upload field inside the Symfony form', [], 'Modules.DemoExtendSymfonyForm2.Admin');
    }

    /**
     * @return bool
     */
    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        $installer = new Installer();

        return $installer->install($this);
    }

    /**
     * @return bool
     */
    public function uninstall()
    {
        $installer = new Installer();

        return $installer->uninstall() && parent::uninstall();
    }

    /**
     * @param array $params
     */
    public function hookActionSupplierFormBuilderModifier(array $params)
    {
        /** @var SupplierExtraImageRepository $supplierExtraImageRepository */
        $supplierExtraImageRepository = $this->get(SupplierExtraImageRepository::class);

        /** @var FormBuilderInterface $formBuilder */
        $formBuilder = $params['form_builder'];
        $formBuilder
            ->add('upload_image_file', FileType::class, [
                'label' => $this->trans('Upload image file', [], 'Modules.DemoExtendSymfonyForm'),
                'required' => false,
            ]);

        /** @var SupplierExtraImage $supplierExtraImage */
        $supplierExtraImage = $supplierExtraImageRepository->findOneBy(['supplierId' => $params['id']]);
        if ($supplierExtraImage && file_exists(_PS_SUPP_IMG_DIR_ . $supplierExtraImage->getImageName())) {
            $formBuilder
                ->add('image_file', CustomContentType::class, [
                    'required' => false,
                    'template' => '@Modules/demoextendsymfonyform2/src/View/upload_image.html.twig',
                    'data' => [
                        'supplierId' => $params['id'],
                        'imageUrl' => self::SUPPLIER_EXTRA_IMAGE_PATH . $supplierExtraImage->getImageName(),
                    ],
                ]);
        }
    }

    /**
     * @param array $params
     */
    public function hookActionAfterUpdateSupplierFormHandler(array $params)
    {
        $this->uploadImage($params);
    }

    /**
     * @param array $params
     */
    public function hookActionAfterCreateSupplierFormHandler(array $params)
    {
        $this->uploadImage($params);
    }

    /**
     * @param array $params
     */
    private function uploadImage(array $params): void
    {
        /** @var ImageUploaderInterface $supplierExtraImageUploader */
        $supplierExtraImageUploader = $this->get(SupplierExtraImageUploader::class);

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $params['form_data']['upload_image_file'];

        if ($uploadedFile instanceof UploadedFile) {
            $supplierExtraImageUploader->upload($params['id'], $uploadedFile);
        }
    }
}
