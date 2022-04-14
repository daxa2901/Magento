<?php
$installer = $this;
$installer->startSetup();
$installer->run("
	-- DROP TABLE IF EXISTS {$this->getTable('product')};
	CREATE TABLE {$this->getTable('product')} (
	`product_id` int(11) unsigned NOT NULL auto_increment,
	`name` varchar(64) NOT NULL ,
	`price` float NOT NULL,
	`quantity` int(11) NOT NULL,
	`sku` varchar(255) NOT NULL UNIQUE,
	`tax` decimal(10,2) NOT NULL,
	`cost` float NOT NULL,
	`discount` float NOT NULL,
	`discount_mode` tinyint(4) NOT NULL default '2',
	`status` varchar(64) NOT NULL default '2',
  	`created_at` DATETIME NOT NULL ,
  	`updated_at` DATETIME  NULL ,
  	PRIMARY KEY (`product_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	");
$installer->endSetup();