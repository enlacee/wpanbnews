<?php

class Anbnews_Admin_CustomPost {

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	* Create custom post
	*/
	public function create_post_type()
	{
		register_post_type('news',
			array(
				'labels' => array(
				'name' => __( 'New' ),
				'singular_name' => __( 'New' )
				),
				'public' => true,
				'has_archive' => true,
			)
		);
	}
}