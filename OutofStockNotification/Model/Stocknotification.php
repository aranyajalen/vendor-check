<?php
/**
 * @category Mageants Out Of Stock Notification
 * @package Mageants_OutOfStockNotification
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\OutofStockNotification\Model;

use Magento\Framework\Exception\LocalizedException as CoreException;

/**
 * Stocknotification Model class
 */

class Stocknotification extends \Magento\Framework\Model\AbstractModel
{
	/**
	 * init Model class
	 */
  	protected function _construct()
    {
        $this->_init('Mageants\OutofStockNotification\Model\ResourceModel\Stocknotification');
    }
}