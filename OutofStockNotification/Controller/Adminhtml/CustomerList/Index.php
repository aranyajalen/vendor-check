<?php
/**
 * @category Mageants Out Of Stock Notification
 * @package Mageants_OutOfStockNotification
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\OutofStockNotification\Controller\Adminhtml\CustomerList;
 
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var resultPageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Mageants_OutofStockNotification::notificationlist');
        $resultPage->addBreadcrumb(__('OutofStock Subscription List'), __('OutofStock Subscription List'));
        $resultPage->addBreadcrumb(__('Manage OutofStock Notification List'), __('Manage OutofStock Notification List'));
        $resultPage->getConfig()->getTitle()->prepend(__('OutofStock Subscription List'));

        return $resultPage;
    }

    /**
     * _isAllowed
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mageants_OutofStockNotification::notificationlist');
    }
}
