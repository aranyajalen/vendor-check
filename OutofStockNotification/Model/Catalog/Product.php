<?php 
/**
 * @category Mageants Out Of Stock Notification
 * @package Mageants_OutOfStockNotification
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\OutofStockNotification\Model\Catalog;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductLinkRepositoryInterface;
use Magento\Catalog\Model\Product\Attribute\Backend\Media\EntryConverterPool;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Pricing\SaleableInterface;

class Product extends \Magento\Catalog\Model\Product
{
    
    /**
     * @var \Mageants\OutofStockNotification\Block\Product\Notify
     */
    protected $_notifyBlock;
    
    /**
     * @var \Magento\Bundle\Model\Product\Type
     */
    protected $_bundelModel;


    /** 
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Api\ProductAttributeRepositoryInterface $metadataService
     * @param \Magento\Catalog\Model\Product\Url $url
     * @param \Magento\Catalog\Model\Product\Link $productLink
     * @param \Magento\Catalog\Model\Product\Configuration\Item\OptionFactory $itemOptionFactory
     * @param \Magento\CatalogInventory\Api\Data\StockItemInterfaceFactory $stockItemFactory
     * @param \Magento\Catalog\Model\Product\OptionFactory $catalogProductOptionFactory
     * @param \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status $catalogProductStatus
     * @param \Magento\Catalog\Model\Product\Media\Config $catalogProductMediaConfig
     * @param \Magento\Catalog\Model\Product\Type $catalogProductType
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param \Magento\Catalog\Helper\Product $catalogProduct
     * @param \Magento\Catalog\Model\ResourceModel\Product $resource
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $resourceCollection
     * @param \Magento\Framework\Data\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry
     * @param \Magento\Catalog\Model\Indexer\Product\Flat\Processor $productFlatIndexerProcessor
     * @param \Magento\Catalog\Model\Indexer\Product\Price\Processor $productPriceIndexerProcessor
     * @param \Magento\Catalog\Model\Indexer\Product\Eav\Processor $productEavIndexerProcessor
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Catalog\Model\Product\Image\CacheFactory $imageCacheFactory
     * @param \Magento\Catalog\Model\ProductLink\CollectionProvider $entityCollectionProvider
     * @param \Magento\Catalog\Model\Product\LinkTypeProvider $linkTypeProvider
     * @param \Magento\Catalog\Api\Data\ProductLinkInterfaceFactory $productLinkFactory
     * @param \Magento\Catalog\Api\Data\ProductLinkExtensionFactory $productLinkExtensionFactory
     * @param \Magento\Catalog\Model\Product\Attribute\Backend\Media\EntryConverterPool $mediaGalleryEntryConverterPool
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
     * @param \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $joinProcessor
     * @param \Mageants\OutofStockNotification\Block\Product\Notify $notifyBlock
     * @param \Magento\Bundle\Model\Product\Type $bundelModel
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface $metadataService,
        \Magento\Catalog\Model\Product\Url $url,
        \Magento\Catalog\Model\Product\Link $productLink,
        \Magento\Catalog\Model\Product\Configuration\Item\OptionFactory $itemOptionFactory,
        \Magento\CatalogInventory\Api\Data\StockItemInterfaceFactory $stockItemFactory,
        \Magento\Catalog\Model\Product\OptionFactory $catalogProductOptionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $catalogProductStatus,
        \Magento\Catalog\Model\Product\Media\Config $catalogProductMediaConfig,
        \Magento\Catalog\Model\Product\Type $catalogProductType,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Catalog\Helper\Product $catalogProduct,
        \Magento\Catalog\Model\ResourceModel\Product $resource,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $resourceCollection,
        \Magento\Framework\Data\CollectionFactory $collectionFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry,
        \Magento\Catalog\Model\Indexer\Product\Flat\Processor $productFlatIndexerProcessor,
        \Magento\Catalog\Model\Indexer\Product\Price\Processor $productPriceIndexerProcessor,
        \Magento\Catalog\Model\Indexer\Product\Eav\Processor $productEavIndexerProcessor,
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Catalog\Model\Product\Image\CacheFactory $imageCacheFactory,
        \Magento\Catalog\Model\ProductLink\CollectionProvider $entityCollectionProvider,
        \Magento\Catalog\Model\Product\LinkTypeProvider $linkTypeProvider,
        \Magento\Catalog\Api\Data\ProductLinkInterfaceFactory $productLinkFactory,
        \Magento\Catalog\Api\Data\ProductLinkExtensionFactory $productLinkExtensionFactory,
        EntryConverterPool $mediaGalleryEntryConverterPool,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $joinProcessor,
        \Mageants\OutofStockNotification\Block\Product\Notify $notifyBlock,
        \Magento\Bundle\Model\Product\Type $bundelModel,
        array $data = []
    ) {

        $this->_notifyBlock = $notifyBlock;
        $this->_bundelModel = $bundelModel;
        parent::__construct($context,$registry,$extensionFactory,$customAttributeFactory,$storeManager,$metadataService,$url,$productLink,$itemOptionFactory,$stockItemFactory,$catalogProductOptionFactory,$catalogProductVisibility,$catalogProductStatus,$catalogProductMediaConfig,$catalogProductType,$moduleManager,$catalogProduct,$resource,$resourceCollection,$collectionFactory,$filesystem,$indexerRegistry,$productFlatIndexerProcessor,$productPriceIndexerProcessor,$productEavIndexerProcessor,$categoryRepository,$imageCacheFactory,$entityCollectionProvider,$linkTypeProvider,$productLinkFactory,$productLinkExtensionFactory,$mediaGalleryEntryConverterPool,$dataObjectHelper,$joinProcessor,$data);
    }

   /**
    * @return object
    */  
   public function isSalable()
    {
        if ($this->_catalogProduct->getSkipSaleableCheck()) {
            return true;
        }
        if (($this->getOrigData('status') != $this->getData('status'))
            || $this->isStockStatusChanged()) {
            $this->unsetData('salable');
        }

        if ($this->hasData('salable')) {
            return $this->getData('salable');
        }

        $this->_eventManager->dispatch('catalog_product_is_salable_before', ['product' => $this]);

        $salable = $this->isAvailable();

        $isBundelProduct = $this->_bundelModel->getParentIdsByChild($this->getId());
        if (array_shift($isBundelProduct) != "" && $this->_notifyBlock->getAllowSelectSimpleBundle() == 1) 
        {
            $salable = 1;
        }

        $object = new \Magento\Framework\DataObject(['product' => $this, 'is_salable' => $salable]);
        $this->_eventManager->dispatch(
            'catalog_product_is_salable_after',
            ['product' => $this, 'salable' => $object]
        );
        
        $this->setData('salable', $object->getIsSalable());
        return $this->getData('salable');
    }

    /**
    * @return boolean
    */  
    private function isStockStatusChanged()
    {
        $stockItem = null;
        $extendedAttributes = $this->getExtensionAttributes();
        if ($extendedAttributes !== null) {
            $stockItem = $extendedAttributes->getStockItem();
        }
        $stockData = $this->getStockData();
        return (
            (is_array($stockData))
            && array_key_exists('is_in_stock', $stockData)
            && (null !== $stockItem)
            && ($stockItem->getIsInStock() != $stockData['is_in_stock'])
        );
    }
}