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
        if (isset($data['title']) && is_string($data['title'])) {
            $contentBlock->setTitle($data['title']);
        }
        if (isset($data['description']) && is_string($data['description'])) {
            $contentBlock->setDescription($data['description']);
        }
        if (isset($data['enable']) && is_bool($data['enable'])) {
            $contentBlock->setEnable($data['enable']);
        }

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
