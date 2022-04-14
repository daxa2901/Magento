<?php

class Di_Product_Block_Adminhtml_Product_Index_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
    {
        parent::__construct();
        $this->setId('product_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('product')->__('product Info.'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
        'name' => Mage::helper('product')->__('Name'),
        'price' => Mage::helper('product')->__('Price'),
        'cost' => Mage::helper('product')->__('Cost'),
        'sku' => Mage::helper('product')->__('Sku'),
        'tax' => Mage::helper('product')->__('Tax'),
        'quantity' => Mage::helper('product')->__('Quantity'),
        'discount' => Mage::helper('product')->__('Discount'),
        'discount_Mode' => Mage::helper('product')->__('Discount_Mode'),
        'status' => Mage::helper('product')->__('status'),
        'createdAt' => Mage::helper('product')->__('created At'),
        'updatedAt' => Mage::helper('product')->__('updated At'),
        'content' => $this->getLayout()->createBlock('product/adminhtml_product_index_edit_tab_form')->toHtml(),));

        return parent::_beforeToHtml();
    }
}