<?php
class JobsTrackerInstall {

    var $demo;

    public function __construct() {

        if(JOBSTRACKER_DEBUG) error_log('JobsTrackerInstall->_construct(()');
        // Other constructor code...

        // Register activation hook within the class constructor.
        register_activation_hook(JOBSTRACKER_WITH_CLASSES_FILE, [$this, 'activate']);
        
        // Register deactivation hook within the class constructor.
        register_deactivation_hook(JOBSTRACKER_WITH_CLASSES_FILE, [$this, 'deactivate']);

        $this->demo = true;
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
                $this->create_company_industries_table();

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
        if ($table_empty && $this->demo) {

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
            company_industry_id INT NOT NULL,
            company_website VARCHAR(500) NOT NULL,
            company_active TINYINT(1)  NOT NULL DEFAULT 1,
            company_status_id TINYINT(2) NOT NULL DEFAULT 1,
            PRIMARY KEY (id)
        ) $charset_collate;";

        maybe_create_table($table_name, $table_structure);

        // Check if the table is empty.
        $table_empty = $wpdb->get_var("SELECT COUNT(*) FROM $table_name") == 0;

        // Insert default data if the table is empty.
        if ($table_empty && $this->demo) {
            $sql = "INSERT INTO wp_jt_companies (ID, company_name, company_industry_id, company_website)
            VALUES
                (1,'Dice',1,'www.dice.com'),
                (2,'Diverse Lynx',2,'www.diverselynx.com'),
                (3,'Stride, Inc',3,'www.stridelearning.com'),
                (4,'HireKeyz Inc',4,'hirekeyz.com'),
                (5,'Clutch',5,'clutch.com')";

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
        if ($table_empty && $this->demo) {
            
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

    private function create_company_industries_table() {

        if(JOBSTRACKER_DEBUG) error_log('JobsTrackerInstall->create_company_industries_table(()');

        global $wpdb;


        // Create the table if it does not exist.
        $table_name = $wpdb->prefix . 'jt_company_industries';
        $charset_collate = $wpdb->get_charset_collate();

        $table_structure = "CREATE TABLE $table_name (
            ID INT NOT NULL AUTO_INCREMENT,
            industry_name VARCHAR(50) NOT NULL,
            industry_parent INT NOT NULL DEFAULT 0,
            industry_active TINYINT(1)  NOT NULL DEFAULT 1,
            PRIMARY KEY (id)
        ) $charset_collate;";
            


        maybe_create_table($table_name, $table_structure);

        // Check if the table is empty.
        $table_empty = $wpdb->get_var("SELECT COUNT(*) FROM $table_name") == 0;

        // Insert default data if the table is empty.
        if ($table_empty && $this->demo) {

                $industries = [ 
                //Parent Records
                    ['ID' => 1,'industry_name' =>'Education'],
                    ['ID' => 2,'industry_name' => 'Construction'],
                    ['ID' => 3,'industry_name' => 'Design'],
                    ['ID' => 4,'industry_name' => 'Corporate Services'],
                    ['ID' => 5,'industry_name' => 'Retail'],
                    ['ID' => 6,'industry_name' => 'Energy & Mining'],
                    ['ID' => 7,'industry_name' => 'Manufacturing'],
                    ['ID' => 8,'industry_name' => 'Finance'],
                    ['ID' => 9,'industry_name' => 'Recreation & Travel'],            
                    ['ID' => 10,'industry_name' => 'Art'],
                    ['ID' => 11,'industry_name' => 'Health Care'],                
                    ['ID' => 12,'industry_name' => 'Real Estate'],
                    ['ID' => 13,'industry_name' => 'Hardware & Networking'],
                    ['ID' => 14,'industry_name' => 'Legal'],
                    ['ID' => 15,'industry_name' => 'Consumer Goods'],            
                    ['ID' => 16,'industry_name' => 'Agriculture'],
                    ['ID' => 17,'industry_name' => 'Media & Communications'],                
                    ['ID' => 18,'industry_name' => 'Nonprofit'],
                    ['ID' => 19,'industry_name' => 'Software & IT Services'],
                    ['ID' => 20,'industry_name' => 'Transportaion & Logistics'],
                    ['ID' => 21,'industry_name' => 'Entertaiment'],            
                    ['ID' => 23,'industry_name' => 'Wellness & Fitness'],
                    ['ID' => 24,'industry_name' => 'Public Safety'],                
                    ['ID' => 24,'industry_name' => 'Public Administration'],  
                    
                // Child records
                  
                // Education
                    
                    ['industry_name' => 'Education Management','industry_parent' => 1],
                    ['industry_name' => 'E-Learning','industry_parent' => 1],
                    ['industry_name' => 'Higher Education','industry_parent' => 1],
                    ['industry_name' => 'Primary/Secondary Education','industry_parent' => 1],
                    ['industry_name' => 'Research','industry_parent' => 1],
                
                 // Construction
                
                     ['industry_name' => 'Building Materials','industry_parent' => 2],
                    ['industry_name' => 'Civil Engineering','industry_parent' => 2],

                // Design

                    ['industry_name' => 'Architecture & Planning','industry_parent' => 3],
                    ['industry_name' => 'Graphic Design','industry_parent' => 3],

                // Corporate Services

                    ['industry_name' => 'Accounting','industry_parent' => 4],
                    ['industry_name' => 'Business Supplies & Equipment','industry_parent' => 4],
                    ['industry_name' => 'Environmental Services','industry_parent' => 4],
                    ['industry_name' => 'Events Services','industry_parent' => 4],
                    ['industry_name' => 'Executive Office','industry_parent' => 4],
                    ['industry_name' => 'Facilities Services','industry_parent' => 4],
                    ['industry_name' => 'Human Resources','industry_parent' => 4],
                    ['industry_name' => 'Information Services','industry_parent' => 4],
                    ['industry_name' => 'Management Consulting','industry_parent' => 4],
                    ['industry_name' => 'Outsourcing/Offshoring','industry_parent' => 4],
                    ['industry_name' => 'Professional Training & Coaching','industry_parent' => 4],
                    ['industry_name' => 'Security & Investigations','industry_parent' => 4],
                    ['industry_name' => 'Staffing & Recruiting','industry_parent' => 4],

                // Retail

                    ['industry_name' => 'Supermarkets','industry_parent' => 5],
                    ['industry_name' => 'Wholesale','industry_parent' => 5],
 
                // Energy & Mining

                    ['industry_name' => 'Mining & Metals','industry_parent' => 6],
                    ['industry_name' => 'Oil & Energy','industry_parent' => 6],
                    ['industry_name' => 'Utilities','industry_parent' => 6],
                
                // Manufacturing

                    ['industry_name' => 'Automotive','industry_parent' => 7],
                    ['industry_name' => 'Aviation & Aerospace','industry_parent' => 7],
                    ['industry_name' => 'Chemicals','industry_parent' => 7],
                    ['industry_name' => 'Defense & Space','industry_parent' => 7],
                    ['industry_name' => 'Electrical & Electronic Manufacturing','industry_parent' => 7],
                    ['industry_name' => 'Food Production','industry_parent' => 7],
                    ['industry_name' => 'Glass, Ceramics & Concrete','industry_parent' => 7],
                    ['industry_name' => 'Industrial Automation','industry_parent' => 7],
                    ['industry_name' => 'Machinery','industry_parent' => 7],
                    ['industry_name' => 'Mechanical or Industrial Engineering','industry_parent' => 7],
                    ['industry_name' => 'Packaging & Containers','industry_parent' => 7],
                    ['industry_name' => 'Paper & Forest Products','industry_parent' => 7],
                    ['industry_name' => 'Plastics','industry_parent' => 7],
                    ['industry_name' => 'Railroad Manufacture','industry_parent' => 7],
                    ['industry_name' => 'Renewables & Environment','industry_parent' => 7],
                    ['industry_name' => 'Shipbuilding','industry_parent' => 7],
                    ['industry_name' => 'Textiles','industry_parent' => 7],

                // Finance

                    ['industry_name' => 'Banking','industry_parent' => 8],
                    ['industry_name' => 'Capital Markets','industry_parent' => 8],
                    ['industry_name' => 'Financial Services','industry_parent' => 8],
                    ['industry_name' => 'Insurance','industry_parent' => 8],
                    ['industry_name' => 'Investment Banking','industry_parent' => 8],
                    ['industry_name' => 'Investment Management','industry_parent' => 8],
                    ['industry_name' => 'Venture Capital & Private Equity','industry_parent' => 8],
                
                // Recreation & Travel
                
                    ['industry_name' => 'Airlines/Aviation','industry_parent' => 9],
                    ['industry_name' => 'Gambling & Casinos','industry_parent' => 9],
                    ['industry_name' => 'Hospitality','industry_parent' => 9],
                    ['industry_name' => 'Leisure, Travel & Tourism','industry_parent' => 9],
                    ['industry_name' => 'Restaurants','industry_parent' => 9],
                    ['industry_name' => 'Recreational Facilities & Services','industry_parent' => 9],
                    ['industry_name' => 'Sports','industry_parent' => 9],
                
                // Arts
                
                    ['industry_name' => 'Arts & Crafts','industry_parent' => 10],
                    ['industry_name' => 'Fine Art','industry_parent' => 10],
                    ['industry_name' => 'Performing Arts','industry_parent' => 10],
                    ['industry_name' => 'Photography','industry_parent' => 10],
                
                // Health Care
                
                    ['industry_name' => 'Biotechnology','industry_parent' => 11],
                    ['industry_name' => 'Hospital & Health Care','industry_parent' => 11],
                    ['industry_name' => 'Medical Device','industry_parent' => 11],
                    ['industry_name' => 'Medical Practice','industry_parent' => 11],
                    ['industry_name' => 'Mental Health Care','industry_parent' => 11],
                    ['industry_name' => 'Pharmaceuticals','industry_parent' => 11],
                    ['industry_name' => 'Veterinary','industry_parent' => 11],
                
                // Hardware & Networking
                
                    ['industry_name' => 'Computer Hardware','industry_parent' => 12],
                    ['industry_name' => 'Computer Networking','industry_parent' => 12],
                    ['industry_name' => 'Nanotechnologie','industry_parent' => 12],
                    ['industry_name' => 'Semiconductors','industry_parent' => 12],
                    ['industry_name' => 'Telecommunications','industry_parent' => 12],
                    ['industry_name' => 'Wireless','industry_parent' => 12],
                
                //Real Estate
                
                    ['industry_name' => 'Commercial Real Estate','industry_parent' => 13],
                
                // Legal
                
                    ['industry_name' => 'Alternative Dispute Resolution','industry_parent' => 14],
                    ['industry_name' => 'Law Practice','industry_parent' => 14],
                
                // Consumer Goods
                
                    ['industry_name' => 'Apparel & Fashion','industry_parent' => 15],
                    ['industry_name' => 'Consumer Electronics','industry_parent' => 15],
                    ['industry_name' => 'Consumer Services','industry_parent' => 15],
                    ['industry_name' => 'Cosmetics','industry_parent' => 15],
                    ['industry_name' => 'Food & Beverages','industry_parent' => 15],
                    ['industry_name' => 'Furniture','industry_parent' => 15],
                    ['industry_name' => 'Luxury Goods & Jewelry','industry_parent' => 15],
                    ['industry_name' => 'Sporting Goods','industry_parent' => 15],
                    ['industry_name' => 'Tobacco','industry_parent' => 15],
                    ['industry_name' => 'Wine and Spirits','industry_parent' => 15],
                
                // Agriculture
                
                    ['industry_name' => 'Dairy','industry_parent' => 16],
                    ['industry_name' => 'Farming','industry_parent' => 16],
                    ['industry_name' => 'Fishery','industry_parent' => 16],
                    ['industry_name' => 'Ranching','industry_parent' => 16],
                
                // Media & Communications
                
                    ['industry_name' => 'Market Research','industry_parent' => 17],
                    ['industry_name' => 'Marketing & Advertising','industry_parent' => 17],
                    ['industry_name' => 'Newspapers','industry_parent' => 17],
                    ['industry_name' => 'Online Media','industry_parent' => 17],
                    ['industry_name' => 'Printing','industry_parent' => 17],
                    ['industry_name' => 'Public Relations & Communications','industry_parent' => 17],
                    ['industry_name' => 'Publishing','industry_parent' => 17],
                    ['industry_name' => 'Translation & Localization','industry_parent' => 17],
                    ['industry_name' => 'Writing & Editing','industry_parent' => 17],
                
               // Nonprofit
                
                    ['industry_name' => 'Civic & Social Organization','industry_parent' => 18],
                    ['industry_name' => 'Fundraising','industry_parent' => 18],
                    ['industry_name' => 'Individual & Family Services','industry_parent' => 18],
                    ['industry_name' => 'International Trade & Development','industry_parent' => 18],
                    ['industry_name' => 'Libraries','industry_parent' => 18],
                    ['industry_name' => 'Museums & Institutions','industry_parent' => 18],
                    ['industry_name' => 'Non-Profit Organization Management','industry_parent' => 18],
                    ['industry_name' => 'Philanthropy','industry_parent' => 18],
                    ['industry_name' => 'Program Development','industry_parent' => 18],
                    ['industry_name' => 'Religious Institutions','industry_parent' => 18],
                    ['industry_name' => 'Think Tanks','industry_parent' => 18],
                
                //Software & IT Services
                
                    ['industry_name' => 'Computer & Network Security','industry_parent' => 19],
                    ['industry_name' => 'Computer Software','industry_parent' => 19],
                    ['industry_name' => 'Information Technology & Services','industry_parent' => 19],
                    ['industry_name' => 'Internet','industry_parent' => 19],
                
                // Transportation & Logistics
                
                    ['industry_name' => 'Import & Export','industry_parent' => 20],
                    ['industry_name' => 'Logistics & Supply Chain','industry_parent' => 20],
                    ['industry_name' => 'Maritime','industry_parent' => 20],
                    ['industry_name' => 'Package/Freight Delivery','industry_parent' => 20],
                    ['industry_name' => 'Transportation/Trucking/Railroad','industry_parent' => 20],
                    ['industry_name' => 'Warehousing','industry_parent' => 20],
                
                // Entertainment
                
                    ['industry_name' => 'Animation','industry_parent' => 21],
                    ['industry_name' => 'Broadcast Media','industry_parent' => 21],
                    ['industry_name' => 'Computer Games','industry_parent' => 21],
                    ['industry_name' => 'Entertainment','industry_parent' => 21],
                    ['industry_name' => 'Media Production','industry_parent' => 21],
                    ['industry_name' => 'Mobile Games','industry_parent' => 21],
                    ['industry_name' => 'Motion Pictures & Film','industry_parent' => 21],
                    ['industry_name' => 'Music','industry_parent' => 21],
                
                // Wellness & Fitness
                
                    ['industry_name' => 'Alternative Medicine','industry_parent' => 22],
                    ['industry_name' => 'Health, Wellness & Fitness','industry_parent' => 22],
                
                // Public Safety
                
                    ['industry_name' => 'Law Enforcement','industry_parent' => 23],
                    ['industry_name' => 'Military','industry_parent' => 23],
                    ['industry_name' => 'Public Safety','industry_parent' => 23],
                
                // Public Administration
                
                    ['industry_name' => 'Government Administration','industry_parent' => 24],
                    ['industry_name' => 'Government Relations','industry_parent' => 24],
                    ['industry_name' => 'International Affairs','industry_parent' => 24],
                    ['industry_name' => 'Judiciary','industry_parent' => 24],
                    ['industry_name' => 'Legislative Office','industry_parent' => 24],
                    ['industry_name' => 'Political Organization','industry_parent' => 24],
                    ['industry_name' => 'Public Policy','industry_parent' => 24],

                ];

               foreach($industries as $industry) {
                    $wpdb->insert($table_name,$industry);
                }

        }

    }

}