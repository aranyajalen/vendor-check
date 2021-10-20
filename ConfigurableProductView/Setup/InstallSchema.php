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
namespace FME\ConfigurableProductView\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        // Get v table
        $tableName = $installer->getTable('fme_configurableproductview');
        // Check if the table already exists
        if ($installer->getConnection()->isTableExists($tableName) != true) {
            $table = $installer->getConnection()
                    ->newTable($tableName)
                    ->addColumn(
                        'configurableproductview_id',
                        Table::TYPE_INTEGER,
                        null,
                        [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                        ],
                        'ID'
                    )
                    ->addColumn(
                        'allow_configurableproductview',
                        Table::TYPE_SMALLINT,
                        null,
                        [
                        'nullable' => false,
                        'default' => 0,
                        ],
                        'Allow configurableproductview'
                    )
                    ->addColumn(
                        'product_id',
                        Table::TYPE_INTEGER,
                        null,
                        [
                        'nullable' => true
                        ],
                        'Product ID'
                    )
                    ->setComment('configurableproductview Table');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
