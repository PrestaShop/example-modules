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

namespace PrestaShop\Module\DemoMultistoreForm\Form;

use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\Module\DemoMultistoreForm\Entity\ContentBlock;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;

class ContentBlockFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ContentBlockFormDataProvider constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param mixed $id
     * @return array
     */
    public function getData($id): array
    {
        $contentBlock = $this->em->getRepository(ContentBlock::class)->find((int) $id);
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
