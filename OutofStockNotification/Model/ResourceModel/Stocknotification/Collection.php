<?php
/**
 * @category Mageants Out Of Stock Notification
 * @package Mageants_OutOfStockNotification
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\OutofStockNotification\Model\ResourceModel\Stocknotification;

/** 
 * Stocknotification model collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	/**
     * init constructor
     */
  	protected function _construct()
    {
        $this->_init('Mageants\OutofStockNotification\Model\Stocknotification', 'Mageants\OutofStockNotification\Model\ResourceModel\Stocknotification');
    }
}