<?php

/**
 * Goldenscent, Dubai, United Arab Emirates
 * @category    Goldenscent
 * @author      Goldenscent Team <technical@goldenscent.com>
 * Copyright (c) 2019.  Goldenscent. (https://www.goldenscent.com)
 *
 * Partner sales capture observer
 * Class Goldenscent_Partner_Model_Observer
 */
class Goldenscent_Partner_Model_Observer extends Varien_Event_Observer
{
    /**
     * @param Varien_Event_Observer $observer
     * Set cookies before controller load
     */
    public function setCookie(Varien_Event_Observer $observer)
    {
        $partner = Mage::app()->getRequest()->getParam('partner');
        //Check the parameter exists in the url
        if ($partner != null) {
            Mage::helper('goldenscent_partner')->setCookie($partner);
        }
    }

    /***
     * Create invoices and shipments
     * @param $observer
     */
    public function splitOrder(Varien_Event_Observer $observer)
    {
        $helper = Mage::helper('goldenscent_partner');
        //If cookie is available split invoices & shipments
        if ($helper->isPartner()) {
            $orderIds = $observer->getEvent()->getOrderIds();
            if (count($orderIds)) {
                $order = Mage::getModel('sales/order')->load($orderIds[0]);
                $orderItem = new Goldenscent_Partner_Model_OrderItems($order);
                $orderItem->split();
                //Remove the cookie after successful order
                $helper->clearPartnerCookie();
            }
        }
    }

    /**
     * Adds column to admin order grid
     *
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function appendCustomColumn(Varien_Event_Observer $observer)
    {
        $block = $observer->getBlock();
        if (!isset($block)) {
            return $this;
        }

        if ($block->getType() == 'adminhtml/sales_order_grid') {
            /* @var $block Mage_Adminhtml_Block_Customer_Grid */
            $block->addColumnAfter('partner', array(
                'header' => 'Partner Name',
                'type' => 'text',
                'index' => 'partner_name',
            ), 'shipping_name');
        }
    }

    /***
     * Set value for custom grid attribute partner
     * @param Varien_Event_Observer $observer
     */
    public function salesOrderGridCollectionLoadBefore(Varien_Event_Observer $observer)
    {
        $collection = $observer->getOrderGridCollection();
        $select = $collection->getSelect();
        $select->joinLeft(array('order' => $collection->getTable('sales/order')),
            'order.entity_id=main_table.entity_id', array('partner' => 'partner'));


    }
}