<?php
/**
 * @category Mageants Out Of Stock Notification
 * @package Mageants_OutOfStockNotification
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\OutofStockNotification\Model\Customer\Source;

use Magento\Framework\Module\Manager as ModuleManager;

class Sender implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var ModuleManager
     */
    protected $moduleManager;

    /**
     * @param ModuleManager $moduleManager
     */
    public function __construct(
        ModuleManager $moduleManager
    ) {
        $this->moduleManager = $moduleManager;
    }

    /**
     * Return array of customer groups
     *
     * @return array
     */
    public function toOptionArray()
    {
        $senderData = array();
        $senderData = [0 => "General Contact", 1 => "Sales Representative", 2 => "Customer Support", 3 => "Custom Email 1", 4 => "Custom Email 2" ];

        return $senderData;
    }
}
