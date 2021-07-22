<?php
/**
 * @category Mageants Out Of Stock Notification
 * @package Mageants_OutOfStockNotification
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\OutofStockNotification\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException as CoreException;
/**
 * Stocknotification Model class
 */
class Stocknotification extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	/**
	 * init Model class
	 */
  	protected function _construct()
    {
        $this->_init('subscribe_product_notification', 'entity_id');
    }
}