<?php
namespace Mageants\OutofStockNotification\Block\Product\Bundle;

class Select extends \Magento\Bundle\Block\Catalog\Product\View\Type\Bundle\Option
{
    /**
     * @var string
     */
    protected $_template = 'Mageants_OutofStockNotification::Product/bundle/option/select.phtml';
}
