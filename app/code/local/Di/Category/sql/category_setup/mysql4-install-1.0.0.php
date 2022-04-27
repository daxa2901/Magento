<?php

$installer = $this;
$installer->startSetup();
    $installer->run("
    DROP TABLE IF EXISTS {$this->getTable('category')};
    CREATE TABLE {$this->getTable('category')} (
      `category_id` int(11) unsigned NOT NULL auto_increment,
      `name` varchar(64) NOT NULL default '',
      `path` varchar(64) NOT NULL default '',
      `parent_id` int(11) unsigned NULL ,
      `status` varchar(64) NOT NULL default '2',
      `created_at` DATETIME NOT NULL ,
      `updated_at` DATETIME  NULL ,
      PRIMARY KEY (`category_id`)
    )
     ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

    $installer->endSetup();