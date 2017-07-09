<?php
class Fedobe_Customblocks_Model_Words
{
    public function toOptionArray()
    {
        return array(
            array('value'=>1, 'label'=>Mage::helper('customblocks')->__('Yes')),
            array('value'=>2, 'label'=>Mage::helper('customblocks')->__('No')),     
        );
    }

}
?>