<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @copyright  Copyright (c) 2006-2017 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = new Mage_Catalog_Model_Resource_Setup('core_setup');
$installer->startSetup();


$installer->addAttribute('catalog_product', 'discount_mode', array(
    'group'             => 'Prices',
    'attribute_set'     => 'Custom',
    'type'               => 'int',
    'label'              => 'Discount Mode',
    'input'              => 'select',
    'source'             => 'eav/entity_attribute_source_table',
    'required'           => false,
    'visible'            => true,
    'system'             => false,
    'validate_rules'     => 'a:0:{}',
    'position'           => 110,
    'admin_checkout'     => 1,
    'option'            => array('values' => array('Fixed', 'Percentage'))
));

