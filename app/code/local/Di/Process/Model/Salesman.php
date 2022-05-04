<?php 
class Di_Process_Model_Salesman extends Di_Process_Model_Process_Abstract
{
	public function getIdentifier($row)
    {
        return $row['Email'];
    }

     public function prepareRow(&$row)
    {
        return [
            'firstName'  =>  $row['firstName'],   
            'lastName' =>  $row['lastName'],
            'mobile' => $row['mobile'],
            'email' => $row['email'],
            'percentage' => $row['percentage'],
        ];
    }

    public function validateRow($row)
    {
        return $row;
    }

    public function importEntries($entries)
    {
        $salesman = Mage::getModel('salesman/salesman');
        foreach ($entries as $key => $entry) 
        {
            $salesman->setData(json_decode($entry['data'], true));
            $salesman->created_at = date('Y-m-d H:i:s');
            $salesman->save();
        }
        return true;
    }

}