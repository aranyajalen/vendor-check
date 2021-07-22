<?php
/**
 * @category Mageants Out Of Stock Notification
 * @package Mageants_OutOfStockNotification
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\OutofStockNotification\Plugin;

class BeforeAllowProducts
{
    /**
     * @var \Mageants\OutofStockNotification\Block\Product\Notify
     */        

    protected $_notifyBlock;

    /**
     * @param \Mageants\OutofStockNotification\Block\Product\Notify $notifyBlock
     */    
    public function __construct(
        \Mageants\OutofStockNotification\Block\Product\Notify $notifyBlock
    )
    {
        $this->_notifyBlock = $notifyBlock;
    }

    /**
     * getAllowProducts
     *
     * @param \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject
     *
     * @return array
     */    
    public function beforeGetAllowProducts($subject)
    {
        if ($this->_notifyBlock->getAllowSelectSimpleConfig() == 1) {
            if (!$subject->hasData('allow_products')) {
                $products = [];
                $allProducts = $subject->getProduct()->getTypeInstance()->getUsedProducts($subject->getProduct(), null);
                foreach ($allProducts as $product) {
                        $products[] = $product;
                }
                //$subject->setAllowProducts($products);
                $subject->setData('allow_products', $products);
            }            
        }
        return [];
    }

}