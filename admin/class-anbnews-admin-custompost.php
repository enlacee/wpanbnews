<?php

class Anbnews_Admin_CustomPost {

	private $plugin_name;
	private $version;
	private $file;

	public static $cpName = 'news'; // Custom post name
	public static $taxonomyNew = 'category-new';
	public static $taxonomyNewTag = 'category-new-tag';

	/**
	* construct
	*/
	public function __construct( $plugin_name, $version, $file) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->file = $file;

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
			'supports'            => array('title', 'editor', 'thumbnail', 'revisions'),
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

	/*
	* Add taxonomy Category new
	*/
	public function create_taxonomy_new()
	{
		$labels = array(
			'name'              => _x('Categories', 'anbnews'),
			'singular_name'     => _x('Category new', 'anbnews'),
			'search_items'      => __('Search from category new', 'anbnews'),
			'popular_items'		=> __('Popular category News', 'anbnews'),
			'all_items'         => __('All category news', 'anbnews'),
			'parent_item'       => __('Type news category parent', 'anbnews'),
			'parent_item_colon' => __('Type news category parent', 'anbnews'),
			'edit_item'         => __('Edit new category'),
			'update_item'       => __('Update category new'),
			'add_new_item'      => __('Add category new'),
			'new_item_name'     => __('New category new'),
			'menu_name'         => __('Categories'),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite' => array('slug' => self::$taxonomyNew),
		);

		register_taxonomy(self::$taxonomyNew, array(self::$cpName), $args);
	}

	/*
	* Add taxonomy Tag new
	*/
	public function create_taxonomy_new_tag()
	{
		register_taxonomy(
			self::$taxonomyNewTag,
			self::$cpName,
			array(
				'label' => __('Tags news', 'anbnews'),
				'rewrite' => array('slug' => self::$taxonomyNewTag),
				'hierarchical' => false,
			)
		);
	}

	/*
	* Add taxonomy Category agency
	*/
	public function create_taxonomy_agency()
	{
		$labels = array(
			'name'              => _x('Agencies', 'anbnews'),
			'singular_name'     => _x('Agencies', 'anbnews'),
			'search_items'      => __('Search from agencies', 'anbnews'),
			'popular_items'		=> __('Popular agencies', 'anbnews'),
			'all_items'         => __('All agencies', 'anbnews'),
			'parent_item'       => __('Type agency parent', 'anbnews'),
			'parent_item_colon' => __('Type agency parent', 'anbnews'),
			'edit_item'         => __('Edit new agency'),
			'update_item'       => __('Update agency new'),
			'add_new_item'      => __('Add agency new'),
			'new_item_name'     => __('New agency new'),
			'menu_name'         => __('Agencies'),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite' 			=> array(
				'slug' => 'agencies',
				'with_front' => false, // Don't display the category base before "/locations/"
				'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
			),
		);

