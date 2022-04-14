<?php

class Di_Salesman_Block_Adminhtml_Salesman_Index_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
    {
        parent::__construct();
        $this->setId('salesman_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('salesman')->__('salesman Info.'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
        'firstName' => Mage::helper('salesman')->__('First Name'),
        'lastName' => Mage::helper('salesman')->__('Last Name'),
        'email' => Mage::helper('salesman')->__('Email'),
        'mobile' => Mage::helper('salesman')->__('Mobile'),
        'status' => Mage::helper('salesman')->__('Status'),
        'createdAt' => Mage::helper('salesman')->__('created At'),
        'updatedAt' => Mage::helper('salesman')->__('updated At'),
        'content' => $this->getLayout()->createBlock('salesman/adminhtml_salesman_index_edit_tab_form')->toHtml(),));

        return parent::_beforeToHtml();
    }
}