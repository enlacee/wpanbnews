<?php

class Anbnews_Admin_Table {

	private $name;

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
}
