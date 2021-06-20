CREATE TABLE IF NOT EXISTS `_DB_PREFIX_demoproductform_custom_product`
(
    `id` INT(11) UNSIGNED NOT NULL UNIQUE,
    `custom_field`  VARCHAR(255) NOT NULL DEFAULT '',
    `date_add`   DATETIME         NOT NULL,
    `date_upd`   DATETIME         NOT NULL,
    PRIMARY KEY(`id`)
) ENGINE = _MYSQL_ENGINE_ DEFAULT CHARSET = utf8;
