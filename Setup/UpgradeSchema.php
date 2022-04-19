<?php

/**
 * @author    Zip Plugin Team <integrations@zip.co>
 * @copyright 2020 Zip Co Limited
 * @link      http://zip.co
 */

namespace Zip\ZipPayment\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    const ZIP_CUSTOMER_TOKEN_TABLE_NAME = 'zip_customer_token';

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (version_compare($context->getVersion(), '1.1.12', '<')) {
            if (!$installer->tableExists(self::ZIP_CUSTOMER_TOKEN_TABLE_NAME)) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable(self::ZIP_CUSTOMER_TOKEN_TABLE_NAME)
                )->addColumn(
                    'id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    8,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Id'
                )->addColumn(
                    'customer_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    8,
                    ['unsigned' => true, 'nullable' => false],
                    'Customer ID'
                )->addColumn(
                    'customer_token',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Customer Token'
                )->addIndex(
                    $installer->getIdxName(self::ZIP_CUSTOMER_TOKEN_TABLE_NAME, ['customer_id']),
                    ['customer_id']
                )->addForeignKey(
                    $installer->getFkName(
                        self::ZIP_CUSTOMER_TOKEN_TABLE_NAME,
                        'customer_id',
                        'customer_entity',
                        'entity_id'
                    ),
                    'customer_id',
                    $installer->getTable('customer_entity'),
                    'entity_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                );

                $installer->getConnection()->createTable($table);
                $installer->endSetup();
            }
        }
    }
}
