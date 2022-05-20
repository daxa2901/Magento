<?php 
class Di_Process_Model_Product extends Di_Process_Model_Process_Abstract
{
	protected $categories = [];
    protected $statues = [];
    public function getIdentifier($row)
    {
        return $row['sku'];
    }

     public function prepareRowForJson(&$row)
    {
        return $row;
        [
            'name'  =>  $row['name'],   
            'category_id'  =>  $row['category_id'],   
            'price' =>  $row['price'],
            'quantity' => $row['quantity'],
            'sku' => $row['sku'],
            'tax' => $row['tax'],
            'cost' => $row['cost'],
            'discount' => $row['discount'],
            'discount_mode' => ($row['discount_mode']=='Fixed')? 1 : 2,
            'status' => $row['status']
        ];
    }

    public function validateRow(&$row)
    {
        $row['category_id'] = $this->validateCategory($row);
        $row['status'] = $this->validateStatus($row);
        return $row;
    }

    public function validateCategory($row)
    {
        if (!$this->categories) 
        {
            $this->categories = array_flip(Mage::getModel('category/category')->getCategoryToPath());
        }
        if (!array_key_exists($row['category_id'],$this->categories)) 
        {
            throw new Exception("Category does not exists.", 1);
        }
        return $this->categories[$row['category_id']];
    }

    public function validateStatus($row)
    {
        if (!$this->statues) 
        {
            $this->statues = array_flip(Mage::getModel('product/product')->getStatus());
        }
        if (!array_key_exists($row['status'],$this->statues)) 
        {
            throw new Exception("status invalid.", 1);
        }
        return $this->statues[$row['status']];
    }
    
    public function validateDiscountMode($row)
    {
        if (!$this->statues) 
        {
            $this->statues = array_flip($this->getModel('product/process')->getDiscountMode());
        }

        if (!array_key_exists($row['discount_mode'],$this->statues)) 
        {
            throw new Exception("discount Mode invalid.", 1);
        }
        return $this->statues[$row['discount_mode']];
    }

    public function importEntries($entries)
    {
        $product = Mage::getModel('product/product');
        $adapter = $product->getResource()->getReadConnection();
        foreach ($entries as $key => $entry) 
        {
            $product->setData(json_decode($entry['data'], true));
            $product->created_at = date('Y-m-d H:i:s');
            $adapter->insertOnDuplicate('product',$product->getData());
        }
        return true;
    }

}