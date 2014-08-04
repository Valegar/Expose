<?php

namespace Ideys\Content\Item;

use Ideys\Content\AbstractContent;

/**
 * Items prototype class.
 */
abstract class Item extends AbstractContent
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var integer
     */
    protected $expose_section_id;

    /**
     * @var string
     */
    protected $type;

    const ITEM_SLIDE    = 'slide';
    const ITEM_VIDEO    = 'video';
    const ITEM_PAGE     = 'page';
    const ITEM_POST     = 'post';
    const ITEM_FIELD    = 'field';
    const ITEM_PLACE    = 'place';

    /**
     * @var string
     */
    protected $category;

    /**
     * @var string
     */
    protected $tags;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $link;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var float
     */
    protected $latitude;

    /**
     * @var float
     */
    protected $longitude;

    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * @var string
     */
    protected $language;

    /**
     * @var \DateTime
     */
    protected $postingDate;

    /**
     * @var string
     */
    protected $author;

    /**
     * @var string
     */
    protected $published = '1';

    /**
     * @var integer
     */
    protected $hierarchy = 0;

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Item
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Item
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Return item types keys.
     *
     * @return array
     */
    public static function getTypes()
    {
        return array(
            self::ITEM_SLIDE,
            self::ITEM_VIDEO,
            self::ITEM_PAGE,
            self::ITEM_POST,
            self::ITEM_FIELD,
            self::ITEM_PLACE,
        );
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return Item
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set tags
     *
     * @param string $tags
     *
     * @return Item
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return string|null
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Item
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Item
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Item
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string|null
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return Item
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Item
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return Item
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return Item
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Test if Item has latitude and longitude defined.
     *
     * @return boolean
     */
    public function hasCoordinates()
    {
        return ($this->latitude  != null)
        and ($this->longitude != null);
    }

    /**
     * Set parameters
     *
     * @param array $parameters
     *
     * @return Item
     */
    public function setParameters($parameters)
    {
        $this->parameters = empty($parameters)
            ? array() : $parameters;

        return $this;
    }

    /**
     * Add a parameter
     *
     * @param string $key
     * @param string $value
     *
     * @return Item
     */
    public function addParameter($key, $value)
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    /**
     * Get parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return (array) $this->parameters;
    }

    /**
     * Get a parameter
     *
     * @param string $key
     *
     * @return mixed
     */
    public function retrieveParameter($key)
    {
        return array_key_exists($key, $this->getParameters())
            ? $this->parameters[$key] : null;
    }

    /**
     * Set language
     *
     * @param string $language
     *
     * @return Item
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set postingDate
     *
     * @param \DateTime $postingDate
     *
     * @return Item
     */
    public function setPostingDate(\DateTime $postingDate)
    {
        $this->postingDate = $postingDate;

        return $this;
    }

    /**
     * Get postingDate
     *
     * @return \DateTime
     */
    public function getPostingDate()
    {
        return $this->postingDate;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Item
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set published
     *
     * @param string $published
     *
     * @return Item
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return string
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Toggle item visibility.
     */
    public function toggle()
    {
        $this->published = !$this->published;
    }

    /**
     * Test if item is published.
     *
     * @return boolean
     */
    public function isPublished()
    {
        return ($this->published == '1');
    }

    /**
     * Set hierarchy
     *
     * @param string $hierarchy
     *
     * @return Item
     */
    public function setHierarchy($hierarchy)
    {
        $this->hierarchy = $hierarchy;

        return $this;
    }

    /**
     * Get hierarchy
     *
     * @return string
     */
    public function getHierarchy()
    {
        return $this->hierarchy;
    }
}
