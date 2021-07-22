<?php
/**
 * @category Mageants Out Of Stock Notification
 * @package Mageants_OutOfStockNotification
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\OutofStockNotification\Controller\Adminhtml\CustomerList;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Mageants\OutofStockNotification\Model\ResourceModel\Stocknotification\CollectionFactory;
/**
 * Perform MassDelete controller Action
 */
class massDelete extends \Magento\Backend\App\Action
{
    /**
     * Filter
     *
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $_filter;

    protected $_stocknotification;

    /**
     * Collection factory
     *
     * @var \Mageants\OutofStockNotification\Model\ResourceModel\Stocknotification\CollectionFactory
     */
    protected $_collectionFactory;
    
    /**
     * @param Magento\Backend\App\Action\Context $context
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Mageants\OutofStockNotification\Model\Stocknotification $stocknotification
     * @param \Mageants\OutofStockNotification\Model\ResourceModel\Stocknotification\CollectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        \Mageants\OutofStockNotification\Model\Stocknotification $stocknotification, 
        CollectionFactory $collectionFactory
    ) 
    {
        $this->_filter = $filter;
        $this->_stocknotification = $stocknotification;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context);
    }
    
    /**
     * Perform MassDelete Action
     */
    public function execute()
    {
        $collection = $this->_filter->getCollection($this->_collectionFactory->create());
        $collectionSize = $collection->getSize();
        foreach($collection as $items)
        {
            $id = $items->getId();
            $stocknotificationData = $this->_stocknotification->load($id);
            try{
                $stocknotificationData->delete();
            }catch(Exception $e){
                $this->messageManager->addError($e->getMessage());    
            }
        }        
            $this->messageManager->addSuccess(
            __('A total of %1 record(s) have been deleted.', $collectionSize));
 
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mageants_OutofStockNotification::massdelete');
    }
}