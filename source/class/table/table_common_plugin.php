<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_common_plugin.php 32122 2012-11-14 01:55:46Z monkey $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_common_plugin extends discuz_table
{
	public function __construct() {

		$this->_table = 'common_plugin';
		$this->_pk    = 'pluginid';

		parent::__construct();
	}

	public function fetch_by_identifier($identifier) {
		return DB::fetch_first('SELECT * FROM %t WHERE identifier=%s', array($this->_table, $identifier));
	}

	public function fetch_all_identifier($identifier) {
		return DB::fetch_all('SELECT * FROM %t WHERE identifier IN (%n)', array($this->_table, $identifier), 'identifier');
	}

	public function fetch_all_data($available = false) {
		$available = $available !== false ? 'WHERE available='.intval($available) : '';
		return DB::fetch_all('SELECT * FROM %t %i ORDER BY available DESC, pluginid DESC', array($this->_table, $available));
	}

	public function fetch_all_by_identifier($identifier) {
		if(!$identifier) {
			return;
		}
		return DB::fetch_all('SELECT * FROM %t WHERE %i', array($this->_table, DB::field('identifier', $identifier)));
	}

	public function fetch_by_pluginvarid($pluginid, $pluginvarid) {
		return DB::fetch_first("SELECT * FROM %t p, %t pv WHERE p.pluginid=%d AND pv.pluginid=p.pluginid AND pv.pluginvarid=%d",
			array($this->_table, 'common_pluginvar', $pluginid, $pluginvarid));
	}

	public function delete_by_identifier($identifier) {
		if(!$identifier) {
			return;
		}
		DB::delete('common_plugin', DB::field('identifier', $identifier));
	}
	
	public function add_group_power_plugin()
	{
	    DB::query("insert %t (available, adminid, name, identifier, description, datatables, directory, copyright, modules, version) values ('1', '1', '圈子加强功能', 'grouppower', '', '', 'grouppower/', 'Wuhaguo Inc.', 'a:1:{i:0;a:11:{s:4:\"name\";s:9:\"g_setting\";s:5:\"param\";s:0:\"\";s:4:\"menu\";s:6:\"设置\";s:3:\"url\";s:0:\"\";s:4:\"type\";s:1:\"3\";s:7:\"adminid\";s:1:\"0\";s:12:\"displayorder\";s:0:\"\";s:8:\"navtitle\";s:0:\"\";s:7:\"navicon\";s:0:\"\";s:10:\"navsubname\";s:0:\"\";s:9:\"navsuburl\";s:0:\"\";}}', '1.0');
	        ", array($this->_table));
	    return DB::insert_id();
	}

}

?>