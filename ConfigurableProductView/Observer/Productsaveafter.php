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
namespace FME\ConfigurableProductView\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Action\Context;

class Productsaveafter implements ObserverInterface
{  
    protected $_productloader;

    public function __construct(
        \Magento\Catalog\Model\ProductFactory $_productloader,
        Context $context,
        \FME\ConfigurableProductView\Model\ConfigurableProductView $_cpv

    ) {
        $this->_productloader = $_productloader;
        $this->_cpv=$_cpv;
        $this->context     = $context;
        $this->_request   = $context->getRequest();
    }

    public function getLoadProduct($id)
    {
        return $this->_productloader->create()->load($id);
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $params= $this->_request->getParams();
        $product=$observer->getProduct();
        if ($product->getTypeId() =="configurable")
        {  
            $customFieldData = $params['magenest'];
            $customFieldData=  $customFieldData['status'];
            $product_id= $observer->getProduct()->getId();
            $collection = $this->_cpv->getCollection()
            ->addFieldToFilter('product_id',array('eq'=>$product_id));
            if(count($collection)>0)//update
            {
                $id=$collection->getData()[0]['configurableproductview_id'];
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $model = $objectManager->create('FME\ConfigurableProductView\Model\ConfigurableProductView')->load($id);
                $model->delete();
                $post['allow_configurableproductview']=$customFieldData;
                $post['product_id']=$product_id;
                $this->_cpv->setData($post);
                $this->_cpv->save();
            } else {
                $post['allow_configurableproductview']=$customFieldData;
                $post['product_id']=$product_id;
                $this->_cpv->setData($post);
                $this->_cpv->save();
            }
        }
    }   
}
