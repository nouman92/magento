<?php

class Fedobe_Customblocks_Block_About extends Mage_Adminhtml_Block_Abstract
{
    /**
     * Render fieldset html
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render()
    {
        $logopath	=	'http://cdn5.fedobe.com/wp-content/uploads/2013/08/fedobe-logo.png';
        $html = <<<HTML
<div style="background:url('$logopath') no-repeat scroll 10px 10px #A8C6CF;border:1px solid #CCCCCC;margin-bottom:10px;padding:10px 5px 5px 200px;">
     <p>
        <strong>PREMIUM and FREE MAGENTO TEMPLATES and EXTENSIONS</strong><br />
        <a href="http://fedobe.com" target="_blank">FEDOBE</a> offers a wide choice of nice-looking and easily editable free and premium Magento extensions. You can find free extensions for the extremely popular Magento eCommerce platform.<br />
    </p>
    <p>
        Our extensions on <a href="http://store.fedobe.com" target="_blank">store.fedobe.com</a><br />
        Should you have any questions email at <a href="mailto:support@fedobe.com">support@fedobe.com</a>
        <br/>
    </p>
</div>
HTML;
        return $html;
    }
}