<?php 
/**
 * Ultimate_ordersBatches extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category   	Ultimate
 * @package		Ultimate_ordersBatches
 * @copyright  	Copyright (c) 2014
 * @license		http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * ordersBatches module install script
 *
 * @category	Ultimate
 * @package		Ultimate_ordersBatches
 * @author Ultimate Module Creator
 */
$this->startSetup();
$table = $this->getConnection()
	->newTable($this->getTable('ordersbatches/batche'))
	->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'identity'  => true,
		'nullable'  => false,
		'primary'   => true,
		), 'Batch ID')
	->addColumn('batches_name_input', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
		'nullable'  => false,
		), 'Batch Name')

	->addColumn('status', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		), 'Status')

	->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
		), 'Batch Creation Time')
	->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
		), 'Batch Modification Time')
	->setComment('Batch Table');
$this->getConnection()->createTable($table);

$table = $this->getConnection()
	->newTable($this->getTable('ordersbatches/batche_store'))
	->addColumn('batche_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
		'nullable'  => false,
		'primary'   => true,
		), 'Batch ID')
	->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		'primary'   => true,
		), 'Store ID')
	->addIndex($this->getIdxName('ordersbatches/batche_store', array('store_id')), array('store_id'))
	->addForeignKey($this->getFkName('ordersbatches/batche_store', 'batche_id', 'ordersbatches/batche', 'entity_id'), 'batche_id', $this->getTable('ordersbatches/batche'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
	->addForeignKey($this->getFkName('ordersbatches/batche_store', 'store_id', 'core/store', 'store_id'), 'store_id', $this->getTable('core/store'), 'store_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
	->setComment('Batches To Store Linkage Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
	->newTable($this->getTable('ordersbatches/batche_product'))
	->addColumn('rel_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Category ID')
	->addColumn('batche_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		'default'   => '0',
	), 'Batch ID')
	->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		'default'   => '0',
	), 'Product ID')
	->addColumn('position', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'nullable'  => false,
		'default'   => '0',
	), 'Position')
	->addIndex($this->getIdxName('ordersbatches/batche_product', array('product_id')), array('product_id'))
	->addForeignKey($this->getFkName('ordersbatches/batche_product', 'batche_id', 'ordersbatches/batche', 'entity_id'), 'batche_id', $this->getTable('ordersbatches/batche'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
	->addForeignKey($this->getFkName('ordersbatches/batche_product', 'product_id', 'catalog/product', 'entity_id'),	'product_id', $this->getTable('catalog/product'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
	->setComment('Batch to Product Linkage Table');
$this->getConnection()->createTable($table);
$this->endSetup();