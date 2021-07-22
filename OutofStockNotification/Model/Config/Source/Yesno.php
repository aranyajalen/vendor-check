<?php
/**
 * @category Mageants Out Of Stock Notification
 * @package Mageants_OutOfStockNotification
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\OutofStockNotification\Model\Config\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory;
use Magento\Framework\DB\Ddl\Table;

/**
 * Custom Attribute Renderer
 */
class Yesno extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var OptionFactory
     */
    private $optionFactory;
    
    /**
     * Get all options** @return array
     */
    public function getAllOptions()
    {
        $this->_options=[['label'=>'Yes', 'value'=>'1'],
        ['label'=>'No', 'value'=>'0'],];
        return $this->_options;
    }
}
