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

namespace PrestaShop\Module\ApiModule\ApiPlatform\Resources;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use PrestaShop\Module\ApiModule\ApiPlatform\State\HorseProcessor;
use PrestaShop\Module\ApiModule\ApiPlatform\State\HorseProvider;
use PrestaShop\Module\ApiModule\Entity\Horse as ObjectModelHorse;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    provider: HorseProvider::class,
    processor: HorseProcessor::class
)]
class Horse
{
    #[ApiProperty(identifier: true)]
    public ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    public ?string $name = null;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    public ?string $color = null;

    #[Assert\NotBlank]
    #[Assert\Type('float')]
    #[Assert\GreaterThanOrEqual(50)]
    public ?float $weight = null;

    public static function fromObjectModel(ObjectModelHorse $omHorse): self
    {
        $horse = new self();
        $horse->id = (int) $omHorse->id;
        $horse->name = $omHorse->name;
        $horse->color = $omHorse->color;
        $horse->weight = $omHorse->weight;

        return $horse;
    }
}
