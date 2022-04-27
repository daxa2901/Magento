<?php
$installer = $this;
$installer->startSetup();
$installer->run("
    ALTER TABLE {$this->getTable('category')}
    ADD CONSTRAINT `Category_Parent` FOREIGN KEY `Category_Fk` (`parent_id`)
    REFERENCES `{$this->getTable('category')}` (`category_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE;
    ");
$installer->endSetup();