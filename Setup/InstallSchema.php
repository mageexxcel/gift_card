<?php
/**
 * Copyright © 2015 Excellence. All rights reserved.
 */

namespace Excellence\GiftCard\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
	
        $installer = $setup;

        $installer->startSetup();

		/**
         * Create table 'storecredit_storecredit'
         */  
//START table setup
    $table = $installer->getConnection()->newTable(
                $installer->getTable('excellence_giftcard_main')
        )->addColumn(
                'gift_card_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Entity Id'
            )
            ->addColumn(
                'time',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true,'default' => null],
                'time'
            )
            ->addColumn(
                'date',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true,'default' => null],
                'date'
            )
            ->addColumn(
                'card_value',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true,'default' => null],
                'Credit Value'
            )->addColumn(
                'receiver_email',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true,'default' => null],
                'Receiver Email Id'
            )->addColumn(
                'sender_email',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true,'default' => null],
                'Sender Email Id'
            )->addColumn(
                'coupon_code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true,'default' => null],
                'Coupon Code'
            )->addColumn(
                'card_value',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true,'default' => null],
                'Coupon value'
            )->addColumn(
                'receiver_email',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true,'default' => null],
                'Receiver Email'
            )->addColumn(
                'receiver_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true,'default' => null],
                'Receiver Name'
            )->addColumn(
                'sender_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true,'default' => null],
                'Sender Name'
            )->addColumn(
                'sender_email',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true,'default' => null],
                'Sender Email'
            )->addColumn(
                'order_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true,'default' => null],
                'Order Id'
            )->addColumn(
                'optional_message',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true,'default' => null],
                'Optional Message'
            )->addColumn(
                'is_mail_sent',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                null,
                [ 'default' => false, 'nullable' => false],
                'Mail staus'
            )->addColumn(
                'is_coupon_used',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                null,
                [ 'default' => false, 'nullable' => false],
                'Coupon staus'
            );
    $installer->getConnection()->createTable($table);

        //END   table setup
        $installer->endSetup();
    }
}
