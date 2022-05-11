<?php 
class Di_Process_Model_Product extends Di_Process_Model_Process_Abstract
{
	public function getIdentifier($row)
    {
        return $row['sku'];
    }

     public function prepareRowForJson(&$row)
    {
        return [
            'name'  =>  $row['name'],   
            'price' =>  $row['price'],
            'quantity' => $row['quantity'],
            'sku' => $row['sku'],
            'tax' => $row['tax'],
            'cost' => $row['cost'],
            'discount' => $row['discount'],
            'discount_mode' => ($row['discount_mode']=='Fixed')? 1 : 2,
            'status' => ($row['status'] == 'Active')? 1 : 2,
        ];
    }

    public function validateRow($row)
    {
        return $row;
    }

    public function importEntries($entries)
    {
        $product = Mage::getModel('product/product');
        foreach ($entries as $key => $entry) 
        {
            $product->setData(json_decode($entry['data'], true));
            $product->created_at = date('Y-m-d H:i:s');
            $product->save();
        }
        return true;
    }

}