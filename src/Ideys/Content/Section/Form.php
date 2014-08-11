<?php

namespace Ideys\Content\Section;

use Ideys\Content\Item;

/**
 * Form content manager.
 */
class Form extends Section implements SectionInterface
{
    /**
     * @var string
     */
    private $validationMessage;

    /**
     * {@inheritdoc}
     */
    public function getDefaultItems()
    {
        return $this->getFields();
    }

    /**
     * Get Field Items.
     *
     * @return array
     */
    public function getFields()
    {
        return $this->getItemsOfType(Item\Item::ITEM_FIELD);
    }

    /**
     * Test if Form has some Field Items.
     *
     * @return boolean
     */
    public function hasFields()
    {
        return $this->hasItemsOfType(Item\Item::ITEM_FIELD);
    }

    /**
     * {@inheritdoc}
     */
    public function countMainItems()
    {
        return count($this->getFields());
    }

    /**
     * @return string
     */
    public function getValidationMessage()
    {
        return $this->validationMessage;
    }

    /**
     * @param string $validationMessage
     *
     * @return Form
     */
    public function setValidationMessage($validationMessage)
    {
        $this->validationMessage = $validationMessage;

        return $this;
    }
}
