<?php
class JobsTrackerInstall {

    public function __construct() {

        if(JOBSTRACKER_DEBUG) error_log('JobsTrackerInstall->_construct(()');
        // Other constructor code...

        // Register activation hook within the class constructor.
        register_activation_hook(JOBSTRACKER_WITH_CLASSES_FILE, [$this, 'activate']);
        
        // Register deactivation hook within the class constructor.
        register_deactivation_hook(JOBSTRACKER_WITH_CLASSES_FILE, [$this, 'deactivate']);
    }

    /**
     * Handle plugin installation upon activation.
     *
     * @since 1.7.4
     */
    public function activate() {
        if(JOBSTRACKER_DEBUG) error_log('JobsTrackerInstall->activate()');
        
        global $wpdb;

        // Your installation logic goes here.
        // This method is called when the plugin is activated.

        // For example, you might want to create necessary database tables or set default options.
        // Note: Make sure to perform actions only if they haven't been done before to avoid duplications.

        // Here's a simple example:
        $installed_version = get_option('JobsTracker_installed_version1');

        if (!$installed_version) {
                    // Start a transaction
            $wpdb->query('START TRANSACTION');

            try {

                // Run your installation code here.
                // Example: Create a database table or set default options.

                // Mark the version as installed to avoid running this again.
                update_option('jobstracker_installed_version', JOBSTRACKER_VERSION);
                $this->create_status_table();
                $this->create_companies_table();
                $this->create_company_meta_table();

                // If all database operations are successful, commit the transaction
                $wpdb->query('COMMIT');

            } catch (Exception $e) {
                
                // If an error occurs, rollback the transaction
                $wpdb->query('ROLLBACK');

                // Handle the exception (log, display error message, etc.)
                wp_die('Transaction Failed: Unable to setup the Jobs Tracker. Please contact the Plugin Administrator');

            }

        }
    }

    /**
     * Handle plugin upon deactivation.
     *
     * @since 1.7.4
     */
    public function deactivate() {
        
        if(JOBSTRACKER_DEBUG) error_log('JobsTrackerInstall->deactivate(()');
    
    }

    private function create_status_table() {

        if(JOBSTRACKER_DEBUG) error_log('JobsTrackerInstall->create_status_table(()');

        global $wpdb;


        // Create the table if it does not exist.
        $table_name = $wpdb->prefix . 'jt_company_statuses';
        $charset_collate = $wpdb->get_charset_collate();

        $table_structure = "CREATE TABLE $table_name (
            ID INT NOT NULL AUTO_INCREMENT,
            status_name VARCHAR(25) NOT NULL,
            status_description TEXT,
            status_active TINYINT(1)  NOT NULL DEFAULT 1,
            PRIMARY KEY (id)
        ) $charset_collate;";
            


        maybe_create_table($table_name, $table_structure);

        // Check if the table is empty.
        $table_empty = $wpdb->get_var("SELECT COUNT(*) FROM $table_name") == 0;

