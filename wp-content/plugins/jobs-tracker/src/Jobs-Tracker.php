<?php

	/**
	* Main JobsTracker class.
	*
	* @since 1.0.0
	* 
	* 
	* 
	*/

	 class JobsTracker {

		/**
		 * Constructor Method.
		 *
		 * Initializes the JobsTracker class.
		 * Includes necessary files, checks for dependencies, and enqueues scripts.
		 * Actions for admin initialization and script loading are added within this constructor.
		 */
		public function __construct() {
			
			if(ABOUTCLIENTS_DEBUG) error_log('JobsTracker->__construct()');

			// Include necessary files.
			$this->includes();

			// Add action to check for other plugins during admin initialization.
			add_action( 'admin_init', [$this, 'checkSomeOtherPlugin'] );

			// Add actions for enqueuing and loading scripts on admin pages.
			add_action( 'admin_enqueue_scripts', [$this, 'registerScripts'] );
			add_action( 'admin_enqueue_scripts', [$this, 'loadScripts' ] );
		
		}
	

		public function checkSomeOtherPlugin() {
			if(ABOUTCLIENTS_DEBUG) error_log('JobsTracker->checkSomeOtherPlugin(()');
			
		}
	
		public function registerScripts() {

			if(ABOUTCLIENTS_DEBUG) error_log('JobsTracker->registerScripts(()');

			wp_register_script( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js' );
			wp_register_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' );

			wp_register_style( 'jt', JOBSTRACKER_PLUGIN_URL.'assets/css/style.css' );
			wp_register_script( 'jt', JOBSTRACKER_PLUGIN_URL.'assets/js/admin/admin-script.js' );
			wp_register_script( 'jt-companies', JOBSTRACKER_PLUGIN_URL.'assets/js/admin/companies-script.js' );

		}
			
		public function loadScripts( $hook ) {

			if(ABOUTCLIENTS_DEBUG) error_log('JobsTracker->loadScripts(()');
			var_dump($hook);

			// Load only on ?page= like aboutclients
			if (is_admin()  === false ) return;
			
			// Load style & scripts.
			wp_enqueue_style( 'bootstrap' );
			wp_enqueue_script( 'bootstrap' );
			
			// Load style & scripts.
			wp_enqueue_style( 'js' );
			wp_enqueue_script( 'js' );	
			if (strpos($hook, '-companies')) {
				wp_enqueue_script( 'jt-companies' );		
			}
		}
		
		/**
		 * Include files.
		 *
		 * @since 1.0.0
		 */
		private function includes() {

			if(ABOUTCLIENTS_DEBUG) error_log('JobsTracker->includes(()');
			
		}


}