<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
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
        return $packageLocation->getDate() !== null;
    }
}
