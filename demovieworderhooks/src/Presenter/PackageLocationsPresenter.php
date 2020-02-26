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

namespace PrestaShop\Module\DemoViewOrderHooks\Presenter;

use PrestaShop\Module\DemoViewOrderHooks\Entity\PackageLocation;
use PrestaShopBundle\Translation\TranslatorAwareTrait;

class PackageLocationsPresenter
{
    use TranslatorAwareTrait;

    /**
     * @param PackageLocation[] $packageLocations
     *
     * @return array
     */
    public function present(array $packageLocations): array
    {
        $presented = [];

        foreach ($packageLocations as $packageLocation) {
            $hasArrived = $this->hasPackageArrivedAtLocation($packageLocation);

            $presented[] = [
                'location' => $packageLocation->getLocation(),
                'date' => $this->formatDate($packageLocation),
                'hasArrived' => $hasArrived,
            ];
        }

        return $presented;
    }

    private function formatDate(PackageLocation $packageLocation): string
    {
        if (!$this->hasPackageArrivedAtLocation($packageLocation)) {
            return $this->trans('Not arrived', [], 'Module.Demovieworderhooks.Admin');
        }

        return $packageLocation->getDate()->format('l, M j');
    }

    private function hasPackageArrivedAtLocation(PackageLocation $packageLocation): bool
    {
        return $packageLocation->getDate()->format('Y') > 0;
    }
}
