<?php

/**
 * Pages content manager.
 */
class ContentPage extends ContentPrototype implements ContentInterface
{
    public static function getParameters()
    {
        return array();
    }

    /**
     * Add a first page to section,
     * using section data.
     *
     * @param integer $id
     * @return array
     */
    public function addFirstPage($id)
    {
        $section = $this->findSection($id);

        $this->addItem(array(
                'expose_section_id' => $id,
                'type' => ContentFactory::CONTENT_PAGE,
                'title' => $section['title'],
                'description' => $section['description'],
            )
        );

        return $section;
    }
}
