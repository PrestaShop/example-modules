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

namespace PrestaShop\Module\DemoMultistoreForm\Database;

use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\Module\DemoMultistoreForm\Entity\ContentBlock;
use Exception;
use PrestaShopBundle\Entity\Shop;

class ContentBlockGenerator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var string
     */
    private $jsonFilePath;

    /**
     * @param EntityManagerInterface $entityManager
     * @param string $jsonFilePath
     */
    public function __construct(EntityManagerInterface $entityManager, string $jsonFilePath)
    {
        $this->entityManager = $entityManager;
        $this->jsonFilePath = $jsonFilePath;
    }

    /**
     * @return array
     */
    public function generateContentBlockFixtures(): array
    {
        $errors = [];

        try {
            $this->removeAll();
            $jsonFile = __DIR__ . $this->jsonFilePath;
            if (!file_exists($jsonFile)) {
                $errors[] = sprintf(
                    'File %s was not found.',
                    $jsonFile
                );
            }
            $contentBlocksData = json_decode(file_get_contents($jsonFile), true);
            $shop = $this->getFirstShop();
            foreach ($contentBlocksData as $data) {
                $contentBlock = new ContentBlock();
                $contentBlock->setTitle($data['title']);
                $contentBlock->setDescription($data['description']);
                $contentBlock->setEnable($data['enable']);
                if ($shop !== null) {
                    $contentBlock->addShop($shop);
                }
                $this->entityManager->persist($contentBlock);
            }

            $this->entityManager->flush();
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }

        return $errors;
    }

    private function removeAll(): void
    {
        $contentBlocks = $this->entityManager->getRepository(ContentBlock::class)->findAll();

        foreach ($contentBlocks as $contentBlock) {
            $contentBlock->clearShops();
            $this->entityManager->remove($contentBlock);
        }

        $this->entityManager->flush();
    }

    /**
     * Returns first shop in the list, it could be any shop, doesn't matter, it's for fixtures
     *
     * @return Shop|null
     */
    private function getFirstShop(): ?Shop
    {
        $shopList = $this->entityManager->getRepository(Shop::class)->findAll();
        foreach ($shopList as $shop) {
            return $shop;
        }

        return null;
    }
}
