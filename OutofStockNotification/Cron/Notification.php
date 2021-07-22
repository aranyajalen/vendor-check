<?php
/**
 * @category Mageants Out Of Stock Notification
 * @package Mageants_OutOfStockNotification
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\OutofStockNotification\Cron;

use Magento\Framework\App\Action\Context;

class Notification extends \Magento\Framework\App\Action\Action
{
    
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Magento\CatalogInventory\Model\Stock\StockItemRepository
     */
    protected $_stockItemRepository;

    /**
     * @var \Mageants\OutofStockNotification\Helper\Data
     */
    protected $_mageantsHelper;

     /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_timezoneInterface;


    /** 
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository
     * @param \Mageants\OutofStockNotification\Helper\Data $mageantsHelper
     * @param \\Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezoneInterface
     */
    public function __construct(
        Context $context, 
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository,
        \Mageants\OutofStockNotification\Helper\Data $mageantsHelper,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezoneInterface
    )
    {
        $this->productRepository = $productRepository;
        $this->_stockItemRepository = $stockItemRepository;     
        $this->_mageantsHelper = $mageantsHelper;
        $this->_timezoneInterface = $timezoneInterface;
        parent::__construct($context);
    }
    
    /**
     * Cron for stock notification
     */
    public function execute()
    {
        if(!$this->_mageantsHelper->isEnable()){
            return;
        }
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/stock_alert.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $currentTime= $this->_timezoneInterface->date()->format('H:i:s');
        $sendTime = $this->_mageantsHelper->getSendTime();
        $sendtime = str_replace(',', ':', $sendTime);
        $currentDateTime = date('h:i:s', strtotime($currentTime));
        $diffrenceTime = (int)strtotime($sendtime) - (int)strtotime($currentDateTime);

             
        try {
            if($sendtime!='00:00:00')
            {
              


               if(abs($diffrenceTime) < 100 && abs($diffrenceTime) >=0){
                    $logger->info('cron ran...');
                    if($this->_mageantsHelper->sendReportToAdmin()){
                        $this->_mageantsHelper->notifySubscriptionStatus($this->_mageantsHelper->getAdminEmail(), 'admin', $this->_mageantsHelper->getEmailTemplate());
                    }
                    $subscribers = $this->_mageantsHelper->getSubscribers();
                    foreach ($subscribers as $subscriber) {

                        try {
                            $product = $this->getProduct($subscriber->getProductSku());
                            $stock = $this->getStockItem($product->getId());

                            if($stock->getIsInStock()){
                                $this->_mageantsHelper->sendNotifications($this->_mageantsHelper->getStockNotifyCustomer(), $subscriber->getSku());
                            }
                        } catch (Exception $e) {
                            $logger->info('Something is wrong...'.$e->getMessage());            
                        }
                    }
                        
                    if($this->_mageantsHelper->sendReportToAdminForProduct()){
                        $this->_mageantsHelper->notifySubscriptionStatus($this->_mageantsHelper->getAdminEmail(), 'admin', $this->_mageantsHelper->getLowQtyStockNotifyCustomer());

                    }

                 }
            }
            else
            {
                if($this->_mageantsHelper->sendReportToAdmin()){
                        $this->_mageantsHelper->notifySubscriptionStatus($this->_mageantsHelper->getAdminEmail(), 'admin', $this->_mageantsHelper->getEmailTemplate());
                    }
                    $subscribers = $this->_mageantsHelper->getSubscribers();
                    foreach ($subscribers as $subscriber) {

                        try {
                            $product = $this->getProduct($subscriber->getProductSku());
                            $stock = $this->getStockItem($product->getId());

                            if($stock->getIsInStock()){
                                $this->_mageantsHelper->sendNotifications($this->_mageantsHelper->getStockNotifyCustomer(), $subscriber->getSku());
                            }
                        } catch (Exception $e) {
                            $logger->info('Something is wrong...'.$e->getMessage());            
                        }
                    }
                   
                    if($this->_mageantsHelper->sendReportToAdminForProduct()){
                        $this->_mageantsHelper->notifySubscriptionStatus($this->_mageantsHelper->getAdminEmail(), 'admin', $this->_mageantsHelper->getLowQtyStockNotifyCustomer());
                       $logger->info($this->_mageantsHelper->getLowQtyStockNotifyCustomer());
                    }
            }
        } catch (Exception $e) {
            $logger->info('Something is wrong...'.$e->getMessage());
        }

        return $this;
    }


    /** 
     * @param $sku
     * @return object
     */
    public function getProduct($sku)
    {
        return $this->productRepository->get($sku);
    }

    /** 
     * @param $prodict Id
     * @return object
     */
    public function getStockItem($productId)
    {
        return $this->_stockItemRepository->get($productId);
    }
}