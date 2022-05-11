<?php 
class Di_Process_Model_Category extends Di_Process_Model_Process_Abstract
{
    protected $categories= [];
    protected $categoriesPath= [];
	public function getIdentifier($row)
    {
        return $row['name'];
    }

    public function prepareRowForJson(&$row)
    {
        return [
            'path' => $row['path'],
            'name'  =>  $row['name'],   
            'parent_name' =>  $row['parent_name'],
            'description' => $row['description'],
            'link' => $row['link'],
            'status' => $row['status'],
        ];
    }

    public function validateRow(&$row)
    {
        if (!$this->categoriesPath) 
        {
            $this->categoriesPath = array_flip(Mage::getModel('category/category')->getCategoryToPath());
            $this->categoriesPath['Root'] = null;
        }
        $this->validateParent($row);
        $this->validateName($row);
        $row['name'] = $this->getName($row);
        $row['parent_name'] = $this->getParent($row);
        $this->categoriesPath[$row['path']] = 0;
        return $row;
        // $this->validateDuplicateCategory($row);
        // return $row;
    }

    public function validateParent($row)
    {
        $parent = $this->getParent($row);
        if (!array_key_exists($parent,$this->categoriesPath)) 
        {
        // echo $parent;
        // echo "<br>";
            throw new Exception("Parent Category does not exists.", 1);
        }
        return true;
    }

    public function validateName($row)
    {
        if (array_key_exists($row['path'],$this->categoriesPath)) 
        {
            throw new Exception("Category already exists.", 1);
        }
        return true;
    }

    public function getName($row)
    {
        return end(explode('/',$row['path']));
    }

    public function getParent($row)
    {
        $explodePath = explode('/',$row['path']);
        if (count($explodePath) == 1) 
        {
            return 'Root';
        }
        return implode('/',array_slice($explodePath,0,-1));
    }

    public function validateDuplicateCategory($row)
    {
        if (!$this->categories) 
        {
            $model = Mage::getModel('category/category');
            $select = $model->getCollection()
                      ->getSelect()
                      ->reset(Zend_Db_Select::COLUMNS)
                      ->columns(['name','category_id']);
            $categories = $model->getResource()->getReadConnection()->fetchPairs($select);
            $this->categories = $categories;
        }
        if (array_key_exists($row['path'],$this->categories)) 
        {
            throw new Exception("Category already exists.", 1);
        }
        return true;
    }

    public function importEntries($entries)
    {
        if (!$this->categoriesPath) 
        {
            $this->categoriesPath = array_flip(Mage::getModel('category/category')->getCategoryToPath());
            $this->categoriesPath['Root'] = null;
        }
        
        $category = Mage::getModel('category/category');
        foreach ($entries as $key => $entry) 
        {
            $category->setData(json_decode($entry['data'], true));
            $category->parent_id = $this->categoriesPath[$category->parent_name];
            $category->status = ($category->status == 'Active') ? 1 : 2;
            unset($category->parent_name);
            $categoriesPath= $category->path;
            unset($category->path);
            $category->created_at = date('Y-m-d H:i:s');
            $category->save();

            $categoryId = $category->getId();
            $this->categoriesPath[$categoriesPath] = $categoryId;
            $parent = Mage::getModel('category/category')->load($category->parent_id);
            if (condition) {
                // code...
            }
            $path = (!$parent->path)? $categoryId : $parent->path.'/'.$categoryId;
            $category = Mage::getModel('category/category')->load($categoryId);
            $category->path = $path;
            $category->save();
        }
        return true;
    }
}