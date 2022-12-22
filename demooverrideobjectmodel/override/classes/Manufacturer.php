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

class Manufacturer extends ManufacturerCore
{
    /**
     * @var string
     */
    public $code;

    public function __construct(
        $id = null,
        $idLang = null
    ) {
        self::$definition['fields']['code'] = ['type' => self::TYPE_STRING, 'size' => 64];
        parent::__construct($id, $idLang);
    }
}
