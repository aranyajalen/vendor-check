<?php
/**
 * @category Mageants OutofStockNotification
 * @package Mageants_OutofStockNotification
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\OutofStockNotification\Observer\Adminhtml;

use Magento\Framework\Event\ObserverInterface;

/**
 * notify class
 */
class Notify implements ObserverInterface
{
     
    /**
     * @param \Mageants\OutofStockNotification\Helper\Data $stockHelper
     * @param \Magento\CatalogInventory\Api\StockStateInterface $stockItem
     */
    public function __construct(
        \Mageants\OutofStockNotification\Helper\Data $stockHelper,
        \Magento\CatalogInventory\Api\StockStateInterface $stockItem,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\App\ProductMetadataInterface $productMetadataInterface
    ) {
        $this->stockHelper = $stockHelper;
        $this->stockItem = $stockItem;
        $this->_request = $request;
        $this->productMetadataInterface = $productMetadataInterface;
    }


    /**
     * Execute and send notification email to customer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $item = $observer->getData('product');
        //var_dump($this->_request->getParam('product'));
        $mageantProduct = $this->_request->getParam('product');
        $qty = $this->stockItem->getStockQty($item->getId(), $item->getStore()->getWebsiteId());
        $version = str_replace(".", "", $this->productMetadataInterface->getVersion());
        if ((int)$version < 230) {
            if ($this->stockHelper->isEnable() && isset($mageantProduct['quantity_and_stock_status']['is_in_stock'])) {
                $this->stockHelper->sendNotifications($this->stockHelper->getStockNotifyCustomer($item->getStoreId()), $item->getSku());
            }
        } else {
            if (isset($mageantProduct['quantity_and_stock_status']['is_in_stock'])) {
                if ($this->stockHelper->isEnable() && $mageantProduct['quantity_and_stock_status']['is_in_stock'] == 1) {
                    $this->stockHelper->sendNotifications($this->stockHelper->getStockNotifyCustomer($item->getStoreId()), $item->getSku());
                }
            }
        }
    }
}
