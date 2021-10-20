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
namespace FME\ConfigurableProductView\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\PhpEnvironment\Request;
use Psr\Log\LoggerInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Quote\Model\QuoteFactory;
use Magento\Catalog\Model\ProductFactory;

class Addtocart extends \Magento\Framework\App\Action\Action
{
    protected $jsonFactory;
    protected $scopeConfig;
    protected $_transportBuilder;
    protected $storeManager;

    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        \Magento\Checkout\Model\Cart $cart,
        ProductFactory $product,
        QuoteFactory $quoteFactory,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->jsonFactory = $jsonFactory;
        $this->cart = $cart;
        $this->_stockItemRepository = $stockItemRepository;
        $this->_checkoutSession = $checkoutSession;
        $this->quoteFactory = $quoteFactory;
        $this->request = $request;
        $this->product = $product;
        $this->quoteRepository = $quoteRepository;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    public function dup($dep) {
        return implode(',', array_keys(array_flip(explode(',', $dep))));
    }

    public function getUniquefromString($xyz)
    {
        $myArray = explode(',', $xyz);
        return array_unique($myArray);
    }

    public function getUniqueValues($xyz)
    {
        $colordata = explode(',', $xyz);
        $vals = array_count_values($colordata);
        return $vals;
    }

    public function getStockItem($productId)
    {
        return $this->_stockItemRepository->get($productId);
    }

    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $c_Ids=$data['product_ids'];
        $main_p=$data['pid_main'];
        if($c_Ids=="")
        {        
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $product = $this->_objectManager->create('Magento\Catalog\Model\ProductFactory')->create()->load($main_p);
            $this->messageManager->addError(__("Select the product before adding to cart"));
            $resultRedirect->setUrl($product->getProductUrl());
            return $resultRedirect;
        }
        $childProductQty=$this->getUniqueValues($c_Ids);
        $cart = $this->_objectManager->get('Magento\Checkout\Model\Cart');
        foreach ($childProductQty as $key => $value) {
            $productId = $data['pid_main'];
            $childId = $key;
            $product = $this->_objectManager->create('Magento\Catalog\Model\ProductFactory')->create()->load($productId);
            $childProduct = $this->_objectManager->create('Magento\Catalog\Model\ProductFactory')->create()->load($childId);
            $params = [];
            $params['product'] = $product->getId();
            $params['qty'] = $value;
            $options = [];
            $productAttributeOptions = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);
            foreach($productAttributeOptions as $option){
                $options[$option['attribute_id']] =  $childProduct->getData($option['attribute_code']);
            }
            $params['super_attribute'] = $options;
            $cart->addProduct($product, $params);
        }
        $cart->save();            
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('checkout/cart/');
        return $resultRedirect;
    }
}
