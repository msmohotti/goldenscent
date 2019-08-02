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
        //if only parameter exists set cookies
        if ($partner != null) {
            Mage::helper('goldenscent_partner')->setCookie($partner);
        }
    }
}