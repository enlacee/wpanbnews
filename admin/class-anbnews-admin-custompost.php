<?php

class Anbnews_Admin_CustomPost {

	private $plugin_name;
	private $version;
	public static $cpName = 'news'; // Custom post name

	/**
	* Build
	*/
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	* Create custom post
	*/
	public function create_post_type()
	{
		// Etiquetas para el Post Type
		$labels = array(
			'name'                => _x('News', 'Post Type General Name', 'anbnews'),
			'singular_name'       => _x('New', 'Post Type Singular Name', 'anbnews'),
			'menu_name'           => __('News', 'anbnews'),
			'parent_item_colon'   => __('New Father', 'anbnews'),
			'all_items'           => __('All News', 'anbnews'),
			'view_item'           => __('See New', 'anbnews'),
			'add_new_item'        => __('Add New', 'anbnews'),
			'add_new'             => __('Add New', 'anbnews'),
			'edit_item'           => __('Edit New', 'anbnews'),
			'update_item'         => __('Update New', 'anbnews'),
			'search_items'        => __('Search New', 'anbnews'),
			'not_found'           => __('Not Found', 'anbnews'),
			'not_found_in_trash'  => __('Not Found in trash', 'anbnews'),
		);

		// Otras opciones para el post type
		$args = array(
			'label'               => __('News', 'anbnews'),
			'description'         => __('News make by google.news', 'anbnews'),
			'labels'              => $labels,
			// Todo lo que soporta este post type
			'supports'            => array('title', 'editor', 'author', 'thumbnail', 'comments', 'revisions'),
			/* Un Post Type hierarchical es como las paginas y puede tener padres e hijos.
			* Uno sin hierarchical es como los posts
			*/
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-admin-post',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);

		// Por ultimo registramos el post type
		register_post_type(self::$cpName, $args);
	}

	public function create_taxonomy()
	{
		$labels = array(
			'name'              => _x('Type News', 'anbnews'),
			'singular_name'     => _x('Type New', 'anbnews'),
			'search_items'      => __('Search from category new', 'anbnews'),
			'popular_items'		=> __('Popular Category News', 'anbnews'),
			'all_items'         => __('All Category news', 'anbnews'),
			'parent_item'       => __('Type News Category Parent', 'anbnews'),
			'parent_item_colon' => __('Type News Category Parent', 'anbnews'),
			'edit_item'         => __('Edit New Category'),
			'update_item'       => __('Update Category New'),
			'add_new_item'      => __('Add Category New'),
			'new_item_name'     => __('New Category New'),
			'menu_name'         => __('Categories'),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite' => array('slug' => 'category-new'),
		);

		register_taxonomy('category-new', array(self::$cpName), $args);


		// add
		register_taxonomy(
			'category-newtag',
			self::$cpName,
			array(
				'label' => __('Tags news', 'anbnews'),
				'rewrite' => array('slug' => 'category-newtag'),
				'hierarchical' => false,
			)
		);
	}
}