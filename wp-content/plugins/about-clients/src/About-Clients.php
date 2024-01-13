<?php

	/**
	* Main AboutClients class.
	*
	* @since 1.0.0
	* 
	* 
	* 
	*/
	/**
	 * AboutClients Class.
	 *
	 * Extends the AboutClientsUtilities class to implement specific functionalities for the About Clients plugin.
	 * This class serves as the main class for the plugin, providing a central point for managing plugin features.
	 */

	 require_once 'About-Clients-Utilities.php';

	 class AboutClients extends AboutClientsUtilities {

		/**
		 * Constructor Method.
		 *
		 * Initializes the AboutClients class.
		 * Includes necessary files, checks for dependencies, and enqueues scripts.
		 * Actions for admin initialization and script loading are added within this constructor.
		 */
		public function __construct() {
			
			if(ABOUTCLIENTS_DEBUG) error_log('AboutClients->__construct()');

			// Include necessary files.
			$this->includes();

			// Add action to check for other plugins during admin initialization.
			add_action( 'admin_init', [$this, 'checkSomeOtherPlugin'] );

			// Add actions for enqueuing and loading scripts on admin pages.
			add_action( 'admin_enqueue_scripts', [$this, 'registerScripts'] );
			add_action( 'admin_enqueue_scripts', [$this, 'loadScripts' ] );
		
		}
	

		public function checkSomeOtherPlugin() {
			if(ABOUTCLIENTS_DEBUG) error_log('AboutClients->checkSomeOtherPlugin(()');
			
		}
	
		public function registerScripts() {

			if(ABOUTCLIENTS_DEBUG) error_log('AboutClients->registerScripts(()');

			wp_register_style( 'aboutclients', WPFORMS_ZEROSPAM_PLUGIN_URL.'assets/css/style.css' );
			wp_register_script( 'aboutclients', WPFORMS_ZEROSPAM_PLUGIN_URL.'assets/js/admin/admin-script.js' );

		}
			
		public function loadScripts( $hook ) {

			if(ABOUTCLIENTS_DEBUG) error_log('AboutClients->loadScripts(()');

			// Load only on ?page= like aboutclients
			if (is_admin()  === false ) return;
			// Load style & scripts.
			wp_enqueue_style( 'aboutclients' );
			wp_enqueue_script( 'aboutclients' );	
		}
			
			

		/**
		 * Include files.
		 *
		 * @since 1.0.0
		 */
		private function includes() {

			if(ABOUTCLIENTS_DEBUG) error_log('AboutClients->includes(()');
			
		}


}