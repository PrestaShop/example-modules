<?php

class Article extends ObjectModel
{
    public $title;
    public $type;
    public $content;
    public $meta_title;

    public $date_add;
    public $date_upd;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => 'article',
        'primary' => 'id_article',
        'multilang' => true,
        'fields' => [
            'type' => ['type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'required' => true, 'size' => 255],
        
            // Lang fields
            'title' => ['type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => true, 'size' => 255],
            'content' => ['type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 4000],
            'meta_title' => ['type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 255],

            'date_add' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
            'date_upd' => ['type' => self::TYPE_DATE, 'validate' => 'isDate']
        ]
    ];

    protected $webserviceParameters = [
        'objectNodeName' => 'article',
        'objectsNodeName' => 'articles',
        'fields' => [
            'title' => ['required' => true],
            'type' => ['required' => true],
            'content' => [],
            'meta_title' => []
        ]
    ];
}