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

	public function create_taxonomy()
	{
		$labels = array(
			'name'              => _x('Categories news', 'anbnews'),
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

	// crear metabox
	public function create_metabox()
	{
		add_meta_box(
			"ga-metaboxes",
			"Nuestro Metabox",
			array($this, 'ga_diseno_metaboxes'),
			self::$cpName,
			"normal",
			"high",
			null
		);
	}

	/*
	* callback : mostrar la vista del metabox
	*/
	public function ga_diseno_metaboxes($post)
	{
		wp_nonce_field(basename(__FILE__), "meta-box-nonce");
		?>
		<div>
		<label for="input-metabox">Calorias:</label>
		<input name="input-metabox" type="text"
		value="<?php echo get_post_meta($post->ID, "input-metabox", true); ?>"/>
		<br/>

		<label for="textarea-metabox">Subtitulo del Post:</label>
		<textarea name="textarea-metabox"><?php echo get_post_meta($post->ID, "textarea-metabox", true); ?></textarea>
		<br/>

		<label for="dropdown-metabox">Calificación:</label>
			<select name="dropdown-metabox">
			<?php
				$opciones = array(1, 2, 3, 4, 5);
				foreach ($opciones as $llave => $valor) {
					if ($valor == get_post_meta($post->ID, "dropdown-metabox", true)) { ?>
						<option selected><?php echo $valor; ?></option>
					<?php } else { ?>
						<option><?php echo $valor; ?></option>
					 <?php }
				} //fin foreach
			?>
			</select>
		<br>
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
			|| !wp_verify_nonce($_POST['meta-box-nonce'], basename(__FILE__))) {
			return $post_id;
		}

		if (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) {
			return $post_id;
		}

		$input_metabox = "";
		$textarea_metabox = "";
		$dropdown_metabox = "";

		// save data
		if (isset($_POST['input-metabox'])) {
			$input_metabox = $_POST['input-metabox'];
		}
		update_post_meta($post_id, 'input-metabox', $input_metabox);

		if (isset($_POST['textarea-metabox'])) {
			$input_metabox = $_POST['textarea-metabox'];
		}
		update_post_meta($post_id, 'textarea-metabox', $input_metabox);

		if (isset($_POST['dropdown-metabox'])) {
			$input_metabox = $_POST['dropdown-metabox'];
		}
		update_post_meta($post_id, 'dropdown-metabox', $input_metabox);

	}

}
