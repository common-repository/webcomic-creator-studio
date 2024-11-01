<?php

/*

This file defines the class webComicCreatorStudio, which has all the functions associated with the Web Comic Creator software,
including the functions that are run upon plug-in activation and deactivation and uninstall.

*/

define( "WEBCOMIC_CHAR_MAXWIDTH", "120" );
define( "WEBCOMIC_CHAR_MAXHEIGHT", "180" );
define( "WEBCOMIC_BG_HEIGHT", "290" );
define( "WEBCOMIC_BG_WIDTH", "284" );
define( "WEBCOMIC_HEIGHT", "345" );
define( "WEBCOMIC_WIDTH", "906" );
define( "WEBCOMIC_TITLE", "Webcomic Creator Studio" );
define( "WEBCOMIC_DISCLAIMER", "This comic was created by Webcomic Creator Studio." );
define( "WEBCOMIC_DEFAULT_PREVIEW", "A brand new webcomic is coming up next!" );


class Webcomic_Creator_Studio {
	
	private $plugin_name = "Webcomic Creator Studio";
	private $menu_parameter = "webcomic-creator-studio";
	private $upload_dir = 'webcomic_images';

	
	
	
	function webcomic_deactivation() {
		//$this->remove_tables();
		$this->remove_tasks();
	}
	function webcomic_uninstall() {
		$this->remove_meta();
		$this->remove_tables();	//remove the tables from the database, destroying all stored data
	}

	function webcomic_activation() {
		$this->create_tables();
		$this->create_defaults();	
		$this->create_meta();
		$this->add_default_chars();

	}
	function register_all_settings() {
		register_setting( $this->menu_parameter, 'webcomic_height', array($this,'webcomic_options_height_validate') );
		register_setting( $this->menu_parameter, 'webcomic_width', array($this,'webcomic_options_width_validate') );
		register_setting( $this->menu_parameter, 'webcomic_title', array($this,'webcomic_options_title_validate') );
		register_setting( $this->menu_parameter, 'webcomic_url', array($this,'webcomic_options_url_validate') );
		register_setting( $this->menu_parameter, 'webcomic_category', array($this,'webcomic_options_category_validate') );
		register_setting( $this->menu_parameter, 'webcomic_tags', array($this,'webcomic_name_validate') );
		register_setting( $this->menu_parameter, 'webcomic_update', array($this,'webcomic_options_update_validate') );
		register_setting( $this->menu_parameter, 'webcomic_disclaimer', array($this,'webcomic_options_disclaimer_validate') );
		register_setting( $this->menu_parameter, 'webcomic_empty_preview', array($this,'webcomic_options_disclaimer_validate') );
		add_settings_section( $this->menu_parameter, 'Main Settings', array($this,'webcomic_settings_text'), $this->menu_parameter );
		add_settings_field( 'webcomic-setting-width', 'Comic Width (px)', array($this,'webcomic_settings_width'), $this->menu_parameter, $this->menu_parameter );
		add_settings_field( 'webcomic-setting-height', 'Comic Height (px)', array($this,'webcomic_settings_height'), $this->menu_parameter, $this->menu_parameter );
		add_settings_field( 'webcomic-setting-title', 'Comic Title', array($this,'webcomic_settings_title'), $this->menu_parameter, $this->menu_parameter );
		add_settings_field( 'webcomic-setting-url', 'Comic URL', array($this,'webcomic_settings_url'), $this->menu_parameter, $this->menu_parameter );
		add_settings_field( 'webcomic-setting-category', 'Comic Category Name', array($this,'webcomic_settings_category'), $this->menu_parameter, $this->menu_parameter );
		add_settings_field( 'webcomic-setting-update', 'Add Tags For', array($this,'webcomic_settings_tags'), $this->menu_parameter, $this->menu_parameter);
		add_settings_field( 'webcomic-setting-update', 'Update Schedule', array($this,'webcomic_settings_update'), $this->menu_parameter, $this->menu_parameter);
		add_settings_field( 'webcomic-setting-disclaimer', 'Optional Text Beneath Comic (Disclaimer, Explanation etc.)', array($this,'webcomic_settings_disclaimer'), $this->menu_parameter, $this->menu_parameter );
		add_settings_field( 'webcomic-setting-empty-preview', 'Text to Display if no Preview is Available', array($this,'webcomic_settings_empty_preview'), $this->menu_parameter, $this->menu_parameter );


	}
	function remove_tasks() {
		wp_clear_scheduled_hook('webcomic_register_update');
	}
	function create_meta() {
		 //$this->add_category( 'queued' );
		 //$this->add_category( 'unqueued' );
		 $this->add_category( 'webcomic' );
		 update_option( 'webcomic_category','webcomic' );
		 update_option( 'webcomic_update', array( 1, 1, 1, 1, 1, 0, 0 ) );
		 
		 
	}
	function remove_meta() {
		$this->remove_category();
	}
	public function add_category( $category_name ) {
		wp_create_category( $category_name );
	}
	public function remove_category() {
		$category_name = trim( get_option('webcomic_category') );
		$category_info = get_term_by( 'name', $category_name, 'category' );
		$category_id = intval( $category_info->{'term_id'} ); //get the id of the 'Webcomic' category
		if ( $category_id > 0 ) {
			wp_delete_category( $category_id );
		}
	}
	function create_defaults() {
		
		add_option( 'webcomic_char_maxwidth', WEBCOMIC_CHAR_MAXWIDTH );
		add_option( 'webcomic_char_maxheight', WEBCOMIC_CHAR_MAXHEIGHT );
		add_option( 'webcomic_bg_height', WEBCOMIC_BG_HEIGHT );
		add_option( 'webcomic_bg_width', WEBCOMIC_BG_WIDTH );
		add_option( 'webcomic_height', WEBCOMIC_HEIGHT );
		add_option( 'webcomic_width', WEBCOMIC_WIDTH );
		add_option( 'webcomic_title', WEBCOMIC_TITLE );
		add_option( 'webcomic_url', get_home_url() );
		add_option( 'webcomic_disclaimer', WEBCOMIC_DISCLAIMER );
		add_option( 'webcomic_empty_preview', WEBCOMIC_DEFAULT_PREVIEW );
	}
	
	function add_default_chars() {
		global $wpdb;
		
		//add chars
		
		$table_name = $wpdb->prefix . "comiccharacters"; 
		$insert = "INSERT INTO " . $table_name .
            " (id, name, flag) " .
            "VALUES ('-1', 'Doctor_Jones', '0')";

		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name .
            " (id, name, flag) " .
            "VALUES ('-2', 'Ms_Johnson', '0')";

		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name .
            " (id, name, flag) " .
            "VALUES ('-3', 'Slick_Carl', '0')";

		$results = $wpdb->query( $insert );
		$query = "ALTER " . $table_name . " AUTO_INCREMENT = 10"; //set auto increment to start on 10
		$results = $wpdb->query( $query );
		
		//add char images
		$image_path = plugin_dir_url( __FILE__ ) . 'img/';
		$table_name = $wpdb->prefix . "comiccharimgs"; 
		$insert = "INSERT INTO " . $table_name .
            " (id, aid, charid, src, flag) " .
            "VALUES ('-1', '-1', '-1', '" . $wpdb->escape( $image_path . 'doctor-jones.png' ) . "', '0')";

		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name .
            " (id, aid, charid, src, flag) " .
            "VALUES ('-2', '-2', '-2', '" . $wpdb->escape( $image_path . 'ms-johnson.png' ) . "', '0')";

		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name .
            " (id, aid, charid, src, flag) " .
            "VALUES ('-3', '-3', '-3', '" . $wpdb->escape( $image_path . 'slick-carl.png' ) . "', '0')";

		$results = $wpdb->query( $insert );		
		$query = "ALTER " . $table_name . " AUTO_INCREMENT = 10"; //set auto increment to start on 10
		$results = $wpdb->query( $query );
	}
	
	function create_tables () {
		
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' ); //for table creation
		
		$table_name = $wpdb->prefix . "comicbgs"; 
	   
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
		  id int(9) NOT NULL AUTO_INCREMENT,
		  aid int(9) NOT NULL,
		  name varchar(64) NOT NULL,
		  src varchar(128) NOT NULL,
		  flag int(11) NULL,
		  UNIQUE KEY id (id)
		) $charset_collate;";

		dbDelta( $sql );
		
