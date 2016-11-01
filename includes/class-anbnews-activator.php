<?php

/**
 * Fired during plugin activation
 *
 * @link        null
 * @since      1.0.0
 *
 * @package    Anbnews
 * @subpackage Anbnews/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Anbnews
 * @subpackage Anbnews/includes
 * @author     Anibal <anibal@pprios.com>
 */
class Anbnews_Activator {

	/*
	* Registrar metadatos al instalar el plugin
	* - install_date: Fecha en UTC general.
	*/
	public static function activate()
	{
		$plugin = Anbnews::getInstance();

		$my_options = $plugin->microData;
		$my_options['install_date'] = date('Y-d-m H:i:s');
		update_option('an_anbnews_options', $my_options);

		// crear tabla

	}

}
