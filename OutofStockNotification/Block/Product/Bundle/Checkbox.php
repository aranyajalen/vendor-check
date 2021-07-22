<?php
/**
 * @category Mageants Out Of Stock Notification
 * @package Mageants_OutOfStockNotification
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\OutofStockNotification\Block\Product\Bundle;

class Checkbox extends \Magento\Bundle\Block\Catalog\Product\View\Type\Bundle\Option
{
    /**
     * @var template
     */
    protected $_template = 'Mageants_OutofStockNotification::Product/bundle/option/checkbox.phtml';
}