		$table_name = $wpdb->prefix . "comiccharacters"; 
	   
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
		  id int(9) NOT NULL AUTO_INCREMENT,
		  name varchar(64) NOT NULL,
		  description varchar(1024) NULL,
		  flag int(11) NULL,
		  UNIQUE KEY id (id)
		) $charset_collate;";

		dbDelta( $sql );
		
		$table_name = $wpdb->prefix . "comiccharimgs"; 
	   
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
		  id int(9) NOT NULL AUTO_INCREMENT,
		  aid int(9) NOT NULL,
		  charid int(9) NOT NULL,
		  src varchar(512) NOT NULL,
		  flag int(11) NULL,
		  UNIQUE KEY id (id)
		) $charset_collate;";

		dbDelta( $sql );
		
		

	}
	
	
	function remove_tables() {

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' ); 
        global $wpdb;
		
		$table_array = array( 'comicbgs', 'comiccharacters', 'comiccharimgs' );
		foreach( $table_array as $this_table_name ) {
  
			$table_to_remove = $wpdb->prefix.$this_table_name;
			$wpdb->query( "DROP TABLE IF EXISTS $table_to_remove" );

		}
	}
	function remove_options() {
		delete_option( 'webcomic_char_maxwidth' );
		delete_option( 'webcomic_char_maxheight' );
		delete_option( 'webcomic_bg_height' );
		delete_option( 'webcomic_bg_width' );
		delete_option( 'webcomic_height' );
		delete_option( 'webcomic_width' );
		delete_option( 'webcomic_title' );
		delete_option( 'webcomic_url' );
		delete_option( 'webcomic_disclaimer' );
		delete_option( 'webcomic_empty_preview' );
	}
	function webcomic_name_validate ( $input ) { //verification for character names and background names
		$verified_input = trim( $input );
		$verified_input = substr( $verified_input, 0, 256 ); //length of character name/bg name is capped at 256 characters
		$verified_input = preg_replace( "([^\w\s\d\-_~'\.\$\:/\!&\[\]\?%])", '', $verified_input );
		return( $verified_input );
	}
	function webcomic_options_width_validate( $input ) { //verification for width settings. only accept numbers
		$verified_input = trim( $input );
		if ( ( !is_numeric($verified_input ) ) || ( $verified_input < 100) ) {
			$verified_input = get_option( 'webcomic_width' );
		}
		if ( is_numeric( $verified_input ) ) {
			return( $verified_input );
		}
	}
	function webcomic_options_height_validate( $input ) { //verification for height settings. only accept numbers
		$verified_input = trim( $input );
		if ( ( !is_numeric( $verified_input ) ) || ( $verified_input < 50 ) ) {
			$verified_input = get_option( 'webcomic_height' );
		}
		if ( is_numeric( $verified_input ) ) {
			return( $verified_input );
		}
	}
	function webcomic_options_title_validate( $input ) { //verification for title settings.
		$verified_input = trim( $input );
		$verified_input = substr( $verified_input, 0, 2048 ); //length of title is capped at 2048 characters
		$verified_input = preg_replace( "([^\w\s\d\-_\!\?~,\"'\:\*\$%\[\]\(\)/\.])", '', $verified_input );
		return( $verified_input );
	}
	function webcomic_options_url_validate( $input ) { //verification for url settings.
		$verified_input = trim( $input );
		$verified_input = substr( $verified_input, 0, 2048 ); //length of url is capped at 2048 characters
		$verified_input = preg_replace( "([^\w\s\d\-_~\.\:/])", '', $verified_input );
		return( $verified_input );
	}
	function webcomic_options_category_validate( $input ) { //verification for category settings.
		$verified_input = trim( $input );
		$verified_input = substr( $verified_input, 0, 256 ); //length of category is capped at 256 characters
		$verified_input = strtolower( preg_replace( "([^\w\s\d\-_~\.\:/\!])", '', $verified_input ) );
		if ($verified_input) { //remove the old category and add a new one
			$category_name = trim( get_option( 'webcomic_category' ) );
			$category_name = preg_replace( "([^\w\s\d\-_~\.\:/\!])", '', $category_name );
			if ( $category_name != $verified_input ) {
				$this->remove_category();
				$this->add_category( $verified_input );
			}
		}
		return( $verified_input );
	}
	function webcomic_options_disclaimer_validate( $input ) { //verification for disclaimer text.
		$verified_input = trim( $input );
		$verified_input = substr( $verified_input, 0, 16384 ); //length of category is capped at 16384 characters
		$verified_input = preg_replace( "([^\w\s\d\-_\!\?~,\:\"'\*\^\$;&\=\+\<\>%\[\]\(\)/\.])", '', $verified_input );
		
		return( $verified_input );
	}
	function webcomic_options_caption_validate( $input ) { //verification for caption settings.
		$verified_input = trim( $input );
		$verified_input = substr( $verified_input, 0, 256 ); //length of caption is capped at 256 characters
		$verified_input = preg_replace( "([^\w\s\d\-_\!\?~,\$\"'\*\:%\[\]\(\)/\.])", '', $verified_input );
		return( $verified_input );
	}
	function webcomic_options_update_validate( $input ) { //verification for update days.
		$verified_input = array();
		if( !is_array( $input ) || empty( $input ) || ( false === $input ) ) {
			return array( 0, 0, 0, 0, 0, 0, 0 );
		}
		for ( $i = 0; $i <= 6; $i++ ) {
			//$this_option = "webcomic_updates[$i]";
			if( isset( $input[$i] ) && ( 1 == $input[$i] ) ) {
				$verified_input[$i] = 1;
				
			}
			else {
				$verified_input[$i] = 0;
			}
		}
		update_option( 'webcomic_updates', $verified_input ); //update the option in wordpress
		unset( $input );
		$this->webcomic_schedule_updates( $verified_input );
		return( $verified_input );
	}
	
	function webcomic_schedule_updates( $schedule ) {
		$time_array = array( 'next monday', 'next tuesday', 'next wednesday', 'next thursday', 'next friday', 'next saturday', 'next sunday' );
		$this->remove_tasks(); //unschedule all currently scheduled updates
		for ( $i = 0; $i <= 6; $i++ ) {
			if ( $schedule[$i] == 1 ) {
				wp_schedule_event( strtotime( $time_array[$i] ), 'weekly', 'webcomic_register_update_schedule' );
			}
			
		}
	}
	public function webcomic_register_update() { //function to add comics to the site from the queue
	
	
		$args=array(
			'post_type'			=> 'wc-webcomic',
			'meta_key'			=> 'queue_position',
			'meta_value'		=> '1',
			//'orderby'			=> 'meta_value_num',
			//'order'			=> 'ASC',
			'queue_status' 		=> 'queued',


		);

		$top_of_queue = get_posts( $args );
		
		if ( $top_of_queue ) {	//only proceed if there is a comic in the queue

			$this_post = array_shift( $top_of_queue );

			//change this post queue_status to "published"
			$comic_id = $this_post->ID;
			set_post_type( $comic_id, 'post' ); //change it to a post
			wp_set_object_terms( $comic_id, 'published', 'queue_status', false ); //change queue_status to published
			delete_post_meta( $comic_id, 'queue_position' ); //delete the queue position property
			$webcomic_category = get_option( 'webcomic_category' ); //get the category name for webcomics
			$webcomic_category = strtolower( preg_replace( "([^\w\s\d\-_~.:/!])", '', $webcomic_category ) ); //safety check
			$category_id = get_cat_ID( $webcomic_category );
			wp_set_post_categories( $comic_id, array( $category_id ), false );
			 
			$this->webcomic_adjust_queue_positions(); //move all the comics left in the queue up 1 position
			
		}
		
	}
	
	function add_bg_to_database( $name, $src, $aid, $flag ) {
		if ( ! current_user_can( 'edit_others_posts' ) ) { //only allow access to "editor" or higher
			return( false );
		}
		if ( !( $src ) || !( $aid ) ) { //these are required fields
			return( false );
		}
		if ( !is_numeric( $aid ) ) { //attachment id must be a number
			return( false );
		}
		if ( !( $name ) ) {
			$name = "untitled";
		}
		global $wpdb;
		$table_name = $wpdb->prefix . "comicbgs"; 
		$insert = "INSERT INTO " . $table_name .
            " (name, src, aid, flag) " .
            "VALUES ('" . $wpdb->escape( $name ) . "','" . $wpdb->escape( $src ) . "','" . $wpdb->escape( $aid ) . "','" . $wpdb->escape( $flag ) . "')";

		$results = $wpdb->query( $insert );
		return( $results );
	}
	function add_char_img_to_database( $charid, $src, $aid, $flag ) { //add a character image to the database
		
		if ( ! current_user_can( 'edit_others_posts' ) ) { //only allow access to "editor" or higher
			return( false );
		}
		if ( !( $src ) || !( $aid ) || !( $charid ) ) { //these are required fields
			return( false );
		}
		if ( !is_numeric( $aid ) || !is_numeric( $charid ) ) { //attachment id, char id must be a number
			return( false );
		}
		global $wpdb;
		$table_name = $wpdb->prefix . "comiccharimgs"; 
		$insert = "INSERT INTO " . $table_name .
            " (aid, charid, src, flag) " .
            "VALUES ('" . $wpdb->escape( $aid ) . "','" . $wpdb->escape( $charid ) . "','" . $wpdb->escape( $src ) . "','" . $wpdb->escape( $flag ) . "')";

		$results = $wpdb->query( $insert );
		return( $results );
	}
	function update_bg_in_database( $name, $aid, $flag ) {
		
		if ( ! current_user_can( 'edit_others_posts' ) ) { //only allow access to "editor" or higher
			return( false );
		}
		
		if ( !is_numeric( $aid ) ) { //attachment id must be a number
			return( false );
		}
		global $wpdb;
		$table_name = $wpdb->prefix . "comicbgs"; 
		$results = $wpdb->update( $table_name, array( "name" => $name, "flag" => $flag ), array( "aid" => $aid ), array( "%s", "%d" ), array( "%d" ) );
		return( $results );
	}
	
	function public_assets( $hook ) {
		wp_enqueue_style( 'webcomic_main_css', plugin_dir_url( __FILE__ ) . 'webcomic-style.css' );
	}
	
	function admin_assets( $hook ) {
		if( 'settings_page_webcomic-creator-studio' == $hook ) {
			wp_enqueue_style( 'webcomic_creator_admin_css', plugin_dir_url( __FILE__ ) . 'webcomic-admin-style.css' );
			wp_enqueue_script( 'webcomic_creator_js', plugin_dir_url( __FILE__ ) . 'js/webcomic-admin-script.js', array( 'jquery' ), '1.0.0', true );
			wp_localize_script( 'webcomic_creator_js', 'webcomic_vars', 
				array(
					//To use this variable in javascript use "webcomic_vars.ajaxurl"
					'ajaxurl' => admin_url( 'admin-ajax.php' ),

				) 
			);  
			
		}
		else if ( 'wc-webcomic_page_create-new-webcomic' == $hook ) {
			wp_enqueue_style( 'webcomic_creator_new_css', plugin_dir_url( __FILE__ ) . 'webcomic-admin-style.css' );
			wp_enqueue_script( 'webcomic_creator_workshop_new_js', plugin_dir_url( __FILE__ ) . 'js/webcomic-create.js', array( 'jquery' ), '1.0.0', true );
			wp_localize_script( 'webcomic_creator_workshop_new_js', 'webcomic_vars', 
				array(
					//To use this variable in javascript use "webcomic_vars.ajaxurl"
					'ajaxurl' => admin_url( 'admin-ajax.php' ),

				) 
			);  
			
		}
		else if ( 'wc-webcomic_page_webcomic-queue' == $hook ) {
			wp_enqueue_script( 'webcomic_creator_workshop_queue_js', plugin_dir_url( __FILE__ ) . 'js/webcomic-queue.js', array( 'jquery' ), '1.0.0', true );
			wp_localize_script( 'webcomic_creator_workshop_queue_js', 'webcomic_vars', 
				array(
					//To use this variable in javascript use "webcomic_vars.ajaxurl"
					'ajaxurl' => admin_url( 'admin-ajax.php' ),

				) 
			);  
			wp_enqueue_style( 'webcomic_creator_queue_css', plugin_dir_url( __FILE__ ) . 'webcomic-queue-style.css' );
		}
			

	}
	function upload_js_assets() {
			wp_enqueue_script( 'media-upload' );
			wp_enqueue_script( 'thickbox' );
			wp_register_script( 'webcomic-media-uploader-functions', plugin_dir_url( __FILE__ ) .  'js/webcomic-media-upload.js' );
			wp_register_script( 'webcomic-media-uploader-jquery', plugin_dir_url( __FILE__ ) .  'js/webcomic-media-upload-jquery.js',
			   array( 'jquery', 'media-upload', 'thickbox', 'webcomic-media-uploader-functions' )
			);
			wp_enqueue_script( 'webcomic-media-uploader-jquery' );
		
	}
	function upload_style_assets() {

			wp_enqueue_style( 'thickbox' );
		
	}
	public function webcomic_settings_text() {
		echo '<p>Your Comic Strip must be a minimum of 100 pixels wide and 50 pixels high, but much greater values are recommended. If you\'re unsure, just leave it at the default of 345 pixels high and 906 pixels wide.</p>';
	}
	public function webcomic_settings_width() {
		$option = get_option( 'webcomic_width' );
		if ( is_numeric( $option ) ) {
			echo '<input id="webcomic-settings-width" name="webcomic_width" type="text" value="'. esc_attr( $option ) .'" />';
		}
	}
	public function webcomic_settings_height() {
		$option = get_option( 'webcomic_height' );
		if ( is_numeric( $option ) ) {
			echo '<input id="webcomic-settings-height" name="webcomic_height" type="text" value="'. esc_attr( $option ) .'" />';
		}
	}
	public function webcomic_settings_title() {
		$option = get_option( 'webcomic_title' );
		$option = $this->webcomic_options_title_validate( $option );
		echo '<input id="webcomic-settings-title" name="webcomic_title" type="text" value="'. esc_attr( $option ) .'" />';
	}
	public function webcomic_settings_url() {
		$option = get_option( 'webcomic_url' );
		$option = $this->webcomic_options_url_validate( $option );
		echo '<input id="webcomic-settings-url" name="webcomic_url" type="text" value="'. esc_attr( $option ) .'" />';
	}
	public function webcomic_settings_category() {
		$option = get_option( 'webcomic_category' );
		$option = strtolower( preg_replace( "([^\w\s\d\-_~.:/!])", '', $option ) );
		echo '<input id="webcomic-settings-category" name="webcomic_category" type="text" value="'. esc_attr( $option ) .'" />';
	}
	public function webcomic_settings_disclaimer() {
		$option = get_option( 'webcomic_disclaimer' );
		$option = $this->webcomic_options_disclaimer_validate( $option );
		echo '<textarea id="webcomic-settings-disclaimer" name="webcomic_disclaimer" rows="3" cols="80">' . esc_html( $option ) . '</textarea>';
	}
	public function webcomic_settings_empty_preview() {
		$option = get_option( 'webcomic_empty_preview' );
		$option = $this->webcomic_options_disclaimer_validate( $option );
		echo '<textarea id="webcomic-settings-empty-preview" name="webcomic_empty_preview" rows="2" cols="80">' . esc_html( $option ) . '</textarea>';
	}
	public function webcomic_settings_tags() {
		echo '<input name="webcomic_tags[0]" type="checkbox" value="1"';
		
		echo ' />';
		
		echo '<input name="webcomic_tags[1]" type="checkbox" value="1"';
		
		echo ' />';
	}
	public function webcomic_settings_update() {
		$options = get_option( 'webcomic_updates' );	//an array, length 7, of 0s or 1s for whether or not there's an update on that corresponding day from $days
		if ( !$options ) {
			$options = array( 0, 0, 0, 0, 0, 0, 0 );
		}
		$days = array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' );

		
		$i = 0; //loop counter
		foreach( $options as $option ) {
			if ( ($option == 0) || ($option == 1) ) { //0 or 1 are the only acceptable settings for a day's update value
				$option_name = "webcomic_update[$i]";  
				echo '<div class="webcomic-update-box"><div class="webcomic-update-day">' . $days[$i] . '</div><div class="webcomic-update-checkbox">';
				echo '<input name="' . $option_name . '" type="checkbox" value="1"';
				if ($option == 1) {	//option will be 1 for each day that's already set for an update
					echo ' checked';
				}
				echo ' />';
				echo '</div></div>';
				
				
			}
			
			$i++;
			
		}
		
	}
	public function initialize_menu() {
	
		
		//this first javascript forces the media uploader to default to the "upload" tab instead of the "media library" tab.
		?>
		<script type="text/javascript">
		jQuery(document).ready(function($){
		wp.media.controller.Library.prototype.defaults.contentUserSetting=false;
		});
		</script>

		<input type="hidden" name="webcomic-admin-page-nonce" id="webcomic-admin-page-nonce" value="<?php echo wp_create_nonce( 'webcomic-admin-nonce' ); ?>"/>
		<div class="wrap">
			<h1>Webcomic Creator Studio Admin Page</h1>
			
			

			<h2 class="nav-tab-wrapper">

				<a class="nav-tab <?php echo $active_tab == 'display_options' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url() ?>index.php?page=webcomic-creator-settings">Comic Settings</a>
				<a class="nav-tab <?php echo $active_tab == 'display_options' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url() ?>index.php?page=webcomic-creator-characters">Characters</a>
				<a class="nav-tab <?php echo $active_tab == 'display_options' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url() ?>index.php?page=webcomic-creator-backgrounds">Backgrounds</a>
			</h2>
			<div id="sections">
				<section>
					<div id="webcomic-settings-header"><p>Thank you for using <strong><?php echo $this->plugin_name;?></strong>. 
					This system is designed to allow you to publish a webcomic with a minimal amount of effort and/or artistic ability.</p>
					<p>To get started, create some characters and add images for them. The characters should be facing to the right.
					You may also want to upload background images. Your characters will need to be .png files or .gif files with transparent backgrounds in order for the background images to show up behind them unobstructed.
					To create or queue comics, there is a special "Webcomics" menu in your main wordpress menu.</p>
					<p>The shortcode <strong>[webcomic-preview]</strong> will display the first panel of the next comic on the queue, or if displayed on an archived comic, it will display the first panel of the next comic after that one.</p>
					<p>The shortcode <strong>[webcomic-display-comic]</strong> will display your most recently published comic with the disclaimer and navigation links. To display only the comic, use <strong>[webcomic-display-comic-no-nav]</strong>.</p>
					<?php 
						$webcomic_category = $this->webcomic_options_category_validate( trim( get_option( 'webcomic_category' ) ) );
						$category_id = intval( get_cat_ID( $webcomic_category ) );
						 $category_link = get_category_link( $category_id );
					?>
					<p>Your comics are listed under the Category "<?php echo $webcomic_category; ?>". Here is a link to that category on your site: <a href="<?php echo esc_url( $category_link ); ?>" title="<?php echo $webcomic_category; ?>"><?php echo esc_url( $category_link ); ?></a></p>
					
					<p>Adjust the following settings to fine-tune your comic.</p></div>
					<div id="webcomic-settings-options">
						<form action="options.php" method="post">
						<?php settings_fields( $this->menu_parameter ); ?>
						<?php do_settings_sections( $this->menu_parameter ); ?>
						<!--
						<div class="webcomic-settings-item">
							<strong>Comic Width: </strong><input type="text" id="webcomic-settings-width" name="webcomic-settings-width" value="<?php echo esc_attr( $comic_width ); ?>"/>
						</div>
						<div class="webcomic-settings-item">
							<strong>Comic Height: </strong><input type="text" id="webcomic-settings-height" name="webcomic-settings-height" value="<?php echo esc_attr( $comic_height ); ?>"/>
						</div>
						<div class="webcomic-settings-item">
							<strong>Comic Title: </strong><input type="text" id="webcomic-settings-title" name="webcomic-settings-title" value="<?php echo esc_attr( $comic_title ); ?>"/>
						</div>
						<div class="webcomic-settings-item">
							<strong>Comic Main URL: </strong><input type="text" id="webcomic-settings-url" name="webcomic-settings-url" value="<?php echo esc_attr( $comic_url ); ?>"/>
						</div>
						-->
						<div class="webcomic-settings-submit">
							<input name="Submit" type="submit" value="<?php esc_attr_e( ' Save Changes ' ); ?>" />
						</div>
						</form>
					</div>
				
				</section>
				<section>
					<p>Characters should be facing right. A mirror image of your character facing left will automatically be created.</p>
					<div class="webcomic-chars">
					<?php 
						$this->show_character_add();
						$this->show_character_list(); 
						
						
					?>
					</div>
					
				</section>
				<section>
					<p>If you add background images, you will be able to choose from them in each panel when you're creating a comic.</p>
					
					<div class="webcomic-bgs"><?php 
					
						$this->show_all_backgrounds();
					
					?>
					</div><div style="clear:both;"></div>
					<a href="javascript:;" class="webcomic-upload-media webcomic-admin-button" id="webcomic-upload-background">Upload a Background Image</a>
				</section>
			</div>



		
  <?php
	}
	
	public function create_admin_menu() {
		add_options_page( $this->plugin_name, $this->plugin_name, 'manage_options', $this->menu_parameter, array( $this,'initialize_menu' ) );
		//add_menu_page( $this->plugin_name, 'New Comic', 'manage_options', 'create-new-webcomic', array($this,'initialize_creation'), '', 6.427 );
		add_submenu_page( 'edit.php?post_type=wc-webcomic', $this->plugin_name . ' New Comic', 'New Comic', 'manage_options', 'create-new-webcomic', array( $this,'initialize_creation' ) );
		add_submenu_page( 'edit.php?post_type=wc-webcomic', $this->plugin_name . ' Comic Queue', 'Comic Queue', 'manage_options', 'webcomic-queue', array( $this,'initialize_queue' ) );
		//register the settings used for the admin page
		$this->register_all_settings();
	}
	

	public function plugin_admin_init(){
		//register_setting( 'plugin_options', 'plugin_options', 'plugin_options_validate' );
		
	}
	
	
	public function webcomic_get_wp_version() {
	   global $wp_version;
	   return $wp_version;
	}
	
	public function webcomic_tab_change(){

		$security = trim( $_POST["security"] );
		if ( ! wp_verify_nonce( $security, 'webcomic-admin-nonce' ) ) {
			exit( "Security Check Failure: Invalid Nonce" );
		}
		
		//Get the post data 
		$current_tab = trim( $_POST["current_tab"] );
		
		if ( is_numeric( $current_tab ) ) {

			update_option( 'webcomic_admin_page',$current_tab );

			//Create the array we send back to javascript here
			$ajax_reply = array( 'reply' => "Option Update Successful to " . $current_tab );

			//Make sure to json encode the output because that's what it is expecting
			echo json_encode( $ajax_reply );
		}

		exit(); 

	}
	
	public function webcomic_admin_scripts_init() {
	   if( $this->webcomic_is_plugin_page() ) {
		   update_option( 'webcomic_img_upload', '1' );
		  //double check for WordPress version and function exists
		  if ( function_exists( 'wp_enqueue_media' ) && version_compare( $this->webcomic_get_wp_version(), '3.5', '>=' ) ) {
			 //call for new media manager
			 wp_enqueue_media();
		  }
		  //old WP < 3.5
		  else {
			 wp_enqueue_script( 'media-upload' );
			 wp_enqueue_script( 'thickbox' );
			 wp_enqueue_style( 'thickbox' );
		  }

		  wp_register_script( 'webcomic_media_uploader', WP_PLUGIN_URL . '/webcomic-creator-studio/js/webcomic-upload-media.js' );
		  wp_enqueue_script( 'webcomic_media_uploader', array( 'jquery' ), '1.0.0', true );
		  
		  wp_enqueue_style( 'media' );
		  
		  //check if an image is being deleted off the backend
		  $bg_delete = $this->webcomic_check_for_bg_delete();
		  if ( $bg_delete ) {
			  //this space for future potential updates
		  }
		  
	   }
	   else {
		   update_option( 'webcomic_img_upload', '0' );
	   }
	}
	
	
	public function update_attachment( $attachment_id ) {
		
		if ( ! current_user_can( 'edit_others_posts' ) ) { //only allow access to "editor" or higher
			return( false );
		}
		
		global $wpdb;
		
		$imagepath = get_attached_file( $attachment_id );
		$image_attributes = wp_get_attachment_image_src( $attachment_id );
		$wc_imgupload = get_option( 'webcomic_img_upload' );
		$wc_charupload = get_option( 'webcomic_admin_page' );
		
		if ( $wc_imgupload == 1 ) { //this means that we've triggered the media upload from the webcomic admin page
			if ( $wc_charupload == 1 ) { //1 is the setting for character upload
				//do nothing for now, titles of character images don't matter but this may change
			}
			else if ( $wc_charupload == 2 ) {  //2 is the setting for background upload
				
				
				$img_meta = wp_prepare_attachment_for_js( $attachment_id );
				$img_title = $img_meta['title'];
				$this->update_bg_in_database( $img_title, $attachment_id, 0 );
				
			}
		}
		else { //if they accessed the media screen from the media library we have to search the BG database for the image and update it if its in there
			$bgid = $this->check_db_for_bg( $attachment_id );
			if ( $bgid && is_numeric( $bgid ) ) { //this attachment is in the database
				$img_meta = wp_prepare_attachment_for_js( $attachment_id );
				$img_title = $img_meta['title'];
				$this->update_bg_in_database( $img_title, $attachment_id, 0 );
			}
			
		}
		
		//$this->process_attachment($attachment_id);
		return( $attachment_id );
	}
	public function process_attachment( $attachment_id ) {
		if ( ! current_user_can( 'edit_others_posts' ) ) { //only allow access to "editor" or higher
			return( false );
		}
		
		$imagepath = get_attached_file( $attachment_id );
		
		$image_attributes = wp_get_attachment_image_src( $attachment_id );
		$wc_imgupload = get_option( 'webcomic_img_upload' );
		$wc_charupload = get_option( 'webcomic_admin_page' );

		
		if ( $wc_imgupload == 1 ) { //this means that we've triggered the media upload from the webcomic admin page
			
			if ( $wc_charupload == 1 ) { //1 is the setting for character upload
				$wc_char_maxwidth = get_option( 'webcomic_char_maxwidth' );
				$wc_char_maxheight = get_option( 'webcomic_char_maxheight' );
				if ( ! is_numeric( $wc_char_maxwidth ) ) {
					$wc_char_maxwidth = WEBCOMIC_CHAR_MAXWIDTH; //if it's not numeric, revert to the default
				}
				if ( ! is_numeric( $wc_char_maxheight ) ) { 
					$wc_char_maxheight = WEBCOMIC_CHAR_MAXHEIGHT; //if it's not numeric, revert to the default
				}
				$wc_image = wp_get_image_editor( $imagepath );
				$oldfilename = $imagepath;
				$wc_imagesize = $wc_image->get_size();
				
				if ( ( $wc_imagesize['width'] > $wc_char_maxwidth ) || ( $wc_imagesize['height'] > $wc_char_maxheight ) ) { //resize an image if it exceeds the allowable size
					$wc_image->resize( $wc_char_maxwidth,$wc_char_maxheight );
					$wc_savedimage = $wc_image->save( $imagepath );
				}
				//flip the image and save a copy
				$search = '.';
				$replace = '_r.';
				$newfilename = strrev( implode( strrev( $replace ), explode( $search, strrev( $oldfilename ), 2 ) ) );
				$wc_image->flip( false, true );
				$wc_savedimage2 = $wc_image->save( $newfilename );
				
				//Add the image to the charimgs database
				$img_meta = wp_prepare_attachment_for_js( $attachment_id );
				$img_title = $this->webcomic_options_title_validate( $img_meta['title'] );
				
				$wc_last_char_id = get_option( 'webcomic_last_char_id' ); //we get the character id from the wordpress options database
				//update_option( 'webcomic_last_char_id', '0'); //set the option back to 0
				if ( is_numeric( $wc_last_char_id ) ) {
					$this->add_char_img_to_database( $wc_last_char_id, $image_attributes[0], $attachment_id, 0 );
				}
				
				
			}
			else if ( $wc_charupload == 2 ) {  //2 is the setting for background upload
				$wc_bg_width = get_option( 'webcomic_bg_width' );
				$wc_bg_height = get_option( 'webcomic_bg_height' );
				if ( ! is_numeric( $wc_bg_width ) ) {
					$wc_bg_width = WEBCOMIC_BG_WIDTH; //if it's not numeric, revert to the default
				}
				if ( ! is_numeric( $wc_bg_height ) )  { 
					$wc_bg_height = WEBCOMIC_BG_HEIGHT; //if it's not numeric, revert to the default
				}
				$wc_image = wp_get_image_editor( $imagepath );
				$wc_imagesize = $wc_image->get_size();
				$image_changed = false;
				if ( ( $wc_imagesize['height'] < $wc_bg_height ) && ( $wc_imagesize['width'] < $wc_bg_width ) ) { //special case: resize both width and height
					$wc_image->crop( 0, 0, $wc_imagesize['width'], $wc_imagesize['height'], $wc_bg_width, $wc_bg_height );
					$image_changed = true;
				}
				else {
					if ( $wc_imagesize['height'] < $wc_bg_height ) { //blow up the image if it isn't tall enough
						$wc_image->crop( 0, 0, $wc_imagesize['width'], $wc_imagesize['height'], $wc_imagesize['width'], $wc_bg_height );
						
						$image_changed = true;
					}
					if ( $wc_imagesize['width'] < $wc_bg_width ) { //blow up the image if it isn't wide enough
						$wc_image->crop( 0, 0, $wc_imagesize['width'], $wc_imagesize['height'], $wc_bg_width, $wc_imagesize['height'] );
						$image_changed = true;
					}
					if ( ( $wc_imagesize['width'] > $wc_bg_width ) || ( $wc_imagesize['height'] > $wc_bg_height ) ) { //resize an image if it exceeds the allowable size
						$wc_image->resize( $wc_bg_width,$wc_bg_height, true );
						$image_changed = true;
						
					}
				}
				if ( true == $image_changed ) {
					$wc_savedimage = $wc_image->save( $imagepath );
					$results_print = print_r( $wc_savedimage, true );
				}

						
				$img_meta = wp_prepare_attachment_for_js( $attachment_id );
				$img_title = $this->webcomic_options_title_validate( $img_meta['title'] );
				$this->add_bg_to_database( $img_title, $image_attributes[0], $attachment_id, 0 );
				
								
			}
		}
		
		return( $attachment_id );
	}
	public function remove_attachment( $attachment_id ) {
		
		if ( ! current_user_can( 'edit_others_posts' ) ) { //only allow access to "editor" or higher
			return( false );
		}
		
		if ( !is_numeric( $attachment_id ) ) { //the id should be a number
			return( false );
		}
		//check if this attachment was a background or a character. If it was, remove that item from the database
		$bgid = $this->check_db_for_bg( $attachment_id );
		if ( $bgid ) {
			$this->delete_db_bg( $bgid );
			return( $attachment_id );
		}
		$charid = $this->check_db_for_char( $attachment_id );
		if ( $charid ) {
			$this->delete_db_char_img( $charid );
			return( $attachment_id );
		}
		return( $attachment_id );
		
	}
	function check_db_for_bg( $aid ) {
		global $wpdb;
		if ( !is_numeric( $aid ) ) {
			return( false );
		}
		$table_name = $wpdb->prefix . "comicbgs"; 
		$bgid = $wpdb->get_var( $wpdb->prepare( 
			"
				SELECT id 
				FROM $table_name 
				WHERE aid = %d
			", 
			$aid
		) );
		return( $bgid );
	}
	function check_db_for_char( $aid ) {
		global $wpdb;
		if ( !is_numeric( $aid ) ) {
			return( false );
		}
		$table_name = $wpdb->prefix . "comiccharimgs"; 
		$charid = $wpdb->get_var( $wpdb->prepare( 
			"
				SELECT id 
				FROM $table_name 
				WHERE aid = %d
			", 
			$aid
		) );
		return( $charid );
	}
	function delete_db_bg( $bgid ) {
		
		if ( ! current_user_can( 'edit_others_posts' ) ) { //only allow access to "editor" or higher
			return( false );
		}
		
		global $wpdb;
		if ( !is_numeric( $bgid ) ) {
			return( false );
		}
		$table_name = $wpdb->prefix . "comicbgs"; 
		$deletedvalue = $wpdb->delete( $table_name, array( 'id' => $bgid ), array( '%d' ) );
		return( $deletedvalue );
	}
	function delete_db_char_img( $charid ) {
		
		if ( ! current_user_can( 'edit_others_posts' ) ) { //only allow access to "editor" or higher
			return( false );
		}
		
		global $wpdb;
		if ( !is_numeric( $charid ) ) {
			return( false );
		}
		$table_name = $wpdb->prefix . "comiccharimgs"; 
		$deletedvalue = $wpdb->delete( $table_name, array( 'id' => $charid ), array( '%d' ) );
		return( $deletedvalue );
	}
	function delete_db_character( $charid ) {
		
		if ( ! current_user_can( 'edit_others_posts' ) ) { //only allow access to "editor" or higher
			return( false );
		}
		
		global $wpdb;
		if ( !is_numeric( $charid ) ) {
			return( false );
		}
		$table_name = $wpdb->prefix . "comiccharacters"; 
		$deletedvalue = $wpdb->delete( $table_name, array( 'id' => $charid ), array( '%d' ) );
		return( $deletedvalue );
	}
	function show_all_backgrounds() {
		global $wpdb;
		$table_name = $wpdb->prefix . "comicbgs"; 
		$backgrounds = $wpdb->get_results( 
			"
			SELECT id, aid, name, src, flag 
			FROM $table_name

			"
		);
		$upload_url = admin_url() . "upload.php?item=";
		foreach ( $backgrounds as $background ) 
		{
			
			echo '<div class="webcomic-background-box"><div class="webcomic-background-title">'. esc_html( $background->name ) .'</div><div class="webcomic-background-delete"><input type="hidden" class="webcomic-background-delete-id" value="'. esc_attr( $background->aid ) .'"/>[X]</div><div class="webcomic-clear"></div><div class="webcomic-background-img"><img src="'. esc_url( $background->src ) .'"/></div></div>';
		}
	}


	
	public function webcomic_is_plugin_page() {
	   $server_uri = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
	   foreach ( array( 'webcomic-creator-studio' ) as $allowURI ) {
		  if ( stristr( $server_uri, $allowURI ) ) {
			  return( true );
		  }
	   }
	   return( false );
	}
	
	public function webcomic_check_for_bg_delete() {
		if ( isset( $_GET['bgimage'] ) ) {
			if ( is_numeric( $_GET['bgimage'] ) ) { //safety check. we only accept numbers.
				return ( trim( $_GET['bgimage'] ) );
			}
		}
		return( false );
	}
	
	public function webcomic_delete_bg_image() {
		
		if ( ! current_user_can( 'edit_others_posts' ) ) { //only allow access to "editor" or higher
			return( false );
		}
		
		$security = trim( $_POST["security"] );
		if ( ! wp_verify_nonce( $security, 'webcomic-admin-nonce' ) ) {
			exit( "Security Check Failure: Invalid Nonce" );
		}
		
		//Get the post data 
		$background_id = trim( $_POST["background_id"] );
		if ( is_numeric( $background_id ) ) {
		
			if ( wp_delete_attachment( $background_id ) === false ) {
				die();
			}
			else {
				//Create the array we send back to javascript here
				$ajax_reply = array( 'reply' => "Delete Successful on Attachment ID " . $background_id );
			}
			//json encode the output
			echo json_encode( $ajax_reply );
		}

		exit(); 
	}
	public function webcomic_delete_char_image() {
		
		if ( ! current_user_can( 'edit_others_posts' ) ) { //only allow access to "editor" or higher
			return( false );
		}
		
		$security = trim( $_POST["security"] );
		if ( ! wp_verify_nonce( $security, 'webcomic-admin-nonce' ) ) {
			exit( "Security Check Failure: Invalid Nonce" );
		}
		
		//Get the post data 
		$img_id = trim( $_POST["img_id"] );
		if ( is_numeric( $img_id ) ) {
		
			if ( $img_id < 0 ) { //built in characters
				//Note: I have decided to disable the deletion of built-in characters but I will leave the code intact
				$imagepath = plugin_dir_url( __FILE__ ) . 'img/';
				if ( $img_id == -1 ) {	//doctor jones
					//unlink( $imagepath . 'doctor-jones.png' );
					//unlink( $imagepath . 'doctor-jones_r.png' );
					
				}
				elseif ( $img_id == -2 ) {	//ms. johnson
					//unlink( $imagepath . 'ms-johnson.png' );
					//unlink( $imagepath . 'ms-johnson_r.png' );
				
				}
				elseif ( $img_id == -3 ) {	//slick carl
					//unlink( $imagepath . 'slick-carl.png' );
					//unlink( $imagepath . 'slick-carl_r.png' );
					
				}
				
				$ajax_reply = array( 'reply' => "Deleted Built-in Character ID " . $img_id );
			}
			else {	//user added characters
				$imagepath = get_attached_file( $img_id ); //get the path of this image so the "right" copy can also be deleted
				$wc_image = wp_get_image_editor( $imagepath );
				$oldfilename = $imagepath;
						
				if ( wp_delete_attachment( $img_id ) === false ) {
					die();
				}
				else {
					
					//delete the "right" copy of this image
					$search = '.';
					$replace = '_r.';
					$newfilename = strrev( implode( strrev( $replace ), explode( $search, strrev( $oldfilename ), 2 ) ) );
					if ( file_exists( $newfilename ) ) {
						unlink( $newfilename );
					} 
					
					//Create the array we send back to javascript here
					$ajax_reply = array( 'reply' => "Delete Successful on Attachment ID " . $img_id );
				}
			}
			//json encode the output
			echo json_encode( $ajax_reply );
			
		}

		exit(); 
	}
	
	public function webcomic_get_bg_title() { //get the title of an attachment from the attachment id
	
		$security = trim( $_POST["security"] );
		if ( ! wp_verify_nonce( $security, 'webcomic-admin-nonce' ) ) {
			exit( "Security Check Failure: Invalid Nonce" );
		}
		
		$current_image = trim( $_POST["image_id"] );
		if ( is_numeric( $current_image ) ) {
			//query wordpress to get the image title
			$image_title = $this->webcomic_options_title_validate( get_the_title( $current_image ) );
			//Create the array we send back to javascript here
			$ajax_reply = array( 'reply' => "Successfully received the title of " . $current_image, 'title' => $image_title );

			//json encode the output
			echo json_encode( $ajax_reply );
		}

		exit(); 
	}
	function show_character_list() {
		
		echo '<div id="webcomic-character-select-box">';
		global $wpdb;
		$table_name = $wpdb->prefix . "comiccharacters"; 
		$characters = $wpdb->get_results( 
			"
			SELECT id, name, description, flag 
			FROM $table_name

			"
		);
		if ( !$characters ) {
			echo '<div id="webcomic-no-chars"><strong>No characters have been added yet.</strong></div>';
		}
		else {
			echo '<div id="webcomic-character-list">';
			echo '<select id="webcomic-character-select">';
			echo '<option value="0" selected>Choose a Character to Edit</option>';
			foreach ( $characters as $character ) //first we just create a select box
			{
				echo '<option value="'. esc_attr( $character->id ) .'">' . esc_html( $character->name ) . '</option>';
				
			}
			echo '</select>';
			echo '</div>';
		
			$table_name = $wpdb->prefix . "comiccharimgs"; 
			foreach ( $characters as $character ) //then we add a div for each character that can be browsed using the select box
			{
				
				echo '<div class="webcomic-character-choice" char="' . esc_attr( $character->id ) . '">';		
				echo '<div class="webcomic-character-header"><div class="webcomic-character-header-name">' . esc_html( $character->name ) . '</div><div class="webcomic-character-header-delete"><input type="hidden" class="webcomic-char-delete-id" value="'. esc_attr( $character->id ) .'"/>[X]</div><div class="webcomic-clear"></div></div>';
				echo '<div class="webcomic-character-imgs-box">';
				//retrieve each image for this character
				if ( is_numeric( $character->id ) ) {
					$thischarimgs = $wpdb->get_results( 
						"
						SELECT id, aid, src, flag 
						FROM $table_name
						WHERE charid = $character->id

						"
					);
					if ( $thischarimgs ) {
						foreach ( $thischarimgs as $thischarimg ) 
						{
							
							echo '<div class="webcomic-char-img-box">';
							if ( $thischarimg->aid > 0 ) { //only allow deletion of character images for characters that are not built in
								echo '<div class="webcomic-char-img-delete"><input type="hidden" class="webcomic-char-img-delete-id" value="'. esc_attr( $thischarimg->aid ) .'"/>[X]</div>';
							}
							
							echo '<div class="webcomic-clear"></div><div class="webcomic-char-img"><img src="'. esc_url( $thischarimg->src ) .'"/></div></div>';
						}
					}
					else {
						echo '<div class="webcomic-char-img-box webcomic-char-noimg">No images added for this character</div>';
					}
				}
				echo '</div>'; //end of webcomic_character_imgs_box
				echo '<div class="webcomic-clear"></div>'; //clear floats
				
				//show button to add image for this character
				echo '<a href="javascript:;" class="webcomic-upload-media webcomic-admin-button webcomic-upload-character" char="' . esc_attr( $character->id ) .'">Upload Image</a>';
				echo '</div>';
			}
		}
		
		
	
	}
	function show_character_add() {
		echo '<div id="webcomic-add-character">';
		echo '<div id="webcomic-add-char-form" style="display:none">';
		echo '<div><strong>Character Name:</strong> <input type="text" name="webcomic-new-char-name" id="webcomic-new-char-name"/> <a href="javascript:;" id="webcomic-create-new-char-save" class="webcomic-admin-button">Add Character</a> <a href="javascript:;" id="webcomic-create-new-char-cancel" class="webcomic-admin-button">Cancel</a></div></div>';
		echo '</div>';
		echo '<a href="javascript:;" id="webcomic-create-new-character" class="webcomic-admin-button">Create a New Character</a>';
		echo '</div>';
	}
	
	public function webcomic_add_new_character() {
		
		if ( ! current_user_can( 'edit_others_posts' ) ) { //only allow access to "editor" or higher
			return( false );
		}
		
		$security = trim( $_POST["security"] );
		if ( ! wp_verify_nonce( $security, 'webcomic-admin-nonce' ) ) {
			exit( "Security Check Failure: Invalid Nonce" );
		}
		
		$character_name = $this->webcomic_name_validate( strip_tags( trim( $_POST["char_name"] ) ) );
		$flag = 0;

		
		global $wpdb;
		$table_name = $wpdb->prefix . "comiccharacters"; 
		$insert = "INSERT INTO " . $table_name .
            " (name, flag) " .
            "VALUES ('" . $wpdb->escape( $character_name ) . "','" . $wpdb->escape( $flag ) . "')";

		$results = $wpdb->query( $insert );
		if ( $results ) {
			$lastid = $wpdb->insert_id;
			$ajax_reply = array( 'reply' => "Successfully added character " . $character_name . " with id " . $lastid, 'charid' => $lastid );
			
			//json encode the output
			echo json_encode( $ajax_reply );
		}

		exit(); 
	}
	public function webcomic_add_char_option() {
		
		if ( ! current_user_can( 'edit_others_posts' ) ) { //only allow access to "editor" or higher
			return( false );
		}
		
		$security = trim( $_POST["security"] );
		if ( ! wp_verify_nonce( $security, 'webcomic-admin-nonce' ) ) {
			exit( "Security Check Failure: Invalid Nonce" );
		}
		
		$character_id = strip_tags( trim( $_POST["char_id"] ) );
		$attachment_id = strip_tags( trim( $_POST["attachment_id"] ) );
		
		$img_src = $this->webcomic_options_url_validate( strip_tags( trim( $_POST["img_src"] ) ) );
		$flag = 0;

		if ( is_numeric( $character_id ) && is_numeric( $attachment_id ) && ( $img_src ) ) { //the id must be a number and the src must exist
			global $wpdb;
			$table_name = $wpdb->prefix . "comiccharimgs"; 
			$insert = "INSERT INTO " . $table_name .
				" (aid, charid, src, flag) " .
				"VALUES ('" . $wpdb->escape( $attachment_id ) . "','" . $wpdb->escape( $character_id ) . "','" . $wpdb->escape( $img_src ) . "','" . $wpdb->escape( $flag ) . "')";

			$results = $wpdb->query( $insert );
			if ( $results ) {
				$ajax_reply = array( 'reply' => "Successfully added character Img " . $character_id . " " . $img_src, 'attachment' => $attachment_id, 'src' => $img_src );
				
				//json encode the output
				echo json_encode( $ajax_reply );
			}
		}
		exit(); 
	}
	
	public function webcomic_delete_character() {
		
		if ( ! current_user_can( 'edit_others_posts' ) ) { //only allow access to "editor" or higher
			return( false );
		}
		
		$security = trim( $_POST["security"] );
		if ( ! wp_verify_nonce( $security, 'webcomic-admin-nonce' ) ) {
			exit( "Security Check Failure: Invalid Nonce" );
		}
		
		$character_id = strip_tags( trim( $_POST["char_id"] ) );
		global $wpdb;
		//we delete the character and we delete all character images for this character
		if ( is_numeric( $character_id ) ) {
			$table_name = $wpdb->prefix . "comiccharimgs"; 
			$thischarimgs = $wpdb->get_results( 
				"
				SELECT id, aid, src, flag 
				FROM $table_name
				WHERE charid = $character_id

				"
			);
			if ( $thischarimgs ) {
				foreach ( $thischarimgs as $thischarimg ) 
				{
					if ( ( is_numeric( $thischarimg->aid ) ) && ( is_numeric( $thischarimg->id ) ) ) {
					
						if ( ( $thischarimg->aid ) < 0 && ( $thischarimg->id < 0 ) ) {
							//these are built in characters. I have decided not to allow the files to be deleted
							$this->delete_db_char_img( $thischarimg->id ); //delete the image from the database
						}
						else {
							$imagepath = get_attached_file( $thischarimg->aid ); //get the path of the image so we can delete its "right" copy
							$wc_image = wp_get_image_editor( $imagepath );
							$oldfilename = $imagepath;
						
							if ( wp_delete_attachment( $thischarimg->aid ) === false ) {
								//error deleting attachment
								
							}
							//now delete the "right" version of this character 
							$search = '.';
							$replace = '_r.';
							$newfilename = strrev( implode( strrev( $replace ), explode( $search, strrev( $oldfilename ), 2 ) ) );
							if ( file_exists( $newfilename ) ) {
								unlink( $newfilename );
							} 
						}
					}
				}
			}
			$deleted_value = $this->delete_db_character( $character_id );
			if ( $deleted_value ) {
				$ajax_reply = array( 'reply' => "Successfully Deleted Character " . $character_id );
				
				//json encode the output
				echo json_encode( $ajax_reply );
			}
		}
		
	
		exit();
	}
	public function create_webcomic_post_type() {
		register_post_type( 'wc-webcomic',
			array(
			  'labels' => array(
				'name' => __( 'Webcomics' ),
				'singular_name' => __( 'Webcomic' ),
			  ),
			  'public' => true,
			  'has_archive' => true,
			  'capabilities' => array(
				'create_posts' => false,
				'edit_posts' => false,
				'edit_others_posts' => false,
				'read_post' => false,
			  ),
			  'supports' => array( 'title', 'author', 'custom-fields' ),
			)
		);
		register_taxonomy(
			'queue_status',
			'wc-webcomic',
			array(
				'label' => __( 'Queue Status' ),
				'public' => false,
				'rewrite' => false,
				'hierarchical' => true,
			)
		);
		register_taxonomy_for_object_type( 'queue_status', 'wc-webcomic' );
		wp_insert_term('queued', 'queue_status');
		wp_insert_term('unqueued', 'queue_status');
	}
	public function initialize_queue() {
		
		if ( ! current_user_can( 'edit_others_posts' ) ) { //only allow access to "editor" or higher
			return( false );
		}
		
		echo '<h1>Webcomic Queue</h1>';
		?>
		<div id="webcomic-queue-intro">
		<input type="hidden" name="webcomic-queue-page-nonce" id="webcomic-queue-page-nonce" value="<?php echo wp_create_nonce( 'webcomic-queue-nonce' ); ?>"/>
		<div id="webcomic-queue-status"></div>
		<p>Use this button if you don't want to wait for a scheduled update to post your newest comic to your site:</p>
		<input type="button" name="webcomic-force-from-queue" id="webcomic-force-update" value=" Post The Top Comic From The Queue "/>
		<p>It will take the top comic from the "Queued" list and instantly post it.</p>
		<hr/>
		</div>
		<div id="webcomic-queue-holder">
			<div class="webcomic-queue-block">
				<table id="webcomic-unqueued-comics" class="webcomic-queue"><caption>Unqueued</caption>
				<tr><th>#</th><th>Date</th><th>Title</th><th>Comic</th><th> </th></tr>
						<?php

						$args=array(
							'post_type'			=> 'wc-webcomic',
							//'post_status'		=> 'publish',
							'order_by'			=> 'date',
							'queue_status' 		=> 'unqueued',

						);
						$unqueued_posts = get_posts( $args );
						if ( $unqueued_posts ) {
							$i = 0;
							foreach ( $unqueued_posts as $unqueued_post ) {
								$i++;
								if ( is_numeric( $unqueued_post->ID ) ) {
									$comic_image = $this->webcomic_options_url_validate( trim( get_post_meta( $unqueued_post->ID, 'comic_img', true ) ) );
									if ( $comic_image ) {
										echo '<tr id="webcomic-queue-' . esc_attr( $unqueued_post->ID ) . '"><td class="webcomic-pos">' . $i . '</td><td>' . esc_html( date( 'm/d/Y', strtotime( esc_html( $unqueued_post->post_date ) ) ) ) . '</td><td><div class="comic-title">' . esc_html( $unqueued_post->post_title ) . '</div></td><td><a href="' . esc_url( $comic_image ) . '" target="_blank"><img src="' . esc_url( $comic_image ) . '" /></a></td><td><div class="webcomic-button-options"><input type="button" class="webcomic-delete-comic" value="[ X ] Delete" /><input type="button" class="webcomic-queue-comic" value="[ &gt;&gt; ] Add to Queue"/><input type="hidden" name="comic-id" value="' .  esc_html( $unqueued_post->ID ) . '"/></div></td></tr>';
										
									}
								}
								
							}
						}
						else {
									echo '<tr><td colspan="5">No Unqueued Comics</td></tr>';
						}
						?>
				</table>
			</div>
			<div class="webcomic-queue-block">
				<table id="webcomic-queued-comics" class="webcomic-queue"><caption>Queued</caption>
				<tr><th>Pos</th><th>Date</th><th>Title</th><th>Comic</th><th> </th></tr>
				<?php

						$args=array(
							'post_type'			=> 'wc-webcomic',
							//'post_status'		=> 'publish',
							'meta_key'			=> 'queue_position',
							'orderby'			=> 'meta_value_num',
							'order'				=> 'ASC',
							'queue_status' 		=> 'queued',
							'cache_results' => false,

						);
						$queued_posts = get_posts( $args );
						if ( $queued_posts ) {
							$i = 0;
							$number_of_comics = count($queued_posts);
							foreach ( $queued_posts as $queued_post ) {
								if ( is_numeric( $queued_post->ID ) ) {
									$comic_image = $this->webcomic_options_url_validate( trim( get_post_meta( $queued_post->ID, 'comic_img', true ) ) );
									if ( $comic_image ) {
										$i++;
										//
										echo '<tr id="webcomic-queue-' . esc_attr( $queued_post->ID ) . '"><td class="webcomic-pos">' . $i . '</td><td>' . date( 'm/d/Y', strtotime( esc_html( $queued_post->post_date ) ) ) . '</td><td><div class="comic-title">' . esc_html( $queued_post->post_title ) . '</div></td><td><a href="' . esc_url( $comic_image ) . '" target="_blank"><img src="' . esc_url( $comic_image ) . '" /></a></td><td><div class="webcomic-button-options">';
										if ( $i != 1 ) {
											echo '<input type="button" class="webcomic-early-queue-comic" value="[ /\ ] Move Up" />';
										}
										if ( $i != $number_of_comics ) {
											
											echo '<input type="button" class="webcomic-late-queue-comic" value="[ \/ ] Move Down" />';
										}
										echo '<input type="button" class="webcomic-unqueue-comic" value="[ &lt;&lt; ] Unqueue" /><input type="hidden" name="comic-id" value="' .  esc_html( $queued_post->ID ) . '"/></div></td></tr>';

									}
								}
					
							}
						}
						else {
								echo '<tr class="webcomic-no-queued"><td colspan="6">No Queued Comics</td></tr>';
						}
						
				?>
				</table>
			</div>
		</div>

	<?php
	}	
	public function initialize_creation() {
		
		if ( ! current_user_can( 'edit_others_posts' ) ) { //only allow access to "editor" or higher
			return( false );
		}
		
		echo '<script>'; //get the info about the width and height and give it to javascript.
		echo "\n";
		$comic_width = get_option( 'webcomic_width' );
		$comic_height = get_option( 'webcomic_height' );
		if ( ! is_numeric( $comic_width ) ) { //if we have a non-numeric value we reset to the default
			$comic_width = WEBCOMIC_WIDTH;
		}
		if ( ! is_numeric( $comic_height ) ) { //if we have a non-numeric value we reset to the default
			$comic_height = WEBCOMIC_HEIGHT;
		}
		
		$comic_title = $this->webcomic_options_title_validate( trim( get_option( 'webcomic_title' ) ) );
		$comic_url = $this->webcomic_options_url_validate( trim( get_option( 'webcomic_url' ) ) );
		echo 'var comicWidth = ' . $comic_width . ';';
		echo "\n";
		echo 'var comicHeight = ' . $comic_height . ';';
		echo "\n";
		echo '</script>';
		echo '<h1>Create a New Webcomic</h1>';
		echo '<input type="hidden" id="webcomic-url" value="' . esc_attr( $comic_url ) . '"/>';
		echo '<input type="hidden" id="webcomic-title" value="' . esc_attr( $comic_title ) . '"/>';
		?>
				<input type="hidden" name="webcomic-create-page-nonce" id="webcomic-create-page-nonce" value="<?php echo wp_create_nonce( 'webcomic-create-nonce' ); ?>"/>
				<div id="webcomic-new-comic-url"></div>
				 <div id="webcomic-comic-space">
					<div id="webcomic-comic-context">
						<div class="webcomic-comic-panel-context" style="height: <?php echo $comic_height; ?>px; width: <?php echo ($comic_width / 3) + 2; ?>px"></div>
						<div class="webcomic-comic-panel-context" style="height: <?php echo $comic_height; ?>px; width: <?php echo ($comic_width / 3) - 10; ?>px"></div>
						<div class="webcomic-comic-panel-context" style="height: <?php echo $comic_height; ?>px; width: <?php echo ($comic_width / 3) + 2; ?>px"></div>
					</div>

					<canvas id="webcomic-comic"></canvas>
					
					<div id="webcomic-comic-preview-space">
						<canvas id="webcomic-comic-preview"></canvas>
					</div><div class="webcomic-clear"></div>
				 
				 </div>
				 
				 
				 <div id="webcomic-comic-form">
					
					<form id="webcomic-comic-params">
					<div id="webcomic-panel-setup">
						<div>
						<!--<input id="webcomic-comic-create" type="button" onClick="createComic();" value="click here to create a comic!"/>-->
						<div id="webcomic-save-comic"></div>
						</div>
						<div id="webcomic-comic-title-div"><strong>Comic Title:</strong> <input type="text" name="comic-title" id="webcomic-comic-title"/></div>
						<div id="webcomic-comic-font-div"><strong>Comic Font:</strong> <select id="webcomic-comic-font" onchange="updateAllFonts()"><option value="Arial" selected>Arial</option>
							<option value="Comic Sans MS">Comic Sans</option><option value="Impact">Impact</option><option value="Lucida Console">Lucida</option><option value="Times New Roman">Times N.R.</option><option value="Verdana">Verdana</option></select></div>
						<div id="webcomic-panel-1" class="webcomic-a-panel">
						
							<h3>Panel 1</h3>
							<div id="webcomic-landscape-select-p1"></div>
							<table><tr><td>Left:</td><td><div><textarea id="webcomic-p1-left" class="webcomic-char-text"></textarea></div><div><input type="radio" name="webcomic-p1-left-word-type" value="word" checked/>Spoken <input type="radio" name="webcomic-p1-left-word-type" value="thought"/>Thought</div></td><td><div>Font Size:</div><div>
							<select id="webcomic-p1-left-font-size"><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12" selected>12</option>
							<option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option></select>
							</div><hr/>
							<div>Font:</div><div><select id="webcomic-p1-left-font"><option value="Arial" selected>Arial</option>
							<option value="Comic Sans MS">Comic Sans</option><option value="Impact">Impact</option><option value="Lucida Console">Lucida</option><option value="Times New Roman">Times N.R.</option><option value="Verdana">Verdana</option></select></div>
							</td></tr>
							<tr><td colspan="3"><hr/></td></tr>
							<tr><td>Right:</td><td><div><textarea id="webcomic-p1-right" class="webcomic-char-text"></textarea></div><div><input type="radio" name="webcomic-p1-right-word-type" value="word" checked/>Spoken <input type="radio" name="webcomic-p1-right-word-type" value="thought"/>Thought</div></td><td><div>Font Size:</div><div>
							<select id="webcomic-p1-right-font-size"><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12" selected>12</option>
							<option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option></select>
							</div><hr/>
							<div>Font:</div><div><select id="webcomic-p1-right-font"><option value="Arial" selected>Arial</option>
							<option value="Comic Sans MS">Comic Sans</option><option value="Impact">Impact</option><option value="Lucida Console">Lucida</option><option value="Times New Roman">Times N.R.</option><option value="Verdana">Verdana</option></select></div></td></tr>
							<tr><td colspan="3"><hr/></td></tr>
							<tr><td>Options:</td><td><input type="checkbox" id="webcomic-p1-ext" checked/>Extend Single Word-bubble</td></tr>
							<tr><td>Caption:</td><td><input type="text" id="webcomic-p1-cap"/></td></tr>
							</table>
							<hr/>
							<div id="webcomic-char-select-p1"></div>
							
							
						</div>
						<div id="webcomic-panel-2" class="webcomic-a-panel">
							<h3>Panel 2</h3>
							<div id="webcomic-landscape-select-p2"></div>
							<table><tr><td>Left:</td><td><div><textarea id="webcomic-p2-left" class="webcomic-char-text"></textarea></div><div><input type="radio" name="webcomic-p2-left-word-type" value="word" checked/>Spoken <input type="radio" name="webcomic-p2-left-word-type" value="thought"/>Thought</div></td><td><div>Font Size:</div><div>
							<select id="webcomic-p2-left-font-size"><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12" selected>12</option>
							<option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option></select>
							</div><hr/>
							<div>Font:</div><div><select id="webcomic-p2-left-font"><option value="Arial" selected>Arial</option>
							<option value="Comic Sans MS">Comic Sans</option><option value="Impact">Impact</option><option value="Lucida Console">Lucida</option><option value="Times New Roman">Times N.R.</option><option value="Verdana">Verdana</option></select></div>
							</td></tr>
							<tr><td colspan="3"><hr/></td></tr>
							<tr><td>Right:</td><td><div><textarea id="webcomic-p2-right" class="webcomic-char-text"></textarea></div><div><input type="radio" name="webcomic-p2-right-word-type" value="word" checked/>Spoken <input type="radio" name="webcomic-p2-right-word-type" value="thought"/>Thought</div></td><td><div>Font Size:</div><div>
							<select id="webcomic-p2-right-font-size"><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12" selected>12</option>
							<option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option></select>
							</div><hr/>
							<div>Font:</div><div><select id="webcomic-p2-right-font"><option value="Arial" selected>Arial</option>
							<option value="Comic Sans MS">Comic Sans</option><option value="Impact">Impact</option><option value="Lucida Console">Lucida</option><option value="Times New Roman">Times N.R.</option><option value="Verdana">Verdana</option></select></div></td></tr>
							<tr><td colspan="3"><hr/></td></tr>
							<tr><td>Options:</td><td><input type="checkbox" id="webcomic-p2-ext" checked/>Extend Single Word-bubble</td></tr>
							<tr><td>Caption:</td><td><input type="text" id="webcomic-p2-cap"/></td></tr>
							</table>
							<hr/>
							<div id="webcomic-char-select-p2"></div>
							
						</div>
						<div id="webcomic-panel-3" class="webcomic-a-panel">
							<h3>Panel 3</h3>
							<div id="webcomic-landscape-select-p3"></div>
							<table><tr><td>Left:</td><td><div><textarea id="webcomic-p3-left" class="webcomic-char-text"></textarea></div><div><input type="radio" name="webcomic-p3-left-word-type" value="word" checked/>Spoken <input type="radio" name="webcomic-p3-left-word-type" value="thought"/>Thought</div></td><td><div>Font Size:</div><div>
							<select id="webcomic-p3-left-font-size"><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12" selected>12</option>
							<option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option></select>
							</div><hr/>
							<div>Font:</div><div><select id="webcomic-p3-left-font"><option value="Arial" selected>Arial</option>
							<option value="Comic Sans MS">Comic Sans</option><option value="Impact">Impact</option><option value="Lucida Console">Lucida</option><option value="Times New Roman">Times N.R.</option><option value="Verdana">Verdana</option></select></div>
							</td></tr>
							<tr><td colspan="3"><hr/></td></tr>
							<tr><td>Right:</td><td><div><textarea id="webcomic-p3-right" class="webcomic-char-text"></textarea></div><div><input type="radio" name="webcomic-p3-right-word-type" value="word" checked/>Spoken <input type="radio" name="webcomic-p3-right-word-type" value="thought"/>Thought</div></td><td><div>Font Size:</div><div>
							<select id="webcomic-p3-right-font-size"><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12" selected>12</option>
							<option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option></select>
							</div><hr/>
							<div>Font:</div><div><select id="webcomic-p3-right-font"><option value="Arial" selected>Arial</option>
							<option value="Comic Sans MS">Comic Sans</option><option value="Impact">Impact</option><option value="Lucida Console">Lucida</option><option value="Times New Roman">Times N.R.</option><option value="Verdana">Verdana</option></select></div></td></tr>
							<tr><td colspan="3"><hr/></td></tr>
							<tr><td>Options:</td><td><input type="checkbox" id="webcomic-p3-ext" checked/>Extend Single Word-bubble</td></tr>
							<tr><td>Caption:</td><td><input type="text" id="webcomic-p3-cap"/></td></tr>
							</table>
							<hr/>
							<div id="webcomic-char-select-p3"></div>
							
						</div>
						
					</div>
					
					</form>
				</div>
				 
				<div class="webcomic-cache-img">
					<img src="<?php echo plugin_dir_url( __FILE__ ); ?>img/words.svg" id="webcomic-speech"/>
					<img src="<?php echo plugin_dir_url( __FILE__ ); ?>img/wordsoff.svg" id="webcomic-speech-off"/>
					<img src="<?php echo plugin_dir_url( __FILE__ ); ?>img/thought.svg" id="webcomic-thought"/>
					<img src="<?php echo plugin_dir_url( __FILE__ ); ?>img/thoughtoff.svg" id="webcomic-thought-off"/>
					
					<img src="<?php echo plugin_dir_url( __FILE__ ); ?>img/none.png" id="webcomic-empty-character"/>
					
					<?php 
					//Cycle through the characters and print them out with all of their character images.
					
					global $wpdb;
					$table_name = $wpdb->prefix . "comiccharacters"; 
					$characters = $wpdb->get_results( 
						"
						SELECT id, name, description, flag 
						FROM $table_name
						ORDER BY name

						"
					);
					if ( !$characters ) {
						echo '<div id="webcomic-no-chars"><strong>You must add characters to make a comic.</strong></div>';
					}
					else {
						
						$search = '.'; //used for changing filename of "right" character
						$replace = '_r.'; //used for changing filename of "right" character
						$table_name = $wpdb->prefix . "comiccharimgs"; 
						
						foreach ( $characters as $character ) //first we just create a select box
						{
							if ( is_numeric( $character->id ) ) {
								$charname = $this->webcomic_name_validate ( $character->name );
								$charname = str_replace( ' ','_', $charname );
								$charname = preg_replace( "([^\w\s\d\-_!.])", '', $charname ); //stricter validation for this instance
								$charname = 'webcomic-char-' . $charname;
								echo '<div class="webcomic-character-choice" id="' . esc_attr( $charname ) . '">';
								//retrieve each image for this character
								$thischarimgs = $wpdb->get_results( 
									"
									SELECT id, aid, src, flag 
									FROM $table_name
									WHERE charid = $character->id

									"
								);
								if ( $thischarimgs ) {
									foreach ( $thischarimgs as $thischarimg ) 
									{
										if ( is_numeric( $thischarimg->id ) ) {
											echo '<img src="' . esc_url( $this->webcomic_options_url_validate( $thischarimg->src ) ) . '" id="' . esc_attr( $thischarimg->id ) . '" class="left"/>';
											
											$newfilename = strrev( implode( strrev( $replace ), explode( $search, strrev( $this->webcomic_options_url_validate( $thischarimg->src ) ), 2 ) ) );
											echo '<img src="' . esc_url( $newfilename ) . '" id="' . esc_attr( $thischarimg->id ) . '_r' . '" class="right"/>';
										}

									}
								}
							}
						}
					}
			
					//list all backgrounds
					
					$table_name = $wpdb->prefix . "comicbgs"; 
					$backgrounds = $wpdb->get_results( 
						"
						SELECT id, aid, name, src, flag 
						FROM $table_name
						ORDER BY name

						"
					);
					foreach ( $backgrounds as $background ) 
					{
						
						$bgname = $this->webcomic_name_validate( $background->name );
						$bgname = str_replace( ' ','_',$bgname );
						$bgname = preg_replace( "([^\w\s\d\-_!.])", '', $bgname ); //extra validation for this instance
						$bgname = 'webcomic-bg-' . $bgname;
						//$random_number = Math.floor((Math.random() * 1000) + 1); 
						echo '<img src="' . esc_url( $this->webcomic_options_url_validate( $background->src ) ) . '" class="webcomic-background-choice" id="' . esc_attr( $bgname ) . '"/>';					
					}
			
					?>
					
					
					
					
					
						
					<img src="<?php echo plugin_dir_url( __FILE__ ); ?>img/nonebg.png" id="white"/>
					
				</div>
		
		<?php
	}
	
	public function webcomic_add_comic_to_inactive_queue() {
		
		global $wpdb;
		
		$security = trim( $_POST["security"] );
		if ( ! wp_verify_nonce( $security, 'webcomic-create-nonce' ) ) {
			$this->webcomic_print_error( 'Error in function webcomic_add_comic_to_inactive_queue from failed nonce verification' );
			exit( "Security Check Failure: Invalid Nonce" );
		}
		
		$comic_image = trim( $_POST["comic"] );
		$comic_preview = trim( $_POST["preview"] );
		$comic_image = str_replace( 'data:image/png;base64,', '', $comic_image );
		$comic_image = str_replace( ' ', '+', $comic_image );
		if ( ! $this->check_base64_image( $comic_image ) ) {
			$this->webcomic_print_error( 'Error in function webcomic_add_comic_to_inactive_queue from check_base64_image. The POST data for the comic image was corrupt.' );
			exit( "Corrupt image! Save failed. Please try again." );
		}
		$comic_image = base64_decode( $comic_image );
		$comic_preview = str_replace( 'data:image/png;base64,', '', $comic_preview );
		$comic_preview = str_replace( ' ', '+', $comic_preview );
		if ( ! $this->check_base64_image( $comic_preview ) ) {
			$this->webcomic_print_error( 'Error in function webcomic_add_comic_to_inactive_queue from check_base64_image. The POST data for the preview image was corrupt.' );
			exit( "Corrupt image! Save failed. Please try again." );
		}
		$comic_preview = base64_decode( $comic_preview );
		
		$timezone =  get_option('timezone_string');
		date_default_timezone_set($timezone);
		$todays_date = date('Y-m-d');

		$post_content = '';
		$title = wp_strip_all_tags( trim( urldecode( $_POST["title"] ) ) );
		$title = $this->webcomic_options_title_validate( $title ); //validate the safety of the title
		$post_name = $title;
		$post_title = $title;
		$post_excerpt = '';
		$save_name = strtolower( $post_title );
		$save_name = str_replace( ' ', '-' , $save_name );
		$save_name = preg_replace( "([^\w\s\d\-_])", '', $save_name );
		$save_name = substr( $save_name, 0, 8 );
		$save_name = $save_name . $todays_date;
		
		$upload_array = wp_upload_dir();
		$upload_dir = $upload_array['path'];
		$upload_url = $upload_array['url'];
		$upload_dir .= '/';
		$upload_url .= '/';
		$image_filename = $upload_dir . $save_name . '.png';
		$success = file_put_contents( $image_filename, $comic_image );
		if ( $success === false ) {
			$upload_dir = preg_replace( "([^\w\s\d\-_\.])", '', $upload_dir );
			$this->webcomic_print_error( 'Error in function webcomic_add_comic_to_inactive_queue from comic image in file_put_contents( ' . $image_filename . ', [comic image] (length: ' . strlen( $comic_image ) . ')');
			$this->webcomic_print_error( 'The upload directory in wordpress is set to ' . $upload_dir);
			exit( "Failure in creating image file on server!" );
		}
		$comic_url = $upload_url . $save_name . '.png';
		//now save comic preview
		$save_name = $save_name . "prev";
		$preview_filename = $upload_dir . $save_name . '.png';
		$success = file_put_contents( $preview_filename, $comic_preview );
		if ( $success === false ) {
			$upload_dir = preg_replace( "([^\w\s\d\-_\.])", '', $upload_dir );
			$this->webcomic_print_error( 'Error in function webcomic_add_comic_to_inactive_queue from preview image in file_put_contents( ' . $preview_filename . ', [comic image] (length: ' . strlen( $comic_preview ) . ')' );
			$this->webcomic_print_error( 'The upload directory in wordpress is set to ' . $upload_dir );
			exit( "Failure in creating image file on server!" );
		}
		$comic_prev_url = $upload_url . $save_name . '.png';				
		$excerpt = '<img src="' . $comic_url . '"/>';
		
		
		$post = array(
			'post_content'		=> $post_content, // The full text of the post.
			'post_name'			=> $post_name, // The name (slug) for your post
			'post_title'		=> $post_title, // The title of your post.
			'post_status'		=> 'publish', // Default 'draft'.
			'post_type'			=> 'wc-webcomic', // Default 'post'.
			'ping_status'		=> 'open', // Pingbacks or trackbacks allowed. Default is the option 'default_ping_status'.
			'post_excerpt'		=> $excerpt, // For all your post excerpt needs.
			'tax_input' 		=> array ( 'queue_status' => 'unqueued' ),
//		  'post_date'      => [ Y-m-d H:i:s ] // The time post was made.
//		  'post_date_gmt'  => [ Y-m-d H:i:s ] // The time post was made, in GMT.
//		  'comment_status' => [ 'closed' | 'open' ] // Default is the option 'default_comment_status', or 'closed'.
//		  'post_category'  => array($category_id), // Default empty.
//		  'tags_input'     => [ '<tag>, <tag>, ...' | array ] // Default empty.
//		  'tax_input'      => [ array( <taxonomy> => <array | string>, <taxonomy_other> => <array | string> ) ] // For custom taxonomies. Default empty.
//		  'page_template'  => [ <string> ] // Requires name of template file, eg template.php. Default empty.
		);  
		$post_id = wp_insert_post( $post, true );
		
		if ( $post_id && is_numeric( $post_id ) ) { //if post id isn't an error then we can add the custom fields to this post
			/*$post_excerpt = $this->new_comic_alt( $post_id, false );
			$update_post = array(
				'ID'			=>	$post_id,
				'post_excerpt'	=>	$post_excerpt,
			);
			wp_update_post( $update_post );*/
			
			
			update_post_meta( $post_id, 'comic_img', $comic_url);
			update_post_meta( $post_id, 'comic_preview', $comic_prev_url);
			update_post_meta( $post_id, 'comic_img_path', $image_filename);
			update_post_meta( $post_id, 'comic_preview_path', $preview_filename);
			wp_set_object_terms( $post_id, 'unqueued', 'queue_status', false );
			//build a list of unique characters for the purpose of adding tags
			$unique_post_characters = array();
			for ( $i = 0; $i < 6; $i++ ) {
				$post_index = 'char' . ( $i + 1);
				$post_meta_name = 'c' . ( $i + 1 );
				if ( isset( $_POST[$post_index] ) ) { //each character will be added to the characters array if it was added to _POST
					$char_name = $this->webcomic_name_validate( trim( urldecode( $_POST[$post_index] ) ) );
					
					update_post_meta( $post_id, $post_meta_name, $char_name);
					
					if ( $char_name != 'None') { //don't add a tag for an empty character
						if ( !in_array( $char_name, $unique_post_characters ) ) { //if this character isn't in the unique array of characters, add it in
							$unique_post_characters[] = $char_name;
						}
					}
				}
				else { //add the "none" character if there wasn't a character set
					update_post_meta( $post_id, $post_meta_name, 'None');
				}
			}
			
			wp_set_post_tags( $post_id, $unique_post_characters, true );
			
			//store the list of backgrounds as post meta
			for ( $i = 0; $i < 3; $i++ ) {
				$post_index = 'bg' . ( $i + 1);
				$post_meta_name = $post_index;
				$background = $this->webcomic_name_validate( trim( urldecode( $_POST[$post_index] ) ) );
				update_post_meta( $post_id, $post_meta_name, $background);
			}
			//store the list of captions as post meta
			for ( $i = 0; $i < 3; $i++ ) {
				$post_index = 'cap' . ( $i + 1);
				$post_meta_name = $post_index;
				$caption = $this->webcomic_options_caption_validate( trim( urldecode( $_POST[$post_index] ) ) );
				update_post_meta( $post_id, $post_meta_name, $caption);
			}
			//store the list of panel text (p*) as post meta
			for ( $i = 0; $i < 6; $i++ ) {
				$post_index = 'p' . ( $i + 1);
				$post_meta_name = $post_index;
				$panel = $this->webcomic_options_disclaimer_validate( urldecode( trim( $_POST[$post_index] ) ) ); //the disclaimer validation is good for all forms of text
				update_post_meta( $post_id, $post_meta_name, $panel);
			}

			
		}
		else {
			if ( !$post_id ) {
				$post_id = 'false';
			}
			$this->webcomic_print_error( 'Error in function webcomic_add_comic_to_inactive_queue from wp_insert_post( post_id = ' . $post_id . ')' );
			exit( "Unable to create a new post for the comic!" );
		}
		$ajax_reply = array( 	
			'reply' => $comic_url
		);

		//json encode the output
		echo json_encode( $ajax_reply );
		exit();
		//return( $post_id );
	}
	
	public function webcomic_add_comic_to_active_queue() {
		
		if ( ! current_user_can( 'edit_others_posts' ) ) { //only allow access to "editor" or higher
			return( false );
		}
		
		global $wpdb;
		
		$security = trim( $_POST["security"] );
		if ( ! wp_verify_nonce( $security, 'webcomic-queue-nonce' ) ) {
			exit( "Security Check Failure: Invalid Nonce" );
		}
		
		$comic_id = wp_strip_all_tags( trim( $_POST["id"] ) );
		if ( is_numeric( $comic_id ) ) { //proceed if comic id is a number
			$queue_position = $this->update_queue_numbers( false, false ) + 1;
			//the new queue position (1 based index)
			update_post_meta( $comic_id, 'queue_position', $queue_position);
			
			//change queue_status of taxonomy to 'queued'
			wp_set_object_terms( $comic_id, 'queued', 'queue_status', false );
			$ajax_reply = array( 	
				'reply' => $comic_id,
			);

			//json encode the output
			echo json_encode( $ajax_reply );

			
			
		}
		exit();
		
		
	}
	
	public function webcomic_move_queue() {
		
		if ( ! current_user_can( 'edit_others_posts' ) ) { //only allow access to "editor" or higher
			return( false );
		}
		
		$security = trim( $_POST["security"] );
		if ( ! wp_verify_nonce( $security, 'webcomic-queue-nonce' ) ) {
			exit( "Security Check Failure: Invalid Nonce" );
		}
		
		$comic_id = wp_strip_all_tags( trim( $_POST["id"] ) );
		$pos = wp_strip_all_tags( trim( $_POST["pos"] ) );
		if ( ( is_numeric( $comic_id ) ) && ( is_numeric( $pos ) ) && ( $pos > 0 ) ) { //proceed if comic id is a number and pos is a number
			$current_comic_pos = get_post_meta( $comic_id, 'queue_position', true );
			if ( is_numeric( $current_comic_pos ) ) {
				if ( $pos < $current_comic_pos ) { //moving earlier in the queue
					//find the post at the queue_position of the target and add 1 to it
					$args = array(
						'post_type'		=> 'wc-webcomic',
						'meta_key'		=> 'queue_position',
						'meta_value'	=> $pos,
						
					);
					
					$queued_posts = get_posts( $args );
					
					if ( $queued_posts ) {
						$target_id = $queued_posts[0]->ID;
						if ( is_numeric( $target_id ) ) {
							update_post_meta( $target_id, 'queue_position', $current_comic_pos);
						}
					}
					
					wp_reset_postdata();

					//set the queue position of the current post to 1 less
					update_post_meta( $comic_id, 'queue_position', $pos);
				}
				else { //moving later in the queue
					$args = array(
						'post_type'		=> 'wc-webcomic',
						'meta_key'		=> 'queue_position',
						'meta_value'	=> $pos,
						
					);
					
					$queued_posts = get_posts( $args );
					
					if ( $queued_posts ) {
						$target_id = $queued_posts[0]->ID;
						if ( is_numeric( $target_id ) ) {
							update_post_meta( $target_id, 'queue_position', $current_comic_pos);
						}
					}
					
					wp_reset_postdata();
					
					//set the queue position of the current post to 1 more
					update_post_meta( $comic_id, 'queue_position', $pos);
				}
				$ajax_reply = array( 	
				'reply' => $pos,
				);

				//json encode the output
				echo json_encode( $ajax_reply );
			
			}
		}
		exit();
	}
	public function webcomic_adjust_queue_positions () { //go through the queue and order it correctly
	
		if ( ! current_user_can( 'edit_others_posts' ) ) { //only allow access to "editor" or higher
			return( false );
		}
		
		global $wpdb;
		
		$args = array(
			'meta_key' 			=> 'queue_position',
			'post_type' 		=> 'wc-webcomic',
			'post_status'		=> 'any',
			'posts_per_page'	=> -1,
			'orderby'			=> 'meta_value_num',
			'order'				=> 'ASC',
			'queue_status' 		=> 'queued',
		);
		$posts = get_posts( $args );
		$i = 1;
		foreach ( $posts as $post ) {
			if ( is_numeric( $post->ID ) ) {
				$queue_position = get_post_meta( $post->ID, 'queue_position', true );
				if ( is_numeric( $queue_position ) ) {
					if ( $queue_position != $i ) {
						update_post_meta( $post->ID, 'queue_position', $i );
					}
				}
			}
			$i++;
		}
		
	}
	
	public function webcomic_remove_comic_from_active_queue() {
		
		if ( ! current_user_can( 'edit_others_posts' ) ) { //only allow access to "editor" or higher
			return( false );
		}
		
		global $wpdb;
		
		$security = trim( $_POST["security"] );
		if ( ! wp_verify_nonce( $security, 'webcomic-queue-nonce' ) ) {
			exit( "Security Check Failure: Invalid Nonce" );
		}
		
		$comic_id = wp_strip_all_tags( trim( $_POST["id"] ) );
		if ( is_numeric( $comic_id ) ) { //proceed if comic id is a number
			$queue_position = get_post_meta( $comic_id, 'queue_position', true);
			if ( is_numeric( $queue_position ) ) { //proceed if queue position is a number
				//remove queue position as a post meta
				$del_info = delete_post_meta( $comic_id, 'queue_position' ); 
				//update the queue positions of all the posts with higher position
				$this->webcomic_adjust_queue_positions( $queue_position );
			}
			
			//change queue_status of taxonomy to 'unqueued'
			wp_set_object_terms( $comic_id, 'unqueued', 'queue_status', false );
			$ajax_reply = array( 	
				'reply' => $comic_id,
			);

			//json encode the output
			echo json_encode( $ajax_reply );
			
			
		}
		exit();
		
		
	}
	
	public function webcomic_delete_queued_comic() {
		
		if ( ! current_user_can( 'edit_others_posts' ) ) { //only allow access to "editor" or higher
			return( false );
		}
		
		global $wpdb;
		
		$security = trim( $_POST["security"] );
		if ( ! wp_verify_nonce( $security, 'webcomic-queue-nonce' ) ) {
			exit( "Security Check Failure: Invalid Nonce" );
		}
		
		$comic_id = wp_strip_all_tags( trim( $_POST["comic"] ) );
		if ( is_numeric( $comic_id ) ) { //proceed if comic id is a number
		
			//get image url and preview url from database
			$this_post_image = $this->webcomic_options_url_validate( trim( get_post_meta( $comic_id, 'comic_img_path', true ) ) );
			$this_post_image_preview = $this->webcomic_options_url_validate( trim( get_post_meta( $comic_id, 'comic_preview_path', true ) ) );
			
			//delete the images
			unlink( $this_post_image );
			unlink( $this_post_image_preview );
			
			//remove the comic from the database
			wp_delete_post( $comic_id );
			
			$ajax_reply = array( 	
				'reply' => $comic_id
			);
			//json encode the output
			echo json_encode( $ajax_reply );
			
		}
		exit();
	}
	
	function update_queue_numbers( $update_id, $new_position ) { //will return the total size of the queue, and optionally update an id to a new numbered position (1 is next in line)
		$queue_count = 0;
		$query_args = array(
				'post_type'			=> 'wc-webcomic',
				'meta_key'			=> 'queue_position',
				'order_by'			=> 'meta_value_num',
				'queue_status' 		=> 'queued',

		);
		$queued_posts = get_posts( $query_args );		
		if ( $queued_posts ) {
			
			foreach ( $queued_posts as $queued_post ) {
				$queue_count++;
				if ( $update_id ) { //if an id is provided, set it to the new position in the queue and move everything else behind it down 1
					$current_position = trim( get_post_meta( $queued_post->ID, 'queue_position', true ) );
					if ( is_numeric( $current_position ) ) {
						if ( $current_position >= $new_position ) { //move all comics past the new position to 1 position higher
							update_post_meta( $queued_post->ID, 'queue_position', $current_position + 1 );						
						}
					}
					
				}

			}
			


		}
		if ( ( $update_id ) && ( $new_position ) ) {
			update_post_meta( $update_id, 'queue_position', $new_position );	
		}
		return( $queue_count );
	}
	
	public function webcomic_add_weekly_chron( $schedules ) {
		$schedules['weekly'] = array(
			'interval' => 7 * 24 * 60 * 60, //7 days * 24 hours * 60 minutes * 60 seconds
			'display' => __( 'Weekly' ),
		);
		
		return( $schedules );
		
	}
	
	public function webcomic_display_comic_img( $query ) {

		global $post;

		$query = $this->webcomic_display_comic_img_html( $query, $post, false );
		
		return( $query );
	}
	
	function webcomic_display_comic_img_html( $query, $post, $no_nav ) {
		
		$webcomic_category = $this->webcomic_options_category_validate( trim( get_option( 'webcomic_category' ) ) );

		if ( in_category( $webcomic_category, $post ) ) {
			$image = $this->webcomic_options_url_validate( trim( get_post_meta( $post->ID, 'comic_img', true ) ) );
		
			if( $image ) {
				
				$image_alt = $this->new_comic_alt( $post->ID, true );
				$image_html = '<img class="post_image" align="center" src="'. $image .'" alt="' . $image_alt . '"/>';
				$post_nav = $this->new_post_navigation( $post );
				$disclaimer = '<div id="webcomic-disclaimer">' . strip_tags( $this->webcomic_options_disclaimer_validate( trim( get_option( 'webcomic_disclaimer' ) ) ), '<p><a><strong><em><h1><h2><h3><h4><h5><h6><br><hr><pre><img>' ) . '</div>';
				
				if ( $no_nav ) {
					$query = $query . $image_html;
				}
				else {
					$query = $query . $image_html . $disclaimer . $post_nav;
				}
			}
		}
		return( $query );
	}
	function new_comic_alt( $post_id, $show_all ) {
		
		$alt_text = "\n";
		if ( is_numeric( $post_id ) ) {
			$j = 1;
			if ( $show_all ) { //if we want to show the whole post, go through all the panels
				$stop_point = 4;
			}
			else { //only show panel 1 otherwise (for post excerpt)
				$stop_point = 2;
			}
			for ( $i = 1; $i < $stop_point; $i++ ) {
				
				$alt_text .= "[Panel $i] \n";
				$this_bg = 'bg' . $i;
				$this_bg = strip_tags( $this->webcomic_name_validate( urldecode( trim( get_post_meta( $post_id, $this_bg, true ) ) ) ) );
				if ( $this_bg && ( $this_bg != "white" ) && ( $this_bg != "none" ) ) {
					$alt_text .= "($this_bg in the background) \n";
				}
				$this_cap = 'cap' . $i;
				$this_cap = strip_tags( $this->webcomic_options_caption_validate( urldecode( trim( get_post_meta( $post_id, $this_cap, true ) ) ) ) );
				if ( $this_cap ) {
					$alt_text .= "Caption: $this_cap \n";
				}
				$this_char_left = 'c' . $j;
				$this_char_right = 'c' . ($j + 1);
				$this_char_left = strip_tags( $this->webcomic_name_validate( urldecode( trim( get_post_meta( $post_id, $this_char_left, true ) ) ) ) );
				$this_char_right = strip_tags( $this->webcomic_name_validate( urldecode( trim( get_post_meta( $post_id, $this_char_right, true ) ) ) ) );
				
				$this_panel_left = 'p' . $j;
				$this_panel_right = 'p' . ($j + 1);
				$this_panel_left = strip_tags( $this->webcomic_options_disclaimer_validate( urldecode( trim( get_post_meta( $post_id, $this_panel_left, true ) ) ) ) );
				$this_panel_right = strip_tags( $this->webcomic_options_disclaimer_validate( urldecode( trim( get_post_meta( $post_id, $this_panel_right, true ) ) ) ) );


				
				if ( $this_panel_left ) {
				
					$this_panel_left = htmlspecialchars( $this_panel_left, ENT_COMPAT );
					
					$alt_text .= "$this_char_left: $this_panel_left \n";
				}
				if ( $this_panel_right ) {
					$this_panel_right = htmlspecialchars( $this_panel_right, ENT_COMPAT );
					
					$alt_text .= "$this_char_right: $this_panel_right \n"; 
				}
				$alt_text .= "\n"; 
				$j = $j + 2;
			}
			
		}
		return( $alt_text );
	}
	
	function new_post_navigation( $post ) {
			$webcomic_category = $this->webcomic_options_category_validate( trim( get_option( 'webcomic_category' ) ) );
			$args = array( 'posts_per_page' => 1, 'category_name' => $webcomic_category, 'orderby' => 'rand', 'post__not_in' => array( $post->ID ) );
			$random_post = get_posts( $args );
			$random_link = '';
			if ( is_numeric( $random_post[0]->ID ) ) {
				$random_link = '<a href="'.get_permalink( $random_post[0]->ID ).'" title="'. $this->webcomic_options_title_validate( get_the_title( $random_post[0]->ID ) ).'">Random</a>';
			}
			$args = array(
     				'category_name' => $webcomic_category,
     				'posts_per_page' => 1,
    				'order' => 'ASC'
			);
			$comic_query = new WP_Query( $args );
			while ( $comic_query->have_posts() ) {
  				$comic_query->the_post(); 
				$firstpost = get_permalink();
  			}
			wp_reset_query();
			$args = array(
     				'category_name' => $webcomic_category,
     				'posts_per_page' => 1,
    				'order' => 'DESC'
			);
			$comic_query = new WP_Query( $args );
			while ( $comic_query->have_posts() ) {
  				$comic_query->the_post(); 
				$lastpost = get_permalink();
  			}
			wp_reset_query();

			$link_html = '<div class="webcomic-links">';
			$current_page = get_permalink();
			if ( $current_page != $firstpost ) {
				$link_html .= '<div class="webcomic-first">';
				$link_html .= '«« <a href="' . $firstpost . '">First</a></div>';
				$link_html .= '<div class="webcomic-previous">';
				ob_start(); // start output buffering
				previous_post_link('&laquo; %link','Previous',TRUE,'');
				$previous_link = ob_get_clean(); //end output buffering
				$link_html .= $previous_link;
				$link_html .= '</div>';
			}
			$link_html .= '<div class="webcomic-random">';
			$link_html .= $random_link; 
			$link_html .= '</div>';
			$current_page = get_permalink();
			if ($current_page != $lastpost) {
				$link_html .= '<div class="webcomic-next">';
				ob_start(); // start output buffering
				next_post_link('%link &raquo;','Next',TRUE,'');
				$next_link = ob_get_clean(); //end output buffering
				$link_html .= $next_link;
				$link_html .= '</div><div class="webcomic-last">';
				$link_html .= '<a href="' . $lastpost . '">Newest</a> »»</div>';

			}
			$link_html .= '</div>';
		
		$link_html .= '<div class="webcomic-clear"></div><div class="webcomic-separator"><hr/></div>';
		return( $link_html );
	}

	function webcomic_display_preview() {
		
		$webcomic_preview = esc_html( $this->webcomic_options_disclaimer_validate( trim( get_option( 'webcomic_empty_preview' ) ) ) ); //text to display if no preview is available.
		
		$in_same_cat = true;
		$excluded_categories = '';
		$previous = false;
		$next_post = get_adjacent_post( $in_same_cat,$excluded_categories,$previous );
			
		if (is_front_page() || $next_post == '') { //if this is the front page, the preview must come from position number 1 in the queue
			
			$args=array(
			'post_type'			=> 'wc-webcomic',
			'meta_key'			=> 'queue_position',
			'meta_value'		=> '1',
			'queue_status' 		=> 'queued',


			);

			$top_of_queue = get_posts( $args );
		
			if ( $top_of_queue ) {	//only proceed if there is a comic in the queue

				$queue_post = array_shift( $top_of_queue );
				$preview_url = $this->webcomic_options_url_validate( trim( get_post_meta( $queue_post->ID, 'comic_preview', true ) ) );
				$webcomic_preview = '<img src="'.$preview_url.'" class="webcomic-preview"/>';
			}
		}
		else {	//if this isn't the front page, the preview is just the next post's preview image
		
			$preview_url = $this->webcomic_options_url_validate( trim( get_post_meta( $next_post->ID, 'comic_preview', true ) ) );
				
			$webcomic_preview = '<img src="'.$preview_url.'" class="webcomic-preview"/>';
		}
		return( $webcomic_preview );
	}
	
	function webcomic_display_latest_comic() {
		
		$webcomic_category = $this->webcomic_options_category_validate( trim( get_option( 'webcomic_category' ) ) );
		$all_comics = get_posts( array( "category_name" => $webcomic_category, "showposts" => 1 ) );
		$newest_comic = array_shift( $all_comics );
		
		$comic_img = $this->webcomic_display_comic_img_html( '', $newest_comic, false );
		return( $comic_img );
	}
	
	function webcomic_display_latest_comic_no_nav() {
		
		$webcomic_category = $this->webcomic_options_category_validate( trim( get_option( 'webcomic_category' ) ) );
		$all_comics = get_posts( array( "category_name" => $webcomic_category, "showposts" => 1 ) );
		$newest_comic = array_shift( $all_comics );
		
		$comic_img = $this->webcomic_display_comic_img_html( '', $newest_comic, true );
		return( $comic_img );
	}

	public function webcomic_force_post_comic() {
		
		if ( ! current_user_can( 'edit_others_posts' ) ) { //only allow access to "editor" or higher
			return( false );
		}
		
		$comic_id = wp_strip_all_tags( trim( $_POST["id"] ) );
		$security = trim( $_POST["security"] );
		if ( ! wp_verify_nonce( $security, 'webcomic-queue-nonce' ) ) {
			exit( "Security Check Failure: Invalid Nonce" );
		}
		if ( is_numeric( $comic_id ) ) { //only proceed if the comic ID is a number
			
			//move the top comic in the queue out of the queue and post it
			$this->webcomic_register_update();
			
			$this->webcomic_adjust_queue_positions(); //update the queue
			
			$ajax_reply = array( 	
				'reply' => $comic_id,
			);

			//json encode the output
			echo json_encode( $ajax_reply );
		}
		exit();
	}
	
	function check_base64_image( $base64 ) {
		
		$img = imagecreatefromstring( base64_decode( $base64 ) );
		if ( $img === false ) {
			$this->webcomic_print_error( 'Error in function check_base64_image from imagecreatefromstring. (base64 length = ' . strlen( $base64 ) . ')');
			return( false );
		}

		if ( ! imagepng( $img, 'tmp.png' ) ) {
			$this->webcomic_print_error( 'Error in function check_base64_image. Image failed imagepng test when attempting to write to tmp.png. (base64 length = ' . strlen( $base64 ) . ')');
			return( false );
		}
		$info = getimagesize( 'tmp.png' );

		unlink( 'tmp.png' );

		if ( $info[0] > 0 && $info[1] > 0 && $info['mime'] ) {
			return( true );
		}
		$mime = $preg_replace( "([^\w\s\d\-_\.\%\#\!\?])", '', $info['mime'] );
		$this->webcomic_print_error( "Error in function check_base64_image. Image failed size test. info[0] = " . $info[0] . " and info[1] = " . $info[1] . " and info[mime] is " . $mime );
		return( false );
	}
	
	function add_og_image() {
		global $post;
		$webcomic_category = $this->webcomic_options_category_validate( trim( get_option( 'webcomic_category' ) ) );
		if ( is_single() ){
			if ( in_category( $webcomic_category ) ) {
				$image = $this->webcomic_options_url_validate( trim( get_post_meta( $post->ID, 'comic_preview', true ) ) );
				if ( $image ) {
					echo '<meta property="og:image" content="' . $image . '" />';
					echo '<meta property="og:image:url" content="' . $image . '" />';
				}
			}
		}
	}

	
	//debug//////////////////////////////////////////////////////////////////////////////////////
	function webcomic_print_error( $error ) {
		date_default_timezone_set( 'America/Denver' );
		$error = preg_replace( "([^\w\s\d\-_\.\%\#\!\?\[\]\:\=\(\)\,\/])", '', $error );
		$error = date( 'l jS \of F Y h:i:s A' ) . " \t" . $error . "<br>\n";
		file_put_contents(
		  ABSPATH. 'wp-content/plugins/webcomic-creator-studio/error_log.html'
		, $error,  FILE_APPEND
		);
	}
	
	function webcomic_save_output_buffer_to_file()
	{
		file_put_contents(
		  ABSPATH. 'wp-content/plugins/webcomic-creator-studio/error_log.html'
		, ob_get_contents(),  FILE_APPEND
		);
	}

	//end debug//////////////////////////////////////////////////////////////////////////////////

}


?>