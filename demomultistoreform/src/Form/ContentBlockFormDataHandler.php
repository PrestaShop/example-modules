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

;

class ContentBlockFormDataHandler implements FormDataHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
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

        $this->em->persist($contentBlock);
        $this->em->flush();

        return $contentBlock->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data): int
    {
        $contentBlock = $this->em->getRepository(ContentBlock::class)->find($id);
        $contentBlock->setTitle($data['title']);
        $contentBlock->setDescription($data['description']);
        $contentBlock->setEnable($data['enable']);
        $this->em->flush();

        return $contentBlock->getId();
    }
}
