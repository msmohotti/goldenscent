<?php

/**
 * Goldenscent, Dubai, United Arab Emirates
 * @category    Goldenscent
 * @author      Goldenscent Team <technical@goldenscent.com>
 * Copyright (c) 2019.  Goldenscent. (https://www.goldenscent.com)
 *
 * Split order items to two parts
 * Class Goldenscent_Partner_Model_OrderItems
 */
class Goldenscent_Partner_Model_OrderItems extends Mage_Core_Model_Abstract
{
    /***
     * @var Mage_Sales_Model_Order
     */
    protected $order;

    /***
     * Goldenscent_Partner_Model_OrderItems constructor.
     *
     * @param Mage_Sales_Model_Order $order
     */
    public function __construct(Mage_Sales_Model_Order $order)
    {
        $this->order = $order;
        parent::_construct();
    }

    /***
     * Create multiple invoices
     */
    public function split()
    {
        $orderItems = $this->order->getAllItems();
        $splitItems = [];
        $this->splitHalf($orderItems, $splitItems);
        foreach ($splitItems as $splitItem) {
            if (count($splitItem)) {
                $this->createInvoices($splitItem);
                $this->createShipments($splitItem);
            }
        }
    }

    /***
     * Split order items to half
     * @param $orderItems
     * @param $splitItems
     */
    protected function splitHalf($orderItems, &$splitItems)
    {
        $totalOrdered = count($orderItems);
        $lenOfArray_1 = ceil($totalOrdered / 2);
        $itemSetOne = [];
        $itemSetTwo = [];
        $counter = 0;
        foreach ($orderItems as $orderItem) {
            $invoiceItem = Mage::getModel('sales/convert_order')
                ->itemToInvoiceItem($orderItem);
            if ($lenOfArray_1 > $counter) {
                $itemSetOne[$invoiceItem->getOrderItemId()]
                    = $orderItem->getQtyOrdered();
            } else {
                $itemSetTwo[$invoiceItem->getOrderItemId()]
                    = $orderItem->getQtyOrdered();
            }
            $counter++;
        }
        $splitItems = [1 => $itemSetOne, 2 => $itemSetTwo];
    }

    /***
     * @param $invoiceQty
     * @throws Exception
     */
    protected function createInvoices($invoiceQty)
    {
        try {
            $invoice = Mage::getModel('sales/service_order', $this->order)->prepareInvoice($invoiceQty);
            $partner = Mage::helper('goldenscent_partner')->getCookie();
            $invoice->register();
            $invoice->setEmailSent(false);
            $invoice->getOrder()->setCustomerNoteNotify(false);
            $invoice = Mage::getModel('core/resource_transaction')
                ->addObject($invoice)
                ->addObject($invoice->getOrder())
                ->save();

            $this->order->addStatusHistoryComment('Invoice split - Order was referred by a partner', false);
            //Save partner to the order
            $this->order->setPartnerName($partner);
            $this->order->save();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param $shipQty
     * @throws Exception
     */
    protected function createShipments($shipQty)
    {
        try {
            $shipment = Mage::getModel('sales/service_order', $this->order)->prepareShipment($shipQty);
            $this->order->addStatusHistoryComment('Shipment split - Order was referred by a partner', false);
            $shipment->save();
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}