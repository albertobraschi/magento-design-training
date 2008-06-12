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
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Mage
 * @package    Mage_Bundle
 * @copyright  Copyright (c) 2004-2007 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Bundle Extended Attribures Block
 *
 * @category    Mage
 * @package     Mage_Bundle
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Bundle_Block_Adminhtml_Catalog_Product_Edit_Tab_Attributes_Extend extends Mage_Adminhtml_Block_Catalog_Form_Renderer_Fieldset_Element
{
    const DYNAMIC = 0;
    const FIXED = 1;

    public function getElementHtml()
    {
        $elementHtml = parent::getElementHtml();

        $switchAttributeCode = $this->getAttribute()->getAttributeCode().'_type';
        $switchAttributeValue = $this->getProduct()->getData($switchAttributeCode);

        $html = '<select name="product[' . $switchAttributeCode . ']" id="' . $switchAttributeCode . '" type="select" class="required-entry select">
            <option value="">' . $this->__('-- Please Select --') . '</option>
            <option ' . ($switchAttributeValue == self::DYNAMIC ? 'selected' : '') . ' value="' . self::DYNAMIC . '">' . $this->__('Dynamic') . '</option>
            <option ' . ($switchAttributeValue == self::FIXED ? 'selected' : '') . ' value="' . self::FIXED . '">' . $this->__('Fixed') . '</option>
        </select>';

        $html .= $elementHtml;
        if ($this->getDisableChild()) {
            $html .= "<script type=\"text/javascript\">
                function " . $switchAttributeCode . "_change() {
                    if ($('" . $switchAttributeCode . "').value == " . self::DYNAMIC . ") {
                        $('" . $this->getAttribute()->getAttributeCode() . "').disabled = true;
                        $('" . $this->getAttribute()->getAttributeCode() . "').removeClassName('required-entry');
                    } else {
                        $('" . $this->getAttribute()->getAttributeCode() . "').disabled = false;
                        $('" . $this->getAttribute()->getAttributeCode() . "').addClassName('required-entry');
                    }
                }

                $('" . $switchAttributeCode . "').observe('change', " . $switchAttributeCode . "_change);
                " . $switchAttributeCode . "_change();
            </script>";
        }
        return $html;
    }

    public function getProduct()
    {
        if (!$this->getData('product')){
            $this->setData('product', Mage::registry('product'));
        }
        return $this->getData('product');
    }
}