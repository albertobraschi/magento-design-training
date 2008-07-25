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
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml newsletter subscribers grid block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Block_Newsletter_Subscriber_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Constructor
     *
     * Set main configuration of grid
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('subscriberGrid');
        $this->setUseAjax(true);
        $this->setDefaultSort('id', 'desc');
    }

    /**
     * Prepare collection for grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceSingleton('newsletter/subscriber_collection')
            ->showCustomerInfo(true)
            ->addSubscriberTypeField()
            ->showStoreInfo();

        if($this->getRequest()->getParam('queue', false)) {
            $collection->useQueue(Mage::getModel('newsletter/queue')
                ->load($this->getRequest()->getParam('queue')));
        }

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

    	$this->addColumn('id', array(
    		'header'	=> Mage::helper('newsletter')->__('ID'),
       		'index'		=> 'subscriber_id'
    	));

    	$this->addColumn('email', array(
    		'header'	=> Mage::helper('newsletter')->__('Email'),
    		'index'		=> 'subscriber_email'
    	));

    	$this->addColumn('type', array(
    		'header'	=> Mage::helper('newsletter')->__('Type'),
    		'index'		=> 'type',
    		'type'      => 'options',
    		'options'   => array(
        		1  => Mage::helper('newsletter')->__('Guest'),
        		2  => Mage::helper('newsletter')->__('Customer')
    		)
    	));

    	$this->addColumn('firstname', array(
    		'header'	=> Mage::helper('newsletter')->__('Customer Firstname'),
    		'index'		=> 'customer_firstname',
    		'default'	=>	'----'
    	));

    	$this->addColumn('lastname', array(
    		'header'	=> Mage::helper('newsletter')->__('Customer Lastname'),
    		'index'		=> 'customer_lastname',
    		'default'	=>	'----'
    	));

        $this->addColumn('status', array(
    		'header'	=> Mage::helper('newsletter')->__('Status'),
    		'index'		=> 'subscriber_status',
    		'type'      => 'options',
    		'options'   => array(
        		Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE   => Mage::helper('newsletter')->__('Not activated'),
        		Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED   => Mage::helper('newsletter')->__('Subscribed'),
        		Mage_Newsletter_Model_Subscriber::STATUS_UNSUBSCRIBED => Mage::helper('newsletter')->__('Unsubscribed'),
    		)
    	));

    	$this->addColumn('website', array(
    		'header'	=> Mage::helper('newsletter')->__('Website'),
    		'index'		=> 'website_id',
    		'type'      => 'options',
    		'options'   => $this->_getOptions(
    		     Mage::getSingleton('core/website')->getCollection()->toOptionArray()
    		)
        ));

        $this->addColumn('group', array(
    		'header'	=> Mage::helper('newsletter')->__('Store'),
    		'index'		=> 'group_id',
    		'type'      => 'options',
    		'options'   => $this->_getOptions(
    		     Mage::getSingleton('core/store_group')->getCollection()->toOptionArray()
    		)
        ));

        $this->addColumn('store', array(
    		'header'	=> Mage::helper('newsletter')->__('Store View'),
    		'index'		=> 'store_id',
    		'type'      => 'options',
    		'options'   => $this->_getOptions(
    		     Mage::getSingleton('core/store')->getCollection()->toOptionArray()
    		 )
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('customer')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('customer')->__('XML'));
    	return parent::_prepareColumns();
    }

    protected function _getOptions($optionsArray)
    {
        $options = array();
        foreach ($optionsArray as $option) {
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('subscriber_id');
        $this->getMassactionBlock()->setFormFieldName('subscriber');

        $this->getMassactionBlock()->addItem('unsubscribe', array(
             'label'        => Mage::helper('newsletter')->__('Unsubscribe'),
             'url'          => $this->getUrl('*/*/massUnsubscribe')
        ));

        return $this;
    }

}// Class Mage_Adminhtml_Block_Newsletter_Subscriber_Grid END
