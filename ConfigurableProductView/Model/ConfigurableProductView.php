<?php

/**
 * FME Extensions
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the fmeextensions.com license that is
 * available through the world-wide-web at this URL:
 * https://www.fmeextensions.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category  FME
 * @author     Atta <support@fmeextensions.com>
 * @package   FME_ConfigurableProductView
 * @copyright Copyright (c) 2019 FME (http://fmeextensions.com/)
 * @license   https://fmeextensions.com/LICENSE.txt
 */

namespace FME\ConfigurableProductView\Model;

use Magento\Framework\Model\AbstractModel;

class ConfigurableProductView extends AbstractModel
{
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\ResourceConnection $rcollection,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->dateTime=$dateTime;
        $this->rcollection=$rcollection;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init('FME\ConfigurableProductView\Model\ResourceModel\ConfigurableProductView');
    }

    public function getProductIdBySelectionId($selectioId)
    {
        $connection = $this->rcollection->getConnection();
        $tableName = $this->rcollection->getTableName('catalog_product_bundle_selection'); 
        $sql="Select * FROM  ".$tableName." WHERE selection_id=".$selectioId;
        $result = $connection->fetchAll($sql);
        return $result;

    }

    public function getMixandMatchByPidParentId($pid,$paretnid)
    {
        $collection = $this->getCollection()
        ->addFieldToFilter('product_id',$pid)
        ->addFieldToFilter('parent_product_id',$paretnid);
        return $collection->getData();;
    }

    public function getReferafriendDataUpdate($refererid,$childid)
    {
        $collection = $this->getCollection()
        ->addFieldToFilter('parent_product_id',$refererid)
        ->addFieldToFilter('product_id',$childid);
        return $collection->getData();
    }
    
    public function getReferafriendData($refererid)
    {
        $collection = $this->getCollection()
        ->addFieldToFilter('parent_product_id',$refererid);
        return $collection->getData();;
    }
}
