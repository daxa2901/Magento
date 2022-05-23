<?php 
$installer = $this;
$installer->startSetup();


$installer->addAttribute(Di_Vendor_Model_Resource_Vendor::ENTITY, 'first_name', array(
    'group' => 'General',
    'input' => 'text',
    'type' => 'varchar',
    'label' => 'First Name',
    'backend' => '',
    'visible' => 1,
    'required' => 0,
    'user_defined' => 1,
    'searchable' => 1,
    'filterable' => 0,
    'comparable' => 1,
    'visible_on_front' => 1,
    'visible_in_advanced_search' => 0,
    'is_html_allowed_on_front' => 1,
    'global' => 1,
));

$installer->addAttribute(Di_Vendor_Model_Resource_Vendor::ENTITY, 'last_name', array(
    'group' => 'General',
    'input' => 'text',
    'type' => 'varchar',
    'label' => 'Last Name',
    'backend' => '',
    'visible' => 1,
    'required' => 0,
    'user_defined' => 1,
    'searchable' => 1,
    'filterable' => 0,
    'comparable' => 1,
    'visible_on_front' => 1,
    'visible_in_advanced_search' => 0,
    'is_html_allowed_on_front' => 1,
    'global' => 1,
));

$installer->addAttribute(Di_Vendor_Model_Resource_Vendor::ENTITY, 'status', array(
    'group' => 'General',
    'input' => 'select',
    'type' => 'int',
    'label' => 'Status',
    'backend' => '',
    'visible' => 1,
    'required' => 0,
    'user_defined' => 1,
    'searchable' => 1,
    'filterable' => 0,
    'comparable' => 1,
    'visible_on_front' => 1,
    'source'   => 'eav/entity_attribute_source_table',
    'option'  => array('values' => array('Enable', 'Disable')),
    'visible_in_advanced_search' => 0,
    'is_html_allowed_on_front' => 1,
    'global' => 1,
));

     