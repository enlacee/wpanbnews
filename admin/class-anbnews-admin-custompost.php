<?php

class Anbnews_Admin_CustomPost {

	private $plugin_name;
	private $version;
	private $file;

	public static $cpName = 'noticia'; // Custom post name
	public static $taxonomyNew = 'category-new';
	public static $taxonomyNewTag = 'category-new-tag';
	public static $taxonomyAgency = 'agencia';
	public static $prefixMeta = '_anews-';

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
			'name'              => _x('Category', 'anbnews'),
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
			'name'              => _x('Agency', 'anbnews'),
			'singular_name'     => _x('Agency', 'anbnews'),
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
				'slug' => self::$taxonomyAgency,
				'with_front' => false, // Don't display the category base before "/locations/"
				'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
			),
		);

		register_taxonomy(self::$taxonomyAgency, array(self::$cpName), $args);

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
			<label for="<?php echo self::$prefixMeta ?>input-guid">GUID:</label>
			<input name="<?php echo self::$prefixMeta ?>input-guid" type="text" class="form-input-tip" disabled
			value="<?php echo get_post_meta($post->ID, self::$prefixMeta . 'input-guid', true); ?>"
			style="width:100%"/>
			<br/>

			<label for="<?php echo self::$prefixMeta ?>input-url">URL:</label>
			<input name="<?php echo self::$prefixMeta ?>input-url" type="text" class="form-input-tip" disabled
			value="<?php echo get_post_meta($post->ID, self::$prefixMeta . 'input-url', true); ?>"/>
			<br/>

			<label for="<?php echo self::$prefixMeta ?>input-pub-date">Pub Date:</label>
			<input name="<?php echo self::$prefixMeta ?>input-pub-date" type="text" class="form-input-tip" disabled
			value="<?php echo get_post_meta($post->ID, self::$prefixMeta . 'input-pub-date', true); ?>"/>
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
		if (isset($_POST[self::$prefixMeta . 'input-url'])) {
			$input_metabox = $_POST[self::$prefixMeta . 'input-url'];
		}
		update_post_meta($post_id, self::$prefixMeta . 'input-url', $input_metabox);

		if (isset($_POST[self::$prefixMeta . 'input-pub-date'])) {
			$input_metabox2 = $_POST[self::$prefixMeta . 'input-pub-date'];
		}
		update_post_meta($post_id, self::$prefixMeta . 'input-pub-date', $input_metabox2);

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
			array($this, 'display_menu_content'),
			null,
			null
		);

		// Add submenu
		add_submenu_page(
			$this->file,
			'Make crons',
			'Make crons',
			'manage_options', // capability *admin*
			$this->file . '_cron-settings',
			array($this, 'display_view_cron_settings')
		);

		// Add submenu
		add_submenu_page(
			$this->file,
			'Make cron Agregar Noticias',
			'Make cron Agregar Noticias',
			'manage_options', // capability *admin*
			$this->file . '_cron-settings-add-news',
			array($this, 'display_add_news')
		);

		// Resetear el CRON: cambiar de horario
		/*
		//get time of next scheduled run
		$timestamp = wp_next_scheduled( 'anbnews_boj_cron_hook');
		//unschedule custom action hook
		wp_unschedule_event( $timestamp, 'anbnews_boj_cron_hook');
		*/

	}

	static function display_add_news()
	{
		do_action('anbnews_cron_read_feed');
		?>
		Funcion Ejecutada!
		<?php
	}

	static function display_menu_content()
	{
		?>
		<div class=”wrap”>
		<?php screen_icon(); ?>
		<h2>My plugin</h2>
		<form action=”options.php” method=”post”>
		<h2>Configuracion en construcción</h2>
		</form></div>
		<?php
	}

	/**
	* Cron action
	*/
	public function cron_email_reminder()
	{
		error_log("CORREO ENVIADO a anibal@pprios.com" . date('Y-m-d H:i:s') . " echo anb");
	}

	public static function display_view_cron_settings()
	{
		// add cron INSERT noticias
		if (!wp_next_scheduled('anbnews_cron_read_feed')) {
			wp_schedule_event(time(), 'hourly', 'anbnews_cron_read_feed');
		}

		// verify
		if (!wp_next_scheduled('anbnews_boj_cron_hook')) {
			// schedule the event to run
			wp_schedule_event(time(), 'minute', 'anbnews_boj_cron_hook');
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
			$idsRegistered =$this->_get_list_guid_already_registered($items);

			foreach ($items as $item) {

				$i_guid = current($item['child'])['guid'][0]['data'];
				if (array_search($i_guid, $idsRegistered) === false) {

					$i_title = current($item['child'])['title'][0]['data'];
					$i_link = $this->_getLink(current($item['child'])['link'][0]['data']);
					$i_category = current($item['child'])['category'][0]['data'];
					$i_pubDate = current($item['child'])['pubDate'][0]['data'];
					$i_description = current($item['child'])['pubDate'][0]['data'];

					// read HTML
					$readOG = $this->_getOpenGraph($i_link);

					// registrar en la tabla cron *principal*
					$tableCron = Anbnews_Admin_Table::getInstance();
					$id_row = $tableCron->insert(array(
						'guid' => $i_guid,
						'date_gmt' => gmdate('Y-m-d H:i:s', strtotime($i_pubDate))
					));

					if ($id_row !== false) {
						// variables post
						$term_id = $this->_getIdTaxonomy($i_category, self::$taxonomyNew);
						$agencyName = $this->_getTitle($i_title, "category");
						$term_id2 = $this->_getIdTaxonomy($agencyName, self::$taxonomyAgency);

						$my_post = array(
							'post_title'	=> $this->_getTitle($i_title, 'main'),
							'post_content'	=> '',
							'post_status'	=> 'publish',
							'post_author'	=> 1,
							'post_type'		=> self::$cpName,
						);

						// Insert the post into the database.
						$post_id = wp_insert_post($my_post);
						// Agregar categoria new
						$cat_ids = array($term_id);
						$cat_ids = array_map('intval', $cat_ids);
						$cat_ids = array_unique($cat_ids);
						wp_set_object_terms($post_id, $cat_ids, self::$taxonomyNew, false);
						// Agregar categoria agency
						$cat_ids2 = array($term_id2);
						$cat_ids2 = array_map('intval', $cat_ids2);
						$cat_ids2 = array_unique($cat_ids2);
						wp_set_object_terms($post_id, $cat_ids2, self::$taxonomyAgency, false);
						// wp_set_object_terms($post_id, array("tag01", "tag02"), self::$taxonomyNewTag, false);
						// agregar metadatos
						add_post_meta($post_id, self::$prefixMeta .'input-guid', $i_guid, true);
						add_post_meta($post_id, self::$prefixMeta .'input-url', $i_link);
						add_post_meta($post_id, self::$prefixMeta .'input-image-url', $readOG['img']);
						add_post_meta($post_id, self::$prefixMeta .'input-description', $readOG['description']);
						add_post_meta($post_id, self::$prefixMeta .'input-pub-date', $i_pubDate);

					} else {
						error_log("Fallo al registrar 1 registro en la tabla ". $tableCron->getName());
					}
				}

			}
		}
	}

	/**
	* Obtener ids
	*
	* return Array
	*/
	private function _get_guids($items)
	{
		$rs = array();
		if (is_array($items) && count($items) > 0) {
			foreach ($items as $item) {
				$i_guid = current($item['child'])['guid'][0]['data'];
				$rs[] = $i_guid;
			}
		}

		return $rs;
	}

	/**
	* lista de ids ya registrados
	*
	* @param String|Array ids
	* @return Array
	*/
	private function _get_list_guid_already_registered($items)
	{
		// lista de ids FEED
		$ids = $this->_get_guids($items);
		$tableCron = Anbnews_Admin_Table::getInstance();

		return $tableCron->exists_guid_get_array($ids);
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
	* @param String
	* @param String
	*/
	private function _getIdTaxonomy($nameCategory, $taxonomyName)
	{
		$term = term_exists($nameCategory, $taxonomyName);
		if ($term !== 0 && $term !== null) {
			// existe
		} else {
			$term = wp_insert_term($nameCategory, $taxonomyName);
		}

		// return false *error*
		if (is_a($term, 'WP_Error')) {
			return false;
		}

		return $term['term_id'];
	}

	/**
	* Retornar solo el nombre de la Agencia o el Diario.
	* todo: sacar solo porcion necesario con preg_split
	*/
	private function _getTitle($string, $slug)
	{
		$rs = false;
		$start = strrpos($string, "-");
		if ($start !== false) {
			if ($slug == 'category') {
				$rs = trim(substr($string, $start+1));
			} else if ($slug == 'main') {
				$rs = trim(substr($string, 0, $start));
			}
		}

		return $rs;
	}

	/**
	* Obtener HTML Y obtener OpenGraph metas
	*/
	private function _getOpenGraph($url)
	{
		$html = file_get_contents($url);

		/* get page's description */
		$re ="<meta\s+property=['\"]??og:image['\"]??\s+content=['\"]??(.+)['\"]??\s*\/?>";
		preg_match("/$re/siU", $html, $matches);
		$img = $matches[1];

		$re="<meta\s+name=['\"]??description['\"]??\s+content=['\"]??(.+)['\"]??\s*\/?>";
		preg_match("/$re/siU", $html, $matches);
		$desc = $matches[1];

		$info = array(
			"img" => $img,
			"description" => $desc,
		);

		return $info;
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
