<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_ . 'demowsextend/src/Entity/Article.php';

class DemoWsExtend extends Module
{
    public function __construct()
    {
        $this->name = 'demowsextend';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'PrestaShop';
        $this->need_instance = 0;
        $this->secure_key = Tools::encrypt($this->name);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->getTranslator()->trans('Extend WS demo module', array(), 'Modules.DemoWsExtend.Admin');
    }

    public function install()
    {
        return parent::install() &&
            $this->installDB() && // Create tables in the DB
            $this->registerHook('addWebserviceResources'); // Register the module to the hook
    }

    public function installDB()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.Article::$definition['table'].'` (
            `id_article` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `type` varchar(255),
            `date_add` datetime NOT NULL,
            `date_upd` datetime NOT NULL,
            PRIMARY KEY  (`id_article`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci';

        $sql_lang = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.Article::$definition['table'].'_lang` (
            `id_article` int(10) unsigned NOT NULL,
            `id_lang` int(10) unsigned NOT NULL,
            `title` varchar(255),
            `content` text NOT NULL,
            `meta_title` varchar(255) NOT NULL,
            PRIMARY KEY  (`id_article`, `id_lang`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci';

        return Db::getInstance()->execute($sql) && 
            Db::getInstance()->execute($sql_lang);
    }

    public function hookAddWebserviceResources($params)
    {
        return [
            'articles' => [
                'description' => 'Blog articles', // The description for those who access to this resource through WS
                'class' => 'Article', // The classname of your Entity
                //'forbidden_method' => array('DELETE') // Optional, if you want to forbid some methods
            ]
        ];
    }
}