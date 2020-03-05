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
        $this->db->insert('order_signature', [
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
