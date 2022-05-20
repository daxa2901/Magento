<?php 
$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('product/product'), 'category_id', "int(11) UNSIGNED NULL DEFAULT NULL AFTER `product_id`");

$installer->run("
    ALTER TABLE {$this->getTable('product')}
    ADD CONSTRAINT  FOREIGN KEY (`category_id`)
    REFERENCES `{$this->getTable('category')}` (`category_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE;
    ");

$installer->endSetup();