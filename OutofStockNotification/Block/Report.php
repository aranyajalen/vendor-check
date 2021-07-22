<?php
/**
 * @category Mageants Out Of Stock Notification
 * @package Mageants_OutOfStockNotification
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\OutofStockNotification\Block;

use Magento\ConfigurableProduct\Model\ConfigurableAttributeData;


class Report extends \Magento\Framework\View\Element\Template
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
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $product;

    /**
     * @var \Magento\CatalogInventory\Api\StockRegistryInterface
     */
    protected $_stockRegistry;


    /** 
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Catalog\Model\Product $product,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        array $data = []
    )
    {
        $this->productRepository = $productRepository;
        $this->_stockItemRepository = $stockItemRepository;     
        $this->date = $date;
        $this->product = $product;
        $this->_stockRegistry = $stockRegistry;
        parent::__construct($context, $data);
    }

    /** 
     * @param $sku
     * @return object
     */
    public function getProduct($sku){
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

    /** 
     * @return date | string
     */
    public function getYesterday(){
        return $this->date->gmtDate('Y-m-d H:i:s', strtotime("-1 days"));
    }

    /** 
     * @return date | string
     */
    public function getSendTime($datetime=''){
        return $this->date->gmtDate('Y-m-d H:i:s', $datetime);
    }
    /** 
     * @return Object
     */
    public function getProductCollection(){
       return $this->product->getCollection()->addAttributeToSelect('*');
    }

    /** 
     * @param $prodict Id
     * @return object
     */
    public function getStock($productId){
        return $this->_stockRegistry->getStockItem($productId);
    }
}
