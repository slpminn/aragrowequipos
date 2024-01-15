<?php
class JobsTrackerUtilities {

    public function __construct() {
        if(JOBSTRACKER_DEBUG) error_log('JobsTrackerUtilities->__construct()');
    }
    
    /**
     * Retrieve statuses data.
     *
     * If $id is provided:
     * - If $id is an array, retrieve multiple statuses based on the provided IDs.
     * - If $d is a single value, retrieve a single status based on the provided ID.
     * If $id is not provided, retrieve all statuese.
     *
     * @param int|array $id Optional. company ID or an array of statuses IDs.
     * @return array|bool|null Associative array of statuses data if found, false if not found, or null on error.
     */
    public function getStatuses($id = 0) {
        
        if(JOBSTRACKER_DEBUG) error_log('JobsTrackerUtilities->getStatuses()');
        global $wpdb;
        
        // Define the table name with the WordPress prefix.
        $table_name = $wpdb->prefix . JOBSTRACKER_PREFIX . 'company_statuses a';
        $select = "a.*";
        $from = "$table_name";
        
        // Check if $id is an array.
        if (is_array($id)) {
            // Prepare a placeholder for each ID in the array.
            $placeholders = rtrim(str_repeat('%d,', count($id)), ',');
            // Prepare SQL query for multiple IDs.
            $sql = $wpdb->prepare("SELECT $select FROM $from WHERE a.ID IN ($placeholders)", $id);
        } elseif (!empty($id)) {
            // Prepare SQL query for a single ID.
            $sql = $wpdb->prepare("SELECT $select FROM $from WHERE a.ID = %d", $id);
        } else {
            // Prepare SQL query to retrieve all.
            $sql = "SELECT $select FROM $from";
        }

        // Execute the SQL query.
        $result = $wpdb->get_results($sql, ARRAY_A);

        // Check if any results are found.
        if ($result) {
            // Return the retrieved data.
            if (count($result) == 1) return $result[0];
            else return $result;
        } else {
            // Return false if no matching company is found.
            return false;
        }
        
    }

    /**
     * Retrieve company data.
     *
     * If $id is provided:
     * - If $id is an array, retrieve multiple companies based on the provided IDs.
     * - If $id is a single value, retrieve a single company based on the provided ID.
     * If $id is not provided, retrieve all companies.
     *
     * @param int|array $id Optional. company ID or an array of company IDs.
     * @return array|bool|null Associative array of company data if found, false if not found, or null on error.
     */
    function getCompanies($id = 0) {

        if(JOBSTRACKER_DEBUG) error_log('JobsTrackerUtilities->getCompanies()');
        global $wpdb;

        // Define the table name with the WordPress prefix.
        $table_name = $wpdb->prefix . JOBSTRACKER_PREFIX . 'companies a';
        $table_name2 = $wpdb->prefix . JOBSTRACKER_PREFIX . 'company_statuses b';
        $select = "a.*, b.status_name";
        $from = "$table_name
                    INNER JOIN $table_name2 ON a.company_status_id = b.ID";
        
        // Check if $id is an array.
        if (is_array($id)) {
            // Prepare a placeholder for each ID in the array.
            $placeholders = rtrim(str_repeat('%d,', count($id)), ',');
            // Prepare SQL query for multiple IDs.
            $sql = $wpdb->prepare("SELECT $select FROM $from WHERE a.ID IN ($placeholders)", $id);
        } elseif (!empty($id)) {
            // Prepare SQL query for a single ID.
            $sql = $wpdb->prepare("SELECT $select FROM $from WHERE a.ID = %d", $id);
        } else {
            // Prepare SQL query to retrieve all.
            $sql = "SELECT $select FROM $from";
        }

        // Execute the SQL query.
        $result = $wpdb->get_results($sql, ARRAY_A);

        // Check if any results are found.
        if ($result) {
            // Return the retrieved data.
            if (count($result) == 1) return $result[0];
            else return $result;
        } else {
            // Return false if no matching company is found.
            return false;
        }
    }

/**
     * Retrieve specialities data for companies.
     *
     * If $company_id is provided:
     * - If $company_id is an array, retrieve multiple specialities based on the provided IDs.
     * - If $company_id is a single value, retrieve a single specialitis based on the provided ID.
     * If $company_id is not provided, retrieve all specialities.
     *
     * @param int|array $company_id Optional. An array of comments IDs.
     * @return array|bool|null Associative array of comments data if found, false if not found, or null on error.
     */
    public function getAllCompanySpecialities($company_id = 0) {
        
        if(JOBSTRACKER_DEBUG) error_log('JobsTrackerUtilities->getAllCompanySpecialities()');
        global $wpdb;
        
        // Define the table name with the WordPress prefix.
        $table_name = $wpdb->prefix . JOBSTRACKER_PREFIX . 'company_meta a';
        $select = "a.*";
        $from = "$table_name";
        $where = "AND a.meta_key = 'speciality'";
        $orderby = "ORDER BY a.company_id, a.meta_value";
        // Check if $id is an array.
        if (is_array($id)) {
            // Prepare a placeholder for each ID in the array.
            $placeholders = rtrim(str_repeat('%d,', count($id)), ',');
            // Prepare SQL query for multiple IDs.
            $sql = $wpdb->prepare("SELECT $select FROM $from WHERE a.company_id IN ($placeholders) $where $orderby", $company_id);
        } elseif (!empty($id)) {
            // Prepare SQL query for a single ID.
            $sql = $wpdb->prepare("SELECT $select FROM $from WHERE a.company_id = %d $where $orderby", $company_id);
        } else {
            // Prepare SQL query to retrieve all.
            $sql = "SELECT $select FROM $from WHERE 1=1 $wher $orderby";
        }

        // Execute the SQL query.
        $result = $wpdb->get_results($sql, ARRAY_A);

        // Check if any results are found.
        if ($result) {
            // Return the retrieved data.
            if (count($result) == 1) return $result[0];
            else return $result;
        } else {
            // Return false if no matching company is found.
            return false;
        }
        
    }

}