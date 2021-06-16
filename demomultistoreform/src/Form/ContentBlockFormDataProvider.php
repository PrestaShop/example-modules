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

use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\Module\DemoMultistoreForm\Entity\ContentBlock;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;

class ContentBlockFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ContentBlockFormDataProvider constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param mixed $id
     * @return array
     */
    public function getData($id): array
    {
        $contentBlock = $this->entityManager->getRepository(ContentBlock::class)->find((int) $id);
        $shopIds = [];
        foreach ($contentBlock->getShops() as $shop) {
            $shopIds[] = $shop->getId();
        }

        return [
            'title' => $contentBlock->getTitle(),
            'description' => $contentBlock->getDescription(),
            'enable' => $contentBlock->getEnable(),
            'shop_association' => $shopIds,
        ];
    }

    /**
     * @return array
     */
    public function getDefaultData(): array
    {
        return [
            'title' => '',
            'description' => '',
            'enable' => false,
            'shop_association' => [],
        ];
    }
}
