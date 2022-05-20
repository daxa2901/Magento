<?php 
class Di_Process_Model_Catalog_Product extends Di_Process_Model_Process_Abstract
{
    public function getIdentifier($row)
    {
        return $row['name'];
    }

    public function prepareRowForJson(&$row)
    {
        return
        [
            'name'  =>  $row['name'],   
            'group'  =>  $row['group'],   
            'attribute_set' => $row['attribute_set'],
            'user_defined' => $row['user_defined'],
            'type' => $row['type'],
            'input' => $row['input'],
            'label' => $row['label'],
            'source' => $row['source'],
            'required' => $row['required']
        ];
    }

    public function validateRow(&$row)
    {
        $row['user_defined'] = ($row['user_defined'] == 1) ? true : false; 
        return $row;
    }

    public function importEntries($entries)
    {

        $installer = new Mage_Catalog_Model_Resource_Setup('core_setup');
        $installer->startSetup();

        foreach ($entries as $key => $entry) 
        {
            $data = json_decode($entry['data'], true);
            $installer->addAttribute('catalog_product', $data['name'], array(
                'type'               => $data['type'],
                'label'              => $data['label'],
                'input'              => $data['input'],
                'source'             => $data['source'],
                'user_defined'       => $data['user_defined'],
                'required'           => $data['required'],
            ));
            $attributeId = $installer->getAttributeId(Mage_Catalog_Model_Product::ENTITY,$data['name']);
            $attributeSetId = $installer->getAttributeSetId(Mage_Catalog_Model_Product::ENTITY,$data['attribute_set']);
            $attributeGroupId = $installer->getAttributeGroup(Mage_Catalog_Model_Product::ENTITY,$attributeSetId,$data['group']);
            $installer->addAttributeToSet(
                Mage_Catalog_Model_Product::ENTITY,
                $attributeSetId,
                $attributeGroupId,
                $attributeId
            );
        }
        $installer->endSetup();
        return true;
    }

}