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
