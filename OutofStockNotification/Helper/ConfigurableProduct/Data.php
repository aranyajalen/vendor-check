<?php
/**
 * @category Mageants Out Of Stock Notification
 * @package Mageants_OutOfStockNotification
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\OutofStockNotification\Helper\ConfigurableProduct;

/**
 * Data class for Helper
 */

class Data extends \Magento\ConfigurableProduct\Helper\Data
{
    
    public function getOptions($currentProduct, $allowedProducts)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $stockRegistry = $objectManager->get('Magento\CatalogInventory\Api\StockRegistryInterface');

        $options = [];
        $allowAttributes = $this->getAllowAttributes($currentProduct);

        foreach ($allowedProducts as $product) {
            $productId = $product->getId();

            $product = $objectManager->get('Magento\Catalog\Model\Product')->load($productId);
            $stockitem = $stockRegistry->getStockItem($product->getId(), $product->getStore()->getWebsiteId());
            //if($stockitem->getQty() == 0) continue;

            foreach ($allowAttributes as $attribute) {
                $productAttribute = $attribute->getProductAttribute();
                $productAttributeId = $productAttribute->getId();
                $attributeValue = $product->getData($productAttribute->getAttributeCode());

                $options[$productAttributeId][$attributeValue][] = $productId;
                $options['index'][$productId][$productAttributeId] = $attributeValue;
            }
        }
        return $options;
    }
}