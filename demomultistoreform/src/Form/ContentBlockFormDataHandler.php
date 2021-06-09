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

use PrestaShop\Module\DemoMultistoreForm\Entity\ContentBlock;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use PrestaShopBundle\Entity\Shop;

class ContentBlockFormDataHandler implements FormDataHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * {@inheritdoc}
     */
    public function create(array $data): int
    {
        $contentBlock = new ContentBlock();
        $contentBlock->setTitle($data['title']);
        $contentBlock->setDescription($data['description']);
        $contentBlock->setEnable($data['enable']);
        $this->addAssociatedShops($contentBlock, $data['shop_association'] ?? null);
        $this->entityManager->persist($contentBlock);
        $this->entityManager->flush();

        return $contentBlock->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data): int
    {
        $contentBlock = $this->entityManager->getRepository(ContentBlock::class)->find($id);
        $contentBlock->setTitle($data['title']);
        $contentBlock->setDescription($data['description']);
        $contentBlock->setEnable($data['enable']);
        $this->addAssociatedShops($contentBlock, $data['shop_association'] ?? null);
        $this->entityManager->flush();

        return $contentBlock->getId();
    }

    /**
     * @param ContentBlock $contentBlock
     * @param array|null $shopIdList
     */
    private function addAssociatedShops(ContentBlock &$contentBlock, array $shopIdList = null): void
    {
        $contentBlock->clearShops();

        if (empty($shopIdList)) {
            return;
        }

        foreach ($shopIdList as $shopId) {
            $shop = $this->entityManager->getRepository(Shop::class)->find($shopId);
            $contentBlock->addShop($shop);
        }
    }
}
