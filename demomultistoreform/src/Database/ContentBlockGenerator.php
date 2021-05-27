<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
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
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

declare(strict_types=1);

namespace PrestaShop\Module\DemoMultistoreForm\Database;

use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\Module\DemoMultistoreForm\Entity\ContentBlock;

class ContentBlockGenerator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function generateContentBlockFixtures()
    {
        $this->removeAll();
        $jsonFile = __DIR__ . '/../../Resources/contentBlocks.json';
        $contentBlocksData = json_decode(file_get_contents($jsonFile), true);

        foreach ($contentBlocksData as $data) {
            $contentBlock = new ContentBlock();
            $contentBlock->setTitle($data['title']);
            $contentBlock->setDescription($data['description']);
            $contentBlock->setEnable($data['enable']);
            $this->entityManager->persist($contentBlock);
        }

        $this->entityManager->flush();
    }

    private function removeAll()
    {
        $contentBlocks = $this->entityManager->getRepository(ContentBlock::class)->findAll();
        foreach ($contentBlocks as $contentBlock) {
            $this->entityManager->remove($contentBlock);
        }

        $this->entityManager->flush();
    }
}
