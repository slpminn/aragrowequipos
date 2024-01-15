<?php

    require_once 'About-Clients-Utilities.php';

	/**
	* Main AboutClients class.
	*
	* @since 1.0.0
	* 
	* 
	* 
	*/

	 class AboutClientsAdminMenu extends AboutClientsUtilities{

		/**
		 * Primary class constructor.
		 *
		 * @since 1.2.2
		 */
		public function __construct() {
            // Log a message to the PHP error log indicating the execution of the constructor.
            if(ABOUTCLIENTS_DEBUG) error_log('AboutClientsAdminMenu->__construct()');

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
            if(ABOUTCLIENTS_DEBUG) error_log('AboutClientsAdminMenu->createMenu()');

            require_once 'menus/main.php';

            require_once 'menus/setup.php';

            require_once 'menus/clients.php';

        }

        /**
         * Register Settings Method.
         *
         * Registers settings and associated fields for the About Clients admin page.
         * Logs a message to the error log indicating the execution of the method.
         */
        public function registerSettings() {
            // Log a message to the PHP error log indicating the execution of the method.
            if(ABOUTCLIENTS_DEBUG) error_log('AboutClientsAdminMenu->registerSettings()');

            // Register settings for the 'aboutclients-se' section.
            $field = 'aboutclients_debug';
            $args = [
                'type' => 'number',
                'sanitize_callback' => [ $this, 'debugCallback'],
                'default' => 0
            ];

            register_setting( 'aboutclients-se', $field, $args);

            // Add a settings field for the 'aboutclients_debug' option.
            add_settings_field(
                'aboutclients_debug',
                esc_html__('Debug', 'aboutclients'),
                [$this ,'debugContentCallback'],
                'aboutclients-se'
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
            if(ABOUTCLIENTS_DEBUG) error_log('AboutClientsAdminMenu->adminPageFormCallback()');
            require_once 'menus/callbacks/form-main.php';
        }

        /**
         * Admin Page Form Setup Callback Method.
         *
         * Outputs the setup content for the About Clients admin page.
         * Logs a message to the error log indicating the execution of the method.
         */
        public function adminPageFormSetupCallback() {
            // Log a message to the PHP error log indicating the execution of the method.
            if(ABOUTCLIENTS_DEBUG) error_log('AboutClientsAdminMenu->adminPageFormSetupCallback()');
            require_once 'menus/callbacks/form-setup.php';
        }

        /**
         * Admin Page Form Clients Callback Method.
         *
         * Outputs the clients content for the About Clients admin page.
         * Logs a message to the error log indicating the execution of the method.
         */
        public function adminPageFormClientsCallback() {
            // Log a message to the PHP error log indicating the execution of the method.
            if(ABOUTCLIENTS_DEBUG) error_log('AboutClientsAdminMenu->adminPageFormClientCallback()');
            require_once 'menus/callbacks/form-clients.php';
        }

        /**
         * Debug Content Callback Method.
         *
         * Outputs a checkbox for debugging purposes and logs a message to the error log.
         */
        public function debugContentCallback() {
            // Log a message to the PHP error log indicating the execution of the method.
            if(ABOUTCLIENTS_DEBUG) error_log('AboutClientsAdminMenu->debugContentCallback()');

            // Retrieve the debug option from the WordPress options.
            $checked = (get_option('aboutclients_debug')) ? 'checked' : '';
            $options = [
                ["value" => 0, "description" => 'Debug is Off'],
                ["value" => 1, "description" => 'Function Names'],
                ["value" => 3, "description" => '+ Informational'],
                ["value" => 5, "description" => '+ Error Only'],
            ];

            // Output the checkbox and its state based on the debug option.
            echo '<ul><li>';
            foreach($options as $option) {
                $checked = (get_option('aboutclients_debug') == $option['value'] ) ? 'checked' : '';
                echo "<input type='radio' class='ac-radio' name='aboutclients_debug' 
                        id='aboutclients_debug-{$option['value']}' 
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
            if(ABOUTCLIENTS_DEBUG) error_log('AboutClientsAdminMenu->debugCallback()');
        
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
            if(ABOUTCLIENTS_DEBUG) error_log('AboutClientsAdminMenu->registerScripts()');

            // Register About Clients stylesheet.
            wp_register_style('aboutclients', WPFORMS_ZEROSPAM_PLUGIN_URL . 'assets/css/aboutclients.css');

            // Register About Clients JavaScript file.
            wp_register_script('aboutclients', WPFORMS_ZEROSPAM_PLUGIN_URL . 'assets/js/admin/admin-aboutclients.js');
        }
                    
        /**
         * Load Scripts Method.
         *
         * Enqueues styles and scripts for the About Clients plugin.
         * Loads the assets only on admin pages with a query parameter of `?page=aboutclients`.
         *
         * @param string $hook The current admin page hook.
         */
        public function loadScripts($hook) {
            
            // Log a message to the PHP error log indicating the execution of the method.
            if(ABOUTCLIENTS_DEBUG) error_log('AboutClientsAdminMenu->loadScripts()');

            // Load styles and scripts only on admin pages.
            if (is_admin() === false) return;

            // Check if the current admin page hook has the expected query parameter.
            if (strpos($hook, 'page=aboutclients') === false) return;

            // Enqueue About Clients stylesheet and script.
            wp_enqueue_style('aboutclients');
            wp_enqueue_script('aboutclients');
        }
            
            

        /**
         * Include files.
         *
         * @since 1.0.0
         */
        private function includes() {
        }

}