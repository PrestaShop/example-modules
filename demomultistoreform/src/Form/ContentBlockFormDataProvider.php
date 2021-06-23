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

use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\Module\DemoMultistoreForm\Entity\ContentBlock;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;
use PrestaShop\PrestaShop\Adapter\Shop\Context;

class ContentBlockFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Context
     */
    private $shopContext;

    /**
     * ContentBlockFormDataProvider constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, Context $shopContext)
    {
        $this->entityManager = $entityManager;
        $this->shopContext = $shopContext;
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
            'shop_association' => $this->shopContext->getContextListShopID(),
        ];
    }
}
