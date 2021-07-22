<?php 

namespace Mageants\OutofStockNotification\Block;

class Outstock extends \Magento\Swatches\Block\Product\Renderer\Configurable
{

   public function getAllowProducts()
    {
        if (!$this->hasAllowProducts()){
            $products = [];
             //$skipSaleableCheck = 1;
            $allproducts = $this->getProduct()->getTypeInstance()->getUsedProducts($this->getProduct(), null) ;
            foreach ($allproducts as $product) {
                    $products[] = $product;
                }
            $this->setAllowProducts($products);
            }     
       return $this->getData('allow_products');
    }
}