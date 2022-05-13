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
                'group'              => $data['group'],
                'user_defined'      => $data['user_defined'],
                'type'               => $data['type'],
                'label'              => $data['label'],
                'input'              => $data['input'],
                'source'             => $data['source'],
                'required'           => $data['required'],
            ));
        }
        $installer->endSetup();
        return true;
    }

}