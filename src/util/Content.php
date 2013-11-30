<?php

use Doctrine\DBAL\Connection;

/**
 * App content manager.
 */
class Content
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $db;

    /**
     * @var string
     */
    private $language;

    /**
     * @var array
     */
    private $sections = array();

    const CONTENT_GALLERY   = 'gallery';
    const CONTENT_VIDEO     = 'video';
    const CONTENT_PAGE      = 'page';
    const CONTENT_FORM      = 'form';
    const CONTENT_DIR       = 'dir';

    /**
     * Constructor: inject required Silex dependencies.
     *
     * @param array $app
     */
    public function __construct(Connection $connection)
    {
        $this->db = $connection;
        $this->language = 'fr';
    }

    /**
     * Return sections.
     *
     * @return array
     */
    public function findSections()
    {
        if (!empty($this->sections)) {
            return $this->sections;
        }

        $sql = "SELECT s.*, t.title, t.description
                FROM expose_section AS s
                LEFT JOIN expose_section_trans AS t
                ON t.expose_section_id = s.id
                WHERE t.language = ?
                ORDER BY s.hierarchy ASC";
        $sections = $this->db->fetchAll($sql, array($this->language));

        // Use sql primary keys as array keys and add sections tree
        foreach ($sections as $section) {
            $this->sections[$section['id']] = $section + array('sections' => array());
        }

        // Generate tree structure from raw datas
        foreach ($this->sections as $id => $section) {
            $parentSectionId = $section['expose_section_id'];
            if ($parentSectionId > 0) {
                $this->sections[$parentSectionId]['sections'][] = $section;
                unset($this->sections[$id]);
            }
        }

        return $this->sections;
    }

    /**
     * Return a section.
     *
     * @return array
     */
    public function findSection($slug)
    {
        $sql = "SELECT s.*, t.title, t.description
                FROM expose_section AS s
                LEFT JOIN expose_section_trans AS t
                ON t.expose_section_id = s.id
                WHERE s.slug = ?
                AND t.language = ?
                ORDER BY s.hierarchy ASC";
        $section = $this->db->fetchAssoc($sql, array($slug, $this->language));

        return $section;
    }

    /**
     * Create a new section.
     */
    public function addSection($type, $title, $description, $dirId, $language, $active = false)
    {
        $dirId = is_numeric($dirId) ? (int)$dirId : null;
        $this->db->insert('expose_section', array(
            'expose_section_id' => $dirId,
            'type' => $type,
            'slug' => slugify($title),
            'active' => $active,
        ));

        $sectionId = $this->db->lastInsertId();
        $this->db->insert('expose_section_trans', array(
            'expose_section_id' => $sectionId,
            'title' => $title,
            'description' => $description,
            'language' => $language,
        ));
    }

    /**
     * Return content types keys
     *
     * @return array
     */
    public static function getContentTypes()
    {
        return array(
            self::CONTENT_GALLERY,
            self::CONTENT_VIDEO,
            self::CONTENT_PAGE,
            self::CONTENT_FORM,
            self::CONTENT_DIR,
        );
    }

    public static function getContentTypesChoice()
    {
        $keys = static::getContentTypes();
        $values = array_map(function($item){
            return 'content.'.$item;
        }, $keys);
        return array_combine($keys, $values);
    }
}
