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
namespace FME\ConfigurableProductView\Block;

use Magento\Store\Model\Store;

class Cpv extends \Magento\Framework\View\Element\Template
{
    public $_storeManager;
    public $_scopeConfig;
    public $_helper;
    protected $_itemsLimit;
    protected $jsLayout;
    protected $request;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Catalog\Model\Product $product,
        \FME\ConfigurableProductView\Model\ConfigurableProductView $_cpv,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\App\ResourceConnectionFactory $coreresourceFactory,
        \Magento\Framework\App\ResourceConnection $coreresource,
        \Magento\Swatches\Helper\Data $swatchHelper,
        array $data = []
    ) {        
        $this->_product = $product;
        $this->_productFactory = $productFactory;
        $this->_urlInterface = $context->getUrlBuilder();
        $this->_objectManager = $objectManager;
        $this->_coreRegistry = $registry;
        $this->_cpv=$_cpv;
        $this->_storeManager = $context->getStoreManager();
        $this->pageConfig = $context->getPageConfig();
        $this->request = $request;
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_coreresourceFactory = $coreresourceFactory;
        $this->_coreresource = $coreresource;
        $this->jsLayout = isset($data['jsLayout']) && is_array($data['jsLayout']) ? $data['jsLayout'] : [];
        $this->swatchHelper = $swatchHelper;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        parent::_construct();
       
    }

    public function checkisAttached($id)
    {
        $collection = $this->_cpv->getCollection()
            ->addFieldToFilter('product_id',array('eq'=>$id));
        if(count($collection)>0)//update
        {
            $isallow=$collection->getData()[0]['allow_configurableproductview'];
            if($isallow==1 || $isallow=="1")
                return true;
        }
        else{
            return false;
        }
        return false;
    }
    public function getCurrentProduct() {
        return $this->_coreRegistry->registry('current_product');
    }
    public function getJsLayout()
    {
        return \Zend_Json::encode($this->jsLayout);
    }
    public function getParameters()
    {
        return $this->request->getParam('id');
    }

    public function getFormAction()
    {
        return $this->getUrl('configurableproductview/index/addtocart', ['_secure' => true]);
    }
    public function numberOfSizes($id)
    {
        $productId = $id;
        $product = $this->_objectManager->create('Magento\Catalog\Model\ProductFactory')->create()->load($productId);
        $productTypeInstance = $this->_objectManager->get('Magento\ConfigurableProduct\Model\Product\Type\Configurable');
        $productAttributeOptions = $productTypeInstance->getConfigurableAttributesAsArray($product);
        foreach($productAttributeOptions as $option){
            $value=$option['values'];
            return $value;
        }
        return array();
    }
    public function getAtributeSwatchHashcode($optionid) {
    $hashcodeData = $this->swatchHelper->getSwatchesByOptionsId([$optionid]);
    return $hashcodeData[$optionid]['value'];
}
   public function getSwatchmediaUrl(){
    return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
   }
   public function getProductPrice()
{
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$product = $objectManager->get('Magento\Framework\Registry')->registry('current_product');//get current product
    $priceRender = $this->getLayout()->getBlock('product.price.render.default')
        ->setData('is_product_list', true);

    $price = '';
    if ($priceRender) {
        $price = $priceRender->render(
            \Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE,
            $product,
            [
                'include_container' => true,
                'display_minimal_price' => true,
                'zone' => \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST,
                'list_category_page' => true
            ]
        );
    }

    return $price;
} 
    public function getCurrecySyb()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $currencyCode = $this->_storeManager->getStore()->getCurrentCurrencyCode(); 
        $currency = $objectManager->create('Magento\Directory\Model\CurrencyFactory')->create()->load($currencyCode); 
        $currencySymbol = $currency->getCurrencySymbol();
       return $currencySymbol;
    }
    public function getTierPriceData($id)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $product_obj = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($id); 
        $tier_price = $product_obj->getTierPrice();
        return $tier_price;
        
    }
    public function getTierData($id)
    {
        $productId = $id;
        $product = $this->_objectManager->create('Magento\Catalog\Model\ProductFactory')->create()->load($productId);
        $productTypeInstance = $this->_objectManager->get('Magento\ConfigurableProduct\Model\Product\Type\Configurable');
        $productAttributeOptions = $productTypeInstance->getConfigurableAttributesAsArray($product);
        $childProducts = $product->getTypeInstance()->getUsedProducts($product);
        $p_arr=array();
        foreach ($childProducts as $childProduct) {
            $arr=array();
            $id=$childProduct->getId();
            $child_Product = $this->_objectManager->create('Magento\Catalog\Model\ProductFactory')->create()->load($id);
        }
        return $p_arr;
    }
    public function colorAndSizeOnlyarray($id)
    {
        $productId = $id;
        $product = $this->_objectManager->create('Magento\Catalog\Model\ProductFactory')->create()->load($productId);
        $productTypeInstance = $this->_objectManager->get('Magento\ConfigurableProduct\Model\Product\Type\Configurable');
        $productAttributeOptions = $productTypeInstance->getConfigurableAttributesAsArray($product);
        $arr=array();
        foreach($productAttributeOptions as $option){
            $value=$option['values'];
            array_push($arr,$value);
        }
        return $arr;
    }
    public function getArrayData($id)
    {
        $productId = $id;
        $product = $this->_objectManager->create('Magento\Catalog\Model\ProductFactory')->create()->load($productId);
        $productTypeInstance = $this->_objectManager->get('Magento\ConfigurableProduct\Model\Product\Type\Configurable');
        $productAttributeOptions = $productTypeInstance->getConfigurableAttributesAsArray($product);
        $childProducts = $product->getTypeInstance()->getUsedProducts($product);
        $p_arr=array();
        foreach ($childProducts as $childProduct) {
            $arr=array();
            $id=$childProduct->getId();
            $child_Product = $this->_objectManager->create('Magento\Catalog\Model\ProductFactory')->create()->load($id);
            foreach($productAttributeOptions as $option){
                $value=$option['values'];
                foreach($value as $value1){
                    if($value1['value_index']==$childProduct->getData($option['attribute_code']))
                    {
                        $arr[]=$value1;
                    
                    }
                }
            }
            $productStockObj =  $this->_objectManager->get('Magento\CatalogInventory\Api\StockRegistryInterface')->getStockItem($id);
            $tier_price=$this->getTierPriceData($id);
            $regularPrice = $child_Product->getPriceInfo()->getPrice('regular_price')->getValue();
            if(count($tier_price)>0)
            {
                $a=array("price"=>$child_Product->getFinalPrice(),"stock" => $productStockObj->getQty(),"tier_price"=>$tier_price,"sku"=>$child_Product->getSku(),"status"=>$productStockObj->getIsInStock(),"regularPrice" => $regularPrice);
            }
            else{
                $a=array("price"=>$child_Product->getFinalPrice(),"stock" => $productStockObj->getQty(),"sku"=>$child_Product->getSku(),"status"=>$productStockObj->getIsInStock(),"regularPrice" => $regularPrice);
            }
            array_push($arr,$a);
            $p_arr[$id]=$arr;
        }
        return $p_arr;
    }

    public function getthumbsdata($image)
    {
        $html='';
        $html.='   <img class="item"  src="'.$image.'" data-src="'.$image.'"/>';        
        return $html;
    }

    public function addfilterwithtiles($galleryid)
    {
        $html='';
        $html.='ftg-set-'.$galleryid;
        return $html;
    }

    public function _prepareLayout()
    {
        parent::_prepareLayout();
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getProduct($id)
    {
        $pro = $this->_product->load($id);
        return $pro;
    }

    public function getCategory($id)
    {
        $model = $this->_objectManager->create('Magento\Catalog\Model\Category')->load($id);
        return $model;
    }

    public function getCMS($id)
    {
        $model = $this->_objectManager->create('Magento\CMS\Model\Page')->load($id);
        return $model;
    }


}
