<?php

class Di_Category_Block_Adminhtml_Category_Index_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
    {
        parent::__construct();
        $this->setId('category_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('category')->__('category Info.'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
        'name' => Mage::helper('category')->__('Name'),
        'parent_id' => Mage::helper('category')->__('Parent Id'),
        'cost' => Mage::helper('category')->__('Cost'),
        'sku' => Mage::helper('category')->__('Sku'),
        'tax' => Mage::helper('category')->__('Tax'),
        'quantity' => Mage::helper('category')->__('Quantity'),
        'discount' => Mage::helper('category')->__('Discount'),
        'discount_Mode' => Mage::helper('category')->__('Discount_Mode'),
        'status' => Mage::helper('category')->__('status'),
        'createdAt' => Mage::helper('category')->__('created At'),
        'updatedAt' => Mage::helper('category')->__('updated At'),
        'content' => $this->getLayout()->createBlock('category/adminhtml_category_index_edit_tab_form')->toHtml(),));

        return parent::_beforeToHtml();
    }
}