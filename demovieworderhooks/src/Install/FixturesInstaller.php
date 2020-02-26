<?php
/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
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
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

declare(strict_types=1);

namespace PrestaShop\Module\DemoViewOrderHooks\Install;

use Configuration;
use Country;
use DateTime;
use Db;
use joshtronic\LoremIpsum;
use Order;

/**
 * Installs data fixtures for the module.
 */
class FixturesInstaller
{
    /**
     * @var LoremIpsum
     */
    private $loremIpsum;

    /**
     * @var Db
     */
    private $db;

    public function __construct(Db $db)
    {
        $this->loremIpsum = new LoremIpsum();
        $this->db = $db;
    }

    public function install(): void
    {
        $orderIds = Order::getOrdersIdByDate('2000-01-01', '2100-01-01');

        foreach ($orderIds as $orderId) {
            $this->insertSignature($orderId);
            $this->insertOrderReview($orderId);

            $order = new Order($orderId);

            if ($order->hasBeenShipped()) {
                $this->insertPackageLocations($orderId);
            }
        }
    }

    private function insertOrderReview(int $orderId): void
    {
        $this->db->insert('order_review', [
            'id_order' => $orderId,
            'score' => rand(0, 3),
            'comment' => $this->loremIpsum->sentence(),
        ]);
    }

    private function insertSignature(int $orderId): void
    {
        $this->db->insert('signature', [
            'id_order' => $orderId,
            'filename' => 'john_doe.png',
        ]);
    }

    private function insertPackageLocations(int $orderId): void
    {
        $numberOfLocations = rand(4, 6);
        $countries = Country::getCountries(Configuration::get('PS_LANG_DEFAULT'));
        $numberOfCountries = count($countries);

        for ($i = 0; $i < $numberOfLocations; $i++) {
            // Last location will not have a date
            $date = $i === 0 ? null : (new DateTime('-'.$i.' days'))->format('Y-m-d H:i:s');

            $this->db->insert('package_location', [
                'id_order' => $orderId,
                'location' => $countries[rand(0, $numberOfCountries - 1)]['name'],
                'position' => $numberOfLocations - $i,
                'date' => $date,
            ]);
        }
    }
}
