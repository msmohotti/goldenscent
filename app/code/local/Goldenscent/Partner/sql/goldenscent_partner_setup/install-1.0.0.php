<?php
/**
 * Goldenscent, Dubai, United Arab Emirates
 * @category    Goldenscent
 * @author      Goldenscent Team <technical@goldenscent.com>
 * Copyright (c) 2019.  Goldenscent. (https://www.goldenscent.com)
 *
 * @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;

$installer->startSetup();

$this->getConnection()->addColumn($installer->getTable('sales/order'),
    'partner',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_VARCHAR,
        'length'    => 255,
        'nullable' => true,
        'default' => null,
        'comment' => 'Partner name'
    )
);

$installer->endSetup();