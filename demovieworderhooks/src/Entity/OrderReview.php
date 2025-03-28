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

namespace PrestaShop\Module\DemoViewOrderHooks\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PrestaShop\Module\DemoViewOrderHooks\Repository\OrderReviewRepository")
 */
class OrderReview
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id_order_review", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id;

    /**
     * @ORM\Column(name="id_order", type="integer")
     */
    private int $orderId;

    /**
     * @ORM\Column(name="score", type="integer")
     */
    private int $score;

    /**
     * @ORM\Column(name="comment", type="string")
     */
    private string $comment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): OrderReview
    {
        $this->id = $id;

        return $this;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): OrderReview
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function setScore(int $score): OrderReview
    {
        $this->score = $score;

        return $this;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): OrderReview
    {
        $this->comment = $comment;

        return $this;
    }
}
