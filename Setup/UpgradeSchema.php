<?php
namespace Artifi\Personalize\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '3.0.0', '<')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('artifi_personalize_design'))
                ->addColumn(
                    'design_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    11,
                    ['unsigned' => true, 'nullable' => false, 'primary' => true, 'auto_increment' => false]
                )
                ->addColumn(
                    'design_params',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => false]
                )
                ->addColumn(
                    'preview_urls',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => false]
                )
                ->addColumn(
                    'thumbnail_urls',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => false]
                )
                ->addColumn(
                    'wishlist_item_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    11,
                    ['unsigned' => true, 'nullable' => true, 'primary' => false, 'auto_increment' => false]
                );
            $installer->getConnection()->createTable($table);
        }
        
        $installer->endSetup();
    }
}