        // Insert default data if the table is empty.
        if ($table_empty) {

                $statuses = [ 
                    ['status_name' =>'Pre-Inquiry', 'status_description' => 'An company that is in the CRM, but has not taken any marketing activities (i.e. contact from purchased list with no activity). Frequently these are contacts from data list purchases.'],
                    ['status_name' => 'Engagement', 'status_description' => 'An company that has taken one or more marketing activities, but has not achieved the lead scoring threshold or route around criteria.'],
                    ['status_name' => 'MQL', 'status_description' => 'An company that’s deemed important enough for inside-sales to engage. This is the result of a contact achieving the lead scoring threshold or route around or fast-tracking criteria and as such is viewed to be a marketing qualified lead (MQL). In doing so, the type of MQL (scoring, route around, fast track) should be defined.'],
                    ['status_name' => 'Rejected', 'status_description' => 'A MQL that was rejected by inside-sales prior to engagement. When a lead is listed as rejected, a conditional field should be used to capture the rejection reason. Reasons for a lead to be rejected include: no contact information, competitor, misrouted, etc. Leads that are rejected should be reviewed by marketing and/or sales operations to improve the organization’s system programs and MQL definitions. When a lead is rejected it is not eligible to become a MQL until it moves to long term nurture..'],
                    ['status_name' => 'Engage', 'status_description' => 'The lead is actively engaged by an inside-sales resource, but the rep has not yet had a live conversation or exchange via phone, in-person, or email with the lead.'],
                    ['status_name' => 'Connected', 'status_description' => 'The lead is actively engaged by an inside-sales resource and the rep has had one or more live conversations or exchanges with the lead.'],
                    ['status_name' => 'Disqualified', 'status_description' => 'A lead that inside-sales or direct-sales engaged or connected with, but which the sales team has determined to be non-viable. When a lead is listed as disqualified, a conditional field should be used to capture the disqualification reason. Disqualification reasons include: unable to reach, no interest, not ready to buy (in timeframe defined by company), went cold (aka stopped responding), etc.'],
                    ['status_name' => 'Potential Deal', 'status_description' => 'The lead is actively engaged by an inside-sales resource and the rep has had one or more live conversations or exchanges with the lead.'],
                    ['status_name' => 'Disqualified', 'status_description' => 'This is what inside-sales sends to a direct rep. When a lead is entered into this status we recommend that a zero dollar, no timeline opportunity is created in the CRM to represent the opportunity that is being passed to the direct rep.'],                ];

               foreach($statuses as $status) {
                    $wpdb->insert($table_name,$status);
                }

        }

    }

    private function create_companies_table() {

        if(JOBSTRACKER_DEBUG) error_log('JobsTrackerInstall->create_companies_table(()');

        global $wpdb;


        // Create the table if it does not exist.
        $table_name = $wpdb->prefix . 'jt_companies';
        $charset_collate = $wpdb->get_charset_collate();

        $table_structure = "CREATE TABLE $table_name (
            ID INT NOT NULL AUTO_INCREMENT,
            company_name VARCHAR(255) NOT NULL,
            company_website VARCHAR(500) NOT NULL,
            company_industry VARCHAR(255) NOT NULL,
            company_active TINYINT(1)  NOT NULL DEFAULT 1,
            company_status_id TINYINT(2) NOT NULL DEFAULT 1,
            PRIMARY KEY (id)
        ) $charset_collate;";

        maybe_create_table($table_name, $table_structure);

        // Check if the table is empty.
        $table_empty = $wpdb->get_var("SELECT COUNT(*) FROM $table_name") == 0;

        // Insert default data if the table is empty.
        if ($table_empty) {
            $sql = "INSERT INTO wp_jt_companies (ID, company_name, company_industry, company_website)
            VALUES
                (1,'Dice','Staffing and Recruiting', 'www.dice.com'),
                (2,'Diverse Lynx','Staffing and Recruiting', 'www.diverselynx.com'),
                (3,'Stride, Inc','Staffing and Recruiting', 'www.stridelearning.com'),
                (4,'HireKeyz Inc','Staffing and Recruiting', 'hirekeyz.com'),
                (5,'Clutch','Staffing and Recruiting', 'Description for company 5')";

            $wpdb->query($sql);
        }

    }

    private function create_company_meta_table() {

        if(JOBSTRACKER_DEBUG) error_log('JobsTrackerInstall->create_company_meta_table(()');

        global $wpdb;


        // Create the table if it does not exist.
        $table_name = $wpdb->prefix . 'jt_companymeta';
        $charset_collate = $wpdb->get_charset_collate();

        $table_structure = "CREATE TABLE $table_name (
            `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `company_id` bigint(20) unsigned NOT NULL DEFAULT '0',
            `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
            `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
            PRIMARY KEY (`meta_id`),
            KEY `company_id` (`company_id`),
            KEY `meta_key` (`meta_key`(191))
          ) $charset_collate;";
          
        maybe_create_table($table_name, $table_structure);
        // Check if the table is empty.
        $table_empty = $wpdb->get_var("SELECT COUNT(*) FROM $table_name") == 0;

        // Insert default data if the table is empty.
        if ($table_empty) {
            
            $companies = [];
            $companies[1] = explode(",", "Technology and engineering recruitment solutions, Employment, Hiring, Job search, Postings, Resume Search, Open Web, Social Recruiting, Recruitment, Technical Recruiting, Talent Pipeline Management, Career Management, and Sourcing");
            $companies[2] = explode(",", "Consulting Services, IT Projects, IT Staffing, Application Development, Mobile Application, Banking and Financial Services, Insurance, Travel and Hospitality, Technology, Legacy Modernization, ODC, and Hospitals");
            $companies[3] = explode(",", "education, curriculum development, virtual school, online education, online school, public school, private school, charter school, online learning, career technical education, school choice, dual enrollment, career learning, and upskill");
            $companies[4] = explode(",", "Staffing, Recruiting");
            $companies[5] = explode(",", "Digital + Creative Staffing, User Experience, Copywriting/Editing, Design/Art Direction, Account Services, Development, Marketing, Motion/Video, Production, Project Management, Technology, Digital Design, Advertising Professionals, Marketing Professionals, Creative Talent, Creative Professionals, Graphic Designers, Staffing Solutions, and Creative Staffing");
            foreach($companies as $key => $values) {
                $c = '';
                $sql = "INSERT INTO wp_jt_companymeta ( company_id, meta_key, meta_value)
                        VALUES ";
                foreach($values as $value) {
                    $value = trim($value);
                    $sql .= "$c($key,'especialty', '$value')";
                    $c = ',';
                }
                $wpdb->query($sql);

            }

        }
    }

}