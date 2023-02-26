CREATE TABLE IF NOT EXISTS `PREFIX_demoproductform_custom_product`
(
    `id` INT(11) UNSIGNED NOT NULL UNIQUE,
    `custom_field`  VARCHAR(255) NOT NULL DEFAULT '',
    `custom_price` decimal(20, 6) NOT NULL DEFAULT '0.000000',
    `date_add`   DATETIME         NOT NULL,
    `date_upd`   DATETIME         NOT NULL,
    PRIMARY KEY(`id`)
) ENGINE = ENGINE_TYPE DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_demoproductform_custom_product_attribute`
(
    `id` INT(11) UNSIGNED NOT NULL UNIQUE,
    `custom_field`  VARCHAR(255) NOT NULL DEFAULT '',
    `custom_price` decimal(20, 6) NOT NULL DEFAULT '0.000000',
    `date_add`   DATETIME         NOT NULL,
    `date_upd`   DATETIME         NOT NULL,
    PRIMARY KEY(`id`)
) ENGINE = ENGINE_TYPE DEFAULT CHARSET = utf8;
