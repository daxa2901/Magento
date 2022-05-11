<?php 
$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('process/process_column'), 'sort_order', "DECIMAL(5,2) NOT NULL default '999.00' AFTER `sample_value`");

$installer->endSetup();