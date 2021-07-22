<?php
/**
 * @category Mageants Out Of Stock Notification
 * @package Mageants_OutOfStockNotification
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\OutofStockNotification\Block\Product;

class Notify extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productloader;


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
     * @var \Magento\Customer\Model\SessionFactory
     */
    protected $_customerSession;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository
     * @param \Magento\Customer\Model\SessionFactory $customerSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\ProductFactory $_productloader
     * @param \Mageants\OutofStockNotification\Helper\Data $notifyHelper
     * @param array $data
     */
    
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Mageants\OutofStockNotification\Helper\Data $notifyHelper,
        array $data = []

    ) {
        parent::__construct($context, $data);
        $this->_registry = $registry;
        $this->_productloader = $_productloader;
        $this->_notifyHelper = $notifyHelper;
        $this->_storeManager = $context->getStoreManager();
        $this->_stockItemRepository = $stockItemRepository;
        $this->_customerSession = $customerSession->create();
    }

    /**
     * @return boolean
     */
    public function isEnable()
    {
        return $this->_notifyHelper->isEnable();
    }
    
    public function isEnableFmeExtension()
    {
        return $this->_notifyHelper->isEnableFmeExtension();
    }
    /**
     * @return boolean
     */
    public function isLoggedIn()
    {
        return $this->_customerSession->isLoggedIn();
    }
    
    /**
     *
     * @return string
     */
    public function getLoggedCustomerEmail()
    {
        if ($this->isLoggedIn()) {
            return $this->_customerSession->getCustomerData()->getEmail();
        }
    }
    
    /**
     *
     * @return int
     */
    public function getLoggedCustomerId()
    {
        $customerId = 0;
        if ($this->isLoggedIn()) {
            $customerId = $this->_customerSession->getCustomerData()->getId();
        }
        return $customerId;
    }
    
    /**
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl()."outofstocknotification/index/save";
    }
    
    /**
     * @return boolean
     */
    public function isShowNotifyOnCategory()
    {
        return $this->_notifyHelper->isShowNotifyOnCategory();
    }
    
    /**
     * @return string
     */
    public function getNotificationMessage()
    {
        return $this->_notifyHelper->getNotificationMessage();
    }
    
    /**
     * @return string
     */
    public function getCustomerGroup()
    {
        return $this->_notifyHelper->getCustomerGroup();
    }
    
    /**
     * @return boolean
     */
    public function getAllowSelectSimpleBundle()
    {
        return $this->_notifyHelper->getAllowSelectSimpleBundle();
    }

    /**
     * @return boolean
     */
    public function getAllowSelectSimpleConfig()
    {
        return $this->_notifyHelper->getAllowSelectSimpleConfig();
    }


    /**
     * @return boolean
     */
    public function isAllowCustomerGroup()
    {
        $isAllow = 0;
        $group_id = 0;
        if ($this->_customerSession->isLoggedIn()) {
            $group_id = $this->_customerSession->getCustomerData()->getGroupId();
        }

        if ($this->getCustomerGroup() == "32000" || $this->getCustomerGroup() == $group_id) {
            $isAllow = 1;
        } else {
            $all_group = explode(',', $this->getCustomerGroup());
            if (in_array($group_id, $all_group) || in_array("32000", $all_group)) {
                $isAllow = 1;
            }
        }
        return $isAllow;
    }

    /**
     * @return int
     */
    public function getMinQtyMail()
    {
        return $this->_notifyHelper->getMinQtyMail();
    }
    
    

    /**
     * @return object
     */
    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

    /**
     * @return int
     */
    public function setListProduct($id)
    {
        $this->_productId = $id;
    }

    /**
     * @return object
     */
    public function getProductStock($id)
    {
        try {
            return $this->_stockItemRepository->get($id);
        } catch (Exception $e) {
            $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
            $logger = new \Zend\Log\Logger();
            $logger->addWriter($writer);
            $logger->info("logggerrrr");
            $logger->info($id);
        }
    }

    /**
     * @return object
     */

    public function getProduct($id)
    {
        return $this->_productloader->create()->load($id);
    }

    /**
     * @return int
     */
    public function getProductQty()
    {
        $id = 0;
       
        if ($this->getCurrentProduct()) {
            $id = $this->getCurrentProduct()->getId();
        } else {
            $id = $this->_productId;
        }
        $product = $this->_stockItemRepository->get($id);
        return $product->getQty();
    }

    /**
     * @return boolean
     */
    public function getIsEnableOutofstock()
    {
        if ($this->getCurrentProduct()) {
            return $this->getCurrentProduct()->getIsEnableOutofstock();
        } else {
            $id = $this->_productId;
        }
    }
}
