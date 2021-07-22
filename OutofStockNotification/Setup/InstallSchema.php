<?php
/**
 * @category Mageants Out Of Stock Notification
 * @package Mageants_OutOfStockNotification
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\OutofStockNotification\Setup;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * InstallSchema for Update Database for Login As Customer
 */
class InstallSchema implements InstallSchemaInterface
{
   
    /**
     * install Database for Login As Customer
     * @param \Magento\Framework\Setup\SchemaSetupInterface
     * @param \Magento\Framework\Setup\ModuleContextInterface
     */
    
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        /* create table loginascustomer_configuration */
        
        $table = $installer->getConnection()->newTable(
        $installer->getTable('subscribe_product_notification'))
         ->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Id'
        )->addColumn(
            'customer_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Customer Name'
        )->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Customer Id'
        )->addColumn(
            'email',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Email'
        )->addColumn(
            'product_sku',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'SKU'
        )->addColumn(
            'product_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Product Name'
        )->addColumn(
            'product_url',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Product URL'
        )->addColumn(
            'subscribe_date',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Subscribe Date'
        )->addColumn(
            'send_date',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Send Date'
        )->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Status'
        )->addColumn(
            'notify_status',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Notify Status'
        )->addIndex(  
                      $setup->getIdxName(  
                           $setup->getTable('subscribe_product_notification'),  
                           ['customer_name'],  
                           \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT  
                      ),  
                      ['customer_name'],
                      ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->setComment(
            'Mageants Out Of Stock Notification'
        );
        $installer->getConnection()->createTable($table);
                
        $installer->endSetup();
    }
}
