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
class CatalogProductImportBunchSaveAfter implements ObserverInterface
{
     
    /**
     * @param \Mageants\OutofStockNotification\Helper\Data $stockHelper
     * @param \Magento\CatalogInventory\Api\StockStateInterface $stockItem
     */
    public function __construct(
        \Mageants\OutofStockNotification\Helper\Data $stockHelper,
        \Magento\CatalogInventory\Api\StockStateInterface $stockItem,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\App\ProductMetadataInterface $productMetadataInterface,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    ) {
        $this->stockHelper = $stockHelper;
        $this->stockItem = $stockItem;
        $this->_request = $request;
        $this->productMetadataInterface = $productMetadataInterface;
        $this->productRepository = $productRepository;
    }


    /**
     * Execute and send notification email to customer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $bunch = $observer->getEvent()->getData('bunch');
        foreach ($bunch as $rowNum => $rowData) {
            $item = $this->productRepository->get($rowData['sku']);

            $qty = $this->stockItem->getStockQty($item->getId(), $item->getStore()->getWebsiteId());
            $version = str_replace(".", "", $this->productMetadataInterface->getVersion());
            if ((int)$version < 230) {
                if ($this->stockHelper->isEnable() && $rowData['is_in_stock'] == '1') {
                    $this->stockHelper->sendNotifications($this->stockHelper->getStockNotifyCustomer($item->getStoreId()), $item->getSku());
                }
            } else {
                if ($rowData['is_in_stock'] == '1') {
                    if ($this->stockHelper->isEnable() && $rowData['is_in_stock'] == '1') {
                        $this->stockHelper->sendNotifications($this->stockHelper->getStockNotifyCustomer($item->getStoreId()), $item->getSku());
                    }
                }
            }
        }
    }
}
