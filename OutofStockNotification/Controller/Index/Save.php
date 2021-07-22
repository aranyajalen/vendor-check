<?php
/**
 * @category Mageants Out Of Stock Notification
 * @package Mageants_OutOfStockNotification
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\OutofStockNotification\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class Save extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $_customer;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $_request;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @var \Mageants\OutofStockNotification\Model\Stocknotification
     */
    protected $_stockNotification;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $_messageManager;

    /**
     * @var \Mageants\OutofStockNotification\Helper\Data
     */
    protected $stockHelper;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager,
     * @param \Magento\Customer\Model\Customer $customer
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Mageants\OutofStockNotification\Model\Stocknotification $stockNotification
     * @param \Mageants\OutofStockNotification\Helper\Data $stockHelper
     */
    public function __construct(
        Context $context, 
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Customer $customer,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Mageants\OutofStockNotification\Model\Stocknotification $stockNotification,
        \Mageants\OutofStockNotification\Helper\Data $stockHelper
    )
    {
        $this->_customer = $customer;
        $this->_storeManager = $storeManager;      
        $this->_request = $request;  
        $this->date = $date;
        $this->_stockNotification = $stockNotification;
        $this->_messageManager = $context->getMessageManager();
        $this->stockHelper = $stockHelper;
        parent::__construct($context);
    }
    
    /**
     * return redirect at customer Dashboard
     */
    public function execute()
    {
        $customerEmail = "";
        $customerId = "";
        $productSku = "";
        $productName = "";
        $producturl = "";
      

        if ($this->_request->getParam('notify') != "") {
            $customerEmail = $this->_request->getParam('notify');
            $customerId = $this->_request->getParam('customerId');
            $productSku = $this->_request->getParam('productSku');
            $productName = $this->_request->getParam('productName');
            $producturl = $this->_request->getParam('producturl');
        }
        else{
            if (isset($_POST['Id']) && isset($_POST['Email'])) {
                $customerId = $_POST['Id'];
                $productSku = $_POST['Sku'];
                $productName = $_POST['Name'];
                $customerEmail = $_POST['Email'];
                $producturl = $_POST['producturl'];
            }
            else{
                $this->_messageManager->addError(__("Please enter your E-mail address."));
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                
                return $resultRedirect;
            }
            
        }
        if(empty($customerEmail) || $customerEmail == ''){
            $this->_messageManager->addError(__("Please enter your E-mail address."));
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                
                return $resultRedirect;
        }
        $isSubscribe = 0;    
        $customer = $this->_customer->load($customerId);
        $customerName = $customer->getName();
        if ($customerName == " ") 
        {
            $customerName = "Guest";
        }
        
        $producturl = $this->stockHelper->getProductUrlBySku($productSku);
        
        $websiteName = $this->_storeManager->getStore()->getWebsite()->getName();
        $subscribeDate = $this->date->gmtDate();
        $data = ["customer_id" => $customerId,"customer_name" => $customerName,"email" => $customerEmail,"product_sku" => $productSku,"product_name" => $productName,"subscribe_date" => $subscribeDate,"send_date" => "","status" => 'Pending',"notify_status" => '1', "product_url"=>$producturl];
        $collection = $this->_stockNotification->getCollection()->addFieldToFilter('email', $customerEmail);

        foreach ($collection as $key => $value) {
            if ($productSku == $value->getProductSku() && $value->getData('status') == 'Pending') 
            {
                $isSubscribe = 1;
            }
        }
        if ($isSubscribe == 0) {
            $this->_stockNotification->setData($data);
            $this->_stockNotification->save();
            $this->stockHelper->notifySubscriptionStatus($customerEmail,$productName, $this->stockHelper->getNotifyCustomer());
            $this->_messageManager->addSuccess(__("Thank you for subscribe."));
        }
        else{
            $this->_messageManager->addError(__("You already subscribed for this product."));
        }
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        
        return $resultRedirect;
    }
}