		register_taxonomy('agencies', array(self::$cpName), $args);

	}

	// crear metabox
	public function create_metabox()
	{
		add_meta_box(
			"an-metaboxes",
			esc_html__('Information Additional', 'anbnews'),
			array($this, 'ga_diseno_metaboxes'),
			self::$cpName,
			"normal",
			"default",
			null
		);
	}

	/*
	* callback : mostrar la vista del metabox
	*/
	public function ga_diseno_metaboxes($post)
	{
		wp_nonce_field(basename($this->file), "meta-box-nonce");
		?>
		<div>
			<label for="_anews-input-url">URL:</label>
			<input name="_anews-input-url" type="text" class="form-input-tip"
			value="<?php echo get_post_meta($post->ID, "_anews-input-url", true); ?>"/>
			<br/>

			<label for="_anews-input-pub-date">Pub Date:</label>
			<input name="_anews-input-pub-date" type="text" class="form-input-tip"
			value="<?php echo get_post_meta($post->ID, "_anews-input-pub-date", true); ?>"/>
			<br/>
		</div>
		<?php
	}

	/**
	*
	* Guardar valores de la vista metabox
	*/
	public function save_metaboxes($post_id, $post, $update)
	{
		if (!isset($_POST['meta-box-nonce'])
			|| !wp_verify_nonce($_POST['meta-box-nonce'], basename($this->file))) {
			return $post_id;
		}

		if (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) {
			return $post_id;
		}

		$input_metabox = "";
		$input_metabox2 = "";

		// save data
		if (isset($_POST['_anews-input-url'])) {
			$input_metabox = $_POST['_anews-input-url'];
		}
		update_post_meta($post_id, '_anews-input-url', $input_metabox);

		if (isset($_POST['_anews-input-pub-date'])) {
			$input_metabox2 = $_POST['_anews-input-pub-date'];
		}
		update_post_meta($post_id, '_anews-input-pub-date', $input_metabox2);

	}

	/*
	* Agregar menu
	*/
	public function add_menu()
	{
		add_menu_page(
			'Settings news',
			'Settings news',
			'manage_options', // capability *admin*
			$this->file,
			array($this, 'callback_menu_content'),
			null,
			null
		);

		// Add submenu
		add_submenu_page(
			$this->file,
			'Cron settings',
			'Cron settings',
			'manage_options', // capability *admin*
			$this->file . '_cron-settings',
			array($this, 'boj_view_cron_settings')
		);

		// Resetear el CRON: cambiar de horario
		/*
		//get time of next scheduled run
		$timestamp = wp_next_scheduled( 'boj_cron_hook');
		//unschedule custom action hook
		wp_unschedule_event( $timestamp, 'boj_cron_hook');
		*/

	}

	static function callback_menu_content()
	{
		?>
		<div class=”wrap”>
		<?php screen_icon(); ?>
		<h2>My plugin</h2>
		<form action=”options.php” method=”post”>
		</form></div>
		<?php


		do_action('an_cron_read_feed');
	}

	static function callback_submenu_cron_settings()
	{
		echo "submenu : cron visual";
	}

	/**
	* Cron action
	*/
	public function boj_cron_email_reminder()
	{
		error_log("CORREO ENVIADO a anibal@pprios.com");
	}

	// view
	public static function boj_view_cron_settings()
	{
		// verify
		if (!wp_next_scheduled('boj_cron_hook')) {
			// schedule the event to run
			//wp_schedule_event(time(), 'hourly', 'boj_cron_hook');
			wp_schedule_event(time(), 'minute', 'boj_cron_hook');
		}

		$cron = _get_cron_array();
		$schedules = wp_get_schedules();
		$date_format = 'M j, Y @ G:i';
	?>
		<div class=”wrap” id=”cron-gui”>
		<h2>Cron Events Scheduled</h2>
		<table class=”widefat fixed”>
		<thead>
		<tr>
		<th scope=”col”>Next Run (GMT/UTC)</th>
		<th scope=”col”>Schedule</th>
		<th scope=”col”>Hook Name</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ( $cron as $timestamp => $cronhooks ) { ?>
		<?php foreach ( (array) $cronhooks as $hook => $events )
		{ ?>
		<?php foreach ( (array) $events as $event ) { ?>
		<tr>
		<td>
		<?php echo date_i18n( $date_format, wp_next_scheduled( $hook ) ); ?>
		</td>
		<td>
		<?php
		if ( $event['schedule'] ) {
			echo $schedules[$event['schedule']]['display'];
		} else {
		?>One-time<?php
		}
		?>
		</td>
		<td><?php echo $hook; ?></td>
		</tr>
		<?php } ?>
		<?php } ?>
		<?php } ?>
		</tbody>
		</table>
		</div>
		<?php

	}

	/*
	* Agregr otro horario para el cron
	*/
	public function my_add_intervals($schedules)
	{
		$schedules['minute'] = array(
		'interval' => 60,
		'display' => __('Minute')
		);
		$schedules['12hours'] = array(
		'interval' => 43200,
		'display' => '12hours'
		);

		return $schedules;
	}


	/*
	* Cron Noticias
	*/
	public function cron_read_feed()
	{
		$feed  = fetch_feed('https://news.google.com.pe/news?cf=all&hl=es&pz=1&ned=es_pe&output=rss');
		// echo "<pre>";
		// var_dump(get_class_methods($feed));
		// echo "<pre>";

		$items = $feed->get_items();
		// echo $feed->get_language();
		// echo "<br>";
		// echo $feed->subscribe_url();
		// echo "<br>";
		// echo $feed->get_title();
		// echo "<br>";
		// echo $feed->get_category();
		// echo "<br>";


		if (isset($items[0]) && get_class($items[0]) == 'SimplePie_Item') {
			$transport = $items[0]->feed->data['child'];
			$mode = current($transport);
			$rss = $mode['rss'];
			$child = current($rss[0]['child']);
			$child2 = current($child['channel'][0]['child']);

			$items = $child2['item'];
			foreach ($items as $item) {
				$i_title = current($item['child'])['title'][0]['data'];
				$i_link = $this->_getLink(current($item['child'])['link'][0]['data']);
				$i_guid = current($item['child'])['guid'][0]['data'];
				$i_category = current($item['child'])['category'][0]['data'];
				$i_pubDate = current($item['child'])['pubDate'][0]['data'];
				$i_description = current($item['child'])['pubDate'][0]['data'];

				// variables post
				$term_id = $this->_getOrInsertTaxonomy($i_category);
				$my_post = array(
					'post_title'	=> $i_title,
					'post_content'	=> '',
					'post_status'	=> 'publish',
					'post_author'	=> 1,
					'post_type'		=> self::$cpName,
				);
				/*
				// Insert the post into the database.
				$post_id = wp_insert_post($my_post);
				$cat_ids = array($term_id);

				$cat_ids = array_map('intval', $cat_ids);
				$cat_ids = array_unique($cat_ids);
				wp_set_object_terms($post_id, $cat_ids, self::$taxonomyNew, false);
				wp_set_object_terms($post_id, array("tag01", "tag02"), self::$taxonomyNewTag, false);
				*/

				echo "<pre>";
				print_r($item);
				echo "</pre>";
				//break;
			}
		}
	}

	/**
	* Retornar Url
	*/
	private function _getLink($str)
	{
		$rs = false;
		if (preg_match("#url=#", $str)) {
			$part = preg_split("#url=#", $str);
			$rs= $part[1];
		}

		return $rs;
	}

	/*
	* Obtener Id de la taxonomia
	* agrega o recupera el ID del termino.
	*/
	private function _getOrInsertTaxonomy($nameCategory)
	{
		$term = term_exists($nameCategory, self::$taxonomyNew);
		if ($term !== 0 && $term !== null) {
			// existe
		} else {
			$term = wp_insert_term($nameCategory, self::$taxonomyNew);
		}

		return $term['term_id'];
	}

	/**
	* Retornar solo el nombre de la Agencia o el Diario.
	* todo: sacar solo porcion necesario con preg_split
	*/
	private function _getAgengieName($string)
	{
		return false;
	}

	/**
	* Establecer el cache a 1hora
	*/
	public function my_cache_filter_handler($seconds)
	{
		$currentSeconds = $seconds;
/*		if (getenv('APP_ENV') == 'development') {
			$currentSeconds = 30;
		} else {
			$currentSeconds = 60*60;
		}
*/
		return $currentSeconds;
	}
}
