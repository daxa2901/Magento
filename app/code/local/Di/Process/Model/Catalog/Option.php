<?php 
class Di_Process_Model_Catalog_Option extends Di_Process_Model_Process_Abstract
{
    public function getIdentifier($row)
    {
        return $row['option'];
    }

    public function prepareRowForJson(&$row)
    {
        return[
            'attribute_code' => $row['attribute_code'],
            'option_order' => $row['option_order'],
            'option' => $row['option'],
        ];
    }

    public function validateRow(&$row)
    {
        return $row;
    }

    public function importEntries($entries)
    {
        $installer = new Mage_Catalog_Model_Resource_Setup('core_setup');
        $installer->startSetup();
        foreach ($entries as $key => $entry) 
        {
            $data = json_decode($entry['data'], true);
            $attributeCode = $data['attribute_code'];
            $attribute = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_product',$attributeCode);

            if ($attribute->getId() && $attribute->getFrontendInput()=='select') 
            {
                $newOptions = array('attribute_id' => $attribute->getId(), 'values'=>array($data['option_order']=> $data['option']));
                $installer->addAttributeOption($newOptions);
            }
        }
        $installer->endSetup();
        return true;
    }
}