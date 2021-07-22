<?php
/**
 * @category Mageants Out Of Stock Notification
 * @package Mageants_OutOfStockNotification
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\OutofStockNotification\Controller\Products;

use Magento\Framework\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;
 
     /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory;
     */
    public function __construct(Context $context, PageFactory $pageFactory)
    {

        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }
    
    /**
     * Redirect to notification page
     */
    public function execute()
    {
        return $this->_pageFactory->create();
    }
}
