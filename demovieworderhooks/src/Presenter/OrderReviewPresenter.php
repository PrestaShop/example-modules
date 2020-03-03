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

namespace PrestaShop\Module\DemoViewOrderHooks\Presenter;

use PrestaShop\Module\DemoViewOrderHooks\Entity\OrderReview;

class OrderReviewPresenter
{
    public function present(OrderReview $orderReview): array
    {
        return [
            'comment' => $orderReview->getComment(),
            'score' => $orderReview->getScore(),
        ];
    }
}
