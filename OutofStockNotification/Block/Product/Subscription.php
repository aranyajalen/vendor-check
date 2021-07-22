<?php
/**
 * @category Mageants Out Of Stock Notification
 * @package Mageants_OutOfStockNotification
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\OutofStockNotification\Block\Product;



class Subscription  extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Mageants\OutofStockNotification\Helper\Data
     */
    protected $_notifyHelper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\CatalogInventory\Model\Stock\StockItemRepository
     */
    protected $_stockItemRepository;

    /**
     * @var \Mageants\OutofStockNotification\Model\Stocknotification
     */
    protected $_stockNotification;
    
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $_productRepository;

    /**
     * @var \Magento\Customer\Model\SessionFactory
     */
    protected $_customerSession;

    /** 
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository
     * @param \Magento\Customer\Model\SessionFactory $customerSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Mageants\OutofStockNotification\Helper\Data $notifyHelper
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Mageants\OutofStockNotification\Model\Stocknotification $stockNotification
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Mageants\OutofStockNotification\Helper\Data $notifyHelper,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Mageants\OutofStockNotification\Model\Stocknotification $stockNotification
    )
    {
        parent::__construct($context);
        $this->_notifyHelper = $notifyHelper;
        $this->_storeManager = $context->getStoreManager();
        $this->_stockItemRepository = $stockItemRepository;
        $this->_stockNotification = $stockNotification;
        $this->_productRepository = $productRepository;    
        $this->_customerSession = $customerSession->create();
    }

    /** 
     * @return booelan
     */
    public function isEnable() {
        return $this->_notifyHelper->isEnable();
    }

    /** 
     * @return booelan
     */
    public function isLoggedIn() {
        return $this->_customerSession->isLoggedIn();
    }
    
    /**
     * @param sku 
     * @return object
     */
    public function getProduct($sku) {
        try {
            $product = $this->_productRepository->get($sku);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e){
            $product = false;
        }
        return $product;
    }
    
    /**
     * @return string
     */
    public function getMediaUrl() {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * @return string
     */
    public function getStopNotifyAction() {
        return $this->_storeManager->getStore()->getBaseUrl()."outofstocknotification/index/stopnotify";
    }

    /**
     * @return string
     */
    public function getStopRemoveAction() {
        return $this->_storeManager->getStore()->getBaseUrl()."outofstocknotification/index/remove";
    }
    
    /**
     * @return boolean
     */
    public function getLoggedCustomerId() {
        $customerId = 0;
        if ($this->isLoggedIn()) {
            $customerId = $this->_customerSession->getCustomerData()->getId();
        }
        return $customerId;
    }

    /**
     * @param product id
     * @return object
     */
    public function getStockItem($productId)
    {
        try {
            $productstock = $this->_stockItemRepository->get($productId);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e){
            $productstock = false;
        }
        return $productstock;
    }
    
    /**
     * @return array
     */
    public function getSubscribedProductSku() {
        $ids = array();
        $data = array();
        $collection = $this->_stockNotification->getCollection()->addFieldToFilter('customer_id', $this->getLoggedCustomerId());
        foreach ($collection as $product) {
            if (in_array($product->getProductSku(), $ids)) {
                foreach ($data as $key => $value) {
                    if ($value['sku'] == $product->getProductSku()) {
                        $email = $value['email'].", ".$product->getEmail();
                        $data[$key]['email'] = $email;
                    }
                }
            }
            else{
                $ids[] = $product->getProductSku();
                $data[$product->getEntityId()] = ['sku' => $product->getProductSku(), 'name' => $product->getProductName(), 'email' => $product->getEmail(), 'notify_status' => $product->getNotifyStatus()];
            }
        }
        return $data;
    }
    /**
     * @return null
     */
    public function getCacheLifetime()
    {
        return null;
    }
}