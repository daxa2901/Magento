<?php 
$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('process/process_column'), 'sample_value', "varchar(255) NOT NULL default '' AFTER `name`");

$installer->getConnection()->addColumn($installer->getTable('process/process_column'), 'default_value', "varchar(255) NULL default NULL AFTER `sample_value`");

$installer->endSetup();