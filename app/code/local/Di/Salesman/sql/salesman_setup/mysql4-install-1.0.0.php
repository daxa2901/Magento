<?php
    $installer = $this;
    $installer->startSetup();
    $installer->run("
    -- DROP TABLE IF EXISTS {$this->getTable('salesman')};
    CREATE TABLE {$this->getTable('salesman')} (
      `salesman_id` int(11) unsigned NOT NULL auto_increment,
      `firstName` varchar(64) NOT NULL default '',
      `lastName` varchar(64) NOT NULL default '',
      `email` varchar(64) NOT NULL default '',
      `mobile` varchar(10) NOT NULL default '',
      `percentage` float NOT NULL ,
      `status` varchar(64) NOT NULL default '2',
      `createdAt` DATETIME NOT NULL ,
      `updatedAt` DATETIME  NULL ,
      PRIMARY KEY (`salseman_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    $installer->endSetup();