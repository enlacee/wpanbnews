<?php

class Anbnews_Admin_Table {

	private $name;

	private static $instance;

	public static function getInstance()
	{
		 if (!isset(self::$instance)) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	* construct
	*/
	public function __construct()
	{
		global $wpdb;
		$this->name = $wpdb->prefix . 'cron_noticias';
	}

	public function getName()
	{
		return $this->name;
	}

	/**
	* Crear tabla
	* return Void
	*/
	public function install()
	{
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE {$this->getName()} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
			guid varchar(50) NOT NULL UNIQUE,
			date_gmt datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			INDEX index_guid (guid)
		) $charset_collate;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	/*
	* Agregar registros de prueba
	* return void
	*/
	public function install_data()
	{
		global $wpdb;

		$wpdb->insert(
			$this->getName(),
			array(
				'guid' => '12345123451234512345123451234512345123451234512345',
				'date_gmt' => gmdate('Y-m-d H:i:s'),
			)
		);
	}

	/*
	* return void
	*/
	public function uninstall()
	{
		global $wpdb;
		$wpdb->query("DROP TABLE {$this->getName()}");
	}

	/**
	* Registrar
	* @param Int|False
	*/
	public function insert($items)
	{
		global $wpdb;
		$table = $this->getName();
		$rs = false;

		$rs = $wpdb->insert(
			$table,
			array(
				'guid' => $items['guid'],
				'date_gmt' => $items['date_gmt']
			),
			array('%s', '%s')
		);

		return $rs;
	}

	/**
	* Obtener todos los items, con guid existentes en DB
	*
	* @param Array
	* @return Array
	*/
	public function exists_guid_get_array($guid)
	{
		global $wpdb;
		$rs = array();

		if (is_array($guid) && count($guid) > 0) {
			$table = $this->getName();
			$strAdd = '';

			foreach ($guid as $key => $value) {
				$strAdd .= "'{$value}',";
			}
			// remover ,
			if (strrpos($strAdd, ",") !== false) {
				$strAdd = substr($strAdd, 0, strlen($strAdd)-1);
			}

			$sql = "SELECT id, guid FROM {$table} WHERE guid IN ($strAdd);";
			$results = $wpdb->get_results($sql, ARRAY_A);

			if (is_array($results) && count($results) > 0) {
				foreach ($results as $key => $value) {
					$rs[] = $value['guid'];
				}
			}
		}

		return $rs;
	}

	/**
	* Verificar si existe GUID registrado en DB
	*
	* @param String id
	* @return Boolean
	*/
	public function exists_guid($guid)
	{
		$rs = true;
		if (is_string($guid)) {
			$meta_key = $guid;

			$count = $wpdb->get_var($wpdb->prepare(
				"
				SELECT count(id)
				FROM $table
				WHERE guid = %s
				",
				$meta_key
			));

			if ($count == 0) {
				$rs = false;
			}
		}

		return $rs;
	}
}
