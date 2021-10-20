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
namespace FME\ConfigurableProductView\Ui\DataProvider\Product\Form\Modifiers;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\ObjectManagerInterface;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\DataType\Price;
use Magento\Ui\Component\Form\Fieldset;
use Magento\Ui\Component\Form\Element\DataType\Number;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\Checkbox;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Element\Select;
use Magento\Ui\Component\Form\Element\MultiSelect;
use Magento\Bundle\Api\ProductOptionRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Bundle\Ui\DataProvider\Product\Form\Modifier\BundlePanel;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\UrlInterface;

class Fields extends AbstractModifier
{
    public function __construct(
        LocatorInterface $locator,
        ObjectManagerInterface $objectManager, ArrayManager $arrayManager, 
        RequestInterface $request,
        UrlInterface $urlBuilder, 
        \Magento\Catalog\Model\ProductFactory $_productloader,
        ProductOptionRepositoryInterface $optionsRepository,
        ProductRepositoryInterface $productRepository,
        \FME\ConfigurableProductView\Model\ConfigurableProductView $_cpv,
        \Magento\Store\Model\System\Store $systemStore,
        array $modifiers = []
    ) {
        $this->locator = $locator;
        $this->objectManager = $objectManager;
        $this->_productloader = $_productloader;
        $this->optionsRepository = $optionsRepository;
        $this->productRepository = $productRepository;
        $this->request = $request;
        $this->_cpv=$_cpv;
        $this->_systemStore = $systemStore;
        $this->arrayManager = $arrayManager;
        $this->urlBuilder = $urlBuilder;
        $this->modifiers = $modifiers;
    }

    public function modifyData(array $data)
    {
        if ($this->locator->getProduct()->getTypeId() === 'configurable') {
            $product   = $this->locator->getProduct();
            $productId = $product->getId();
            $collection = $this->_cpv->getCollection()
            ->addFieldToFilter('product_id',array('eq'=>$productId));
            if(count($collection)>0)
            {
                $data[strval($productId)]['magenest']['status']    = $collection->getData()[0]['allow_configurableproductview'];
            }
        } 
        return $data;
    }

    public function modifyMeta(array $meta)
    {
        if ($this->locator->getProduct()->getTypeId() === 'configurable') {
            $meta = array_replace_recursive(
                $meta,
                [
                    'magenest' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'label' => __('FME Configurable Product View'),
                                    'collapsible' => true,
                                    'componentType' => Fieldset::NAME,
                                    'dataScope' => 'data.magenest',
                                    'sortOrder' => 10
                                ],
                            ],
                        ],
                        'children' => $this->getFields()
                    ],
                ]
            );
        }
        return $meta;
    }
    protected function getFields()
    {
        return [
            'status'    => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label'         => __('Attach Configurable Product View'),
                            'componentType' => Field::NAME,
                            'formElement'   => Select::NAME,
                            'dataScope'     => 'status',
                            'dataType'      => Text::NAME,
                            'sortOrder'     => 10,
                            'options'       => [
                                ['value' => '0', 'label' => __('No')],
                                ['value' => '1', 'label' => __('Yes')]
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }
}