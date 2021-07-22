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
namespace FME\ConfigurableProductView\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Store\Model\Store;
use Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_MODULE_ENABLE= 'configurableproductview/general/enableModule';
    const XML_PATH_MODULE_THEME= 'configurableproductview/general/theme';
    const XML_PATH_MODULE_GRID_TIER= 'configurableproductview/general/showtierprice';
    const XML_PATH_MODULE_GRID_RESET= 'configurableproductview/general/resetbtn';
    const XML_PATH_MODULE_GRID_FIXCOLOR= 'configurableproductview/general/color';
    const XML_PATH_MODULE_THEME_ADDTOCART= 'configurableproductview/general/addtocartLabel';
    const XML_PATH_MODULE_THEME_QTY= 'configurableproductview/general/qty';
    const XML_PATH_MODULE_THEME_TLABEL= 'configurableproductview/general/totallabel';

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Image\Factory $imageFactory,
        \Magento\Framework\App\ResourceConnection $coreResource
    ) {
        $this->_objectManager = $objectManager;
        $this->_coreRegistry = $registry;
        $this->_storeManager = $storeManager;
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_eventManager = $context->getEventManager();
        $this->_imageFactory = $imageFactory;
        $this->_resource = $coreResource;
        parent::__construct($context);
    }

    public function isEnableFixedColor()
    {
        $isEnabled = true;
        $enabled = $this->scopeConfig->getValue(self::XML_PATH_MODULE_GRID_FIXCOLOR, ScopeInterface::SCOPE_STORE);
        if ($enabled == null || $enabled == '0') {
            $isEnabled = false;
        }
        return $isEnabled;
    }

    public function getCartTLabel()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_MODULE_THEME_TLABEL,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getRestBtnLabel()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_MODULE_GRID_RESET,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getCartQuantity()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_MODULE_THEME_QTY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getCartbtnLabel()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_MODULE_THEME_ADDTOCART,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

    }

    public function isEnableTierPrice()
    {
        $isEnabled = true;
        $enabled = $this->scopeConfig->getValue(self::XML_PATH_MODULE_GRID_TIER, ScopeInterface::SCOPE_STORE);
        if ($enabled == null || $enabled == '0') {
            $isEnabled = false;
        }
        return $isEnabled;
    }

    public function getTheme()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_MODULE_THEME,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function isEnabledInFrontend()
    {
        $isEnabled = true;
        $enabled = $this->scopeConfig->getValue(self::XML_PATH_MODULE_ENABLE, ScopeInterface::SCOPE_STORE);
        if ($enabled == null || $enabled == '0') {
            $isEnabled = false;
        }
        return $isEnabled;
    }
}
