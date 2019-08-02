<?php

/**
 * Goldenscent, Dubai, United Arab Emirates
 * @category    Goldenscent
 * @author      Goldenscent Team <technical@goldenscent.com>
 * Copyright (c) 2019.  Goldenscent. (https://www.goldenscent.com)
 *
 * Class Goldenscent_Partner_Helper_Data
 */
class Goldenscent_Partner_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @var COOKIE_NAME
     */
    const COOKIE_NAME = 'gs_partner';

    /**
     * Set cookie
     * @param $partner
     */
    public function setCookie($partner)
    {
        Mage::getModel('core/cookie')->set(
            self::COOKIE_NAME, serialize($partner), 86400
        );
    }

    /***
     * Get cookie
     * @return mixed
     */
    public function getCookie()
    {
        return Mage::getModel('core/cookie')->get(self::COOKIE_NAME);
    }

    /**
     * Clear cookie
     * @throws Exception
     */
    public function clearCookie()
    {
        if ($cookie = Mage::getModel('core/cookie')->get(self::COOKIE_NAME)) {
            Mage::getModel('core/cookie')->delete(self::COOKIE_NAME);
        }
    }

    /***
     * Check partner exists in the cookie
     * @return bool
     */
    public function isPartner()
    {
        if($partnerCookie = Mage::getModel('core/cookie')->get(self::COOKIE_NAME)){
            return true;
        }
        return false;
    }
}