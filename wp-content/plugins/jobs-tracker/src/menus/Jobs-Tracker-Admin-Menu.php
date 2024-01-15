<?php

    require_once JOBSTRACKER_PLUGIN_FILE.'src/utilities/Jobs-Tracker-Utilities.php';

	/**
	* Main JobsTracker class.
	*
	* @since 1.0.0
	* 
	* 
	* 
	*/

	 class JobsTrackerAdminMenu extends JobsTrackerUtilities{

		/**
		 * Primary class constructor.
		 *
		 * @since 1.2.2
		 */
		public function __construct() {
            // Log a message to the PHP error log indicating the execution of the constructor.
            if(JOBSTRACKER_DEBUG) error_log('JobsTrackerAdminMenu->__construct()');

            // Include necessary files.
            $this->includes();

            // Add actions for creating admin menu and registering settings.
            add_action('admin_menu', [$this, 'createMenu']);
            add_action('admin_init', [$this, 'registerSettings']);
			
		}
	
        /**
         * Create Menu Method.
         *
         * Creates the admin menu and submenus for the About Clients plugin.
         * Logs a message to the error log indicating the execution of the method.
         */
        public function createMenu() {
            // Log a message to the PHP error log indicating the execution of the method.
            if(JOBSTRACKER_DEBUG) error_log('JobsTrackerAdminMenu->createMenu()');

            require_once 'main.php';

            require_once 'setup.php';

            require_once 'companies.php';

        }

        /**
         * Register Settings Method.
         *
         * Registers settings and associated fields for the About Clients admin page.
         * Logs a message to the error log indicating the execution of the method.
         */
        public function registerSettings() {
            // Log a message to the PHP error log indicating the execution of the method.
            if(JOBSTRACKER_DEBUG) error_log('JobsTrackerAdminMenu->registerSettings()');

            // Register settings for the 'JobsTracker-se' section.
            $field = 'JOBSTRACKER_DEBUG';
            $args = [
                'type' => 'number',
                'sanitize_callback' => [ $this, 'debugCallback'],
                'default' => 0
            ];

            register_setting( 'jobstracker-se', $field, $args);

            // Add a settings field for the 'JOBSTRACKER_DEBUG' option.
            add_settings_field(
                'JOBSTRACKER_DEBUG',
                esc_html__('Debug', 'jobstracker'),
                [$this ,'debugContentCallback'],
                'jobstracker-se'
            );
        }

        /**
         *  ##################################################
         *  ################################ CALLBACK SECTION
         *  ##################################################
         */

         /**
         * Admin Page Form Callback Method.
         *
         * Outputs the content for the About Clients admin page.
         * Logs a message to the error log indicating the execution of the method.
         */
        public function adminPageFormCallback() {
            // Log a message to the PHP error log indicating the execution of the method.
            if(JOBSTRACKER_DEBUG) error_log('JobsTrackerAdminMenu->adminPageFormCallback()');
            require_once 'callbacks/form-main.php';
        }

        /**
         * Admin Page Form Setup Callback Method.
         *
         * Outputs the setup content for the About Clients admin page.
         * Logs a message to the error log indicating the execution of the method.
         */
        public function adminPageFormSetupCallback() {
            // Log a message to the PHP error log indicating the execution of the method.
            if(JOBSTRACKER_DEBUG) error_log('JobsTrackerAdminMenu->adminPageFormSetupCallback()');
            require_once 'callbacks/form-setup.php';
        }

        /**
         * Admin Page Form Companies Callback Method.
         *
         * Outputs the companies content for the Company admin page.
         * Logs a message to the error log indicating the execution of the method.
         */
        public function adminPageFormCompaniesCallback() {
            // Log a message to the PHP error log indicating the execution of the method.
            if(JOBSTRACKER_DEBUG) error_log('JobsTrackerAdminMenu->adminPageFormCompaniesCallback()');
            require_once 'callbacks/form-companies.php';
        }

        /**
         * Debug Content Callback Method.
         *
         * Outputs a checkbox for debugging purposes and logs a message to the error log.
         */
        public function debugContentCallback() {
            // Log a message to the PHP error log indicating the execution of the method.
            if(JOBSTRACKER_DEBUG) error_log('JobsTrackerAdminMenu->debugContentCallback()');

            // Retrieve the debug option from the WordPress options.
            $checked = (get_option('JOBSTRACKER_DEBUG')) ? 'checked' : '';
            $options = [
                ["value" => 0, "description" => __('Debug is Off','jobstracker')],
                ["value" => 1, "description" => __('Function Names','jobstracker')],
                ["value" => 3, "description" => __('+ Informational','jobstracker')],
                ["value" => 5, "description" => __('+ Error Only','jobstracker')],
            ];

            // Output the checkbox and its state based on the debug option.
            echo '<ul><li>';
            foreach($options as $option) {
                $checked = (get_option('JOBSTRACKER_DEBUG') == $option['value'] ) ? 'checked' : '';
                echo "<input type='radio' class='jt-radio' name='jobstracker_debug' 
                        id='jobstracker_debug-{$option['value']}' 
                        value='{$option['value']}' $checked/>{$option['description']}   ";
            }
            echo '</li></ul>';
        }


        /**
        * Debug Callback Method.
        *
        * Logs a message to the error log and returns 1 if the provided data is not NULL, otherwise returns 0.
        *
        * @param mixed $data The data to be evaluated.
        *
        * @return int Returns 1 if the provided data is not NULL, otherwise returns 0.
        */
        public function debugCallback($data) {
            
            // Log a message to the PHP error log indicating the execution of the method.
            if(JOBSTRACKER_DEBUG) error_log('JobsTrackerAdminMenu->debugCallback()');
        
            // Check if the provided data is not NULL.
            if ($data !== NULL) {
                // Return 1 if the data is not NULL.
                return $data;
            } else {
                // Return 0 if the data is NULL.
                return 0;
            }
        }

        /**
         * Register Scripts Method.
         *
         * Registers styles and scripts for the About Clients plugin.
         * Uses the `wp_register_style` and `wp_register_script` functions to register assets.
         */
        public function registerScripts() {

            // Log a message to the PHP error log indicating the execution of the method.
            if(JOBSTRACKER_DEBUG) error_log('JobsTrackerAdminMenu->registerScripts()');

            // Register About Clients stylesheet.
            wp_register_style('jobstracker', WPFORMS_ZEROSPAM_PLUGIN_URL . 'assets/css/JobsTracker.css');

            // Register About Clients JavaScript file.
            wp_register_script('jobstracker', WPFORMS_ZEROSPAM_PLUGIN_URL . 'assets/js/admin/admin-JobsTracker.js');
        }
                    
        /**
         * Load Scripts Method.
         *
         * Enqueues styles and scripts for the About Clients plugin.
         * Loads the assets only on admin pages with a query parameter of `?page=JobsTracker`.
         *
         * @param string $hook The current admin page hook.
         */
        public function loadScripts($hook) {
            
            // Log a message to the PHP error log indicating the execution of the method.
            if(JOBSTRACKER_DEBUG) error_log('JobsTrackerAdminMenu->loadScripts()');

            // Load styles and scripts only on admin pages.
            if (is_admin() === false) return;

            // Check if the current admin page hook has the expected query parameter.
            if (strpos($hook, 'page=JobsTracker') === false) return;

            // Enqueue About Clients stylesheet and script.
            wp_enqueue_style('jobstracker');
            wp_enqueue_script('jobstracker');
        }
            
            

        /**
         * Include files.
         *
         * @since 1.0.0
         */
        private function includes() {
        }

}