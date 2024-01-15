<?php
class AboutClientsUtilities {

    public function __construct() {
        if(ABOUTCLIENTS_DEBUG) error_log('AboutClientsUtilities->__construct()');
    }
    
    /**
     * Retrieve statuses data.
     *
     * If $id is provided:
     * - If $id is an array, retrieve multiple statuses based on the provided IDs.
     * - If $d is a single value, retrieve a single status based on the provided ID.
     * If $id is not provided, retrieve all statuese.
     *
     * @param int|array $id Optional. Client ID or an array of statuses IDs.
     * @return array|bool|null Associative array of statuses data if found, false if not found, or null on error.
     */
    public function getStatuses($id = 0) {
        
        if(ABOUTCLIENTS_DEBUG) error_log('AboutClientsUtilities->getStatuses()');
        global $wpdb;
        
        // Define the table name with the WordPress prefix.
        $table_name = $wpdb->prefix . ABOUTCLIENTS_PREFIX . 'client_statuses a';
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
            // Return false if no matching client is found.
            return false;
        }
        
    }

    /**
     * Retrieve clients data.
     *
     * If $id is provided:
     * - If $id is an array, retrieve multiple clients based on the provided IDs.
     * - If $id is a single value, retrieve a single client based on the provided ID.
     * If $id is not provided, retrieve all clients.
     *
     * @param int|array $id Optional. Client ID or an array of client IDs.
     * @return array|bool|null Associative array of client data if found, false if not found, or null on error.
     */
    function getClients($id = 0) {

        if(ABOUTCLIENTS_DEBUG) error_log('AboutClientsUtilities->getClients()');
        global $wpdb;

        // Define the table name with the WordPress prefix.
        $table_name = $wpdb->prefix . ABOUTCLIENTS_PREFIX . 'clients a';
        $table_name2 = $wpdb->prefix . ABOUTCLIENTS_PREFIX . 'client_statuses b';
        $select = "a.*, b.status_name";
        $from = "$table_name
                    INNER JOIN $table_name2 ON a.client_status_id = b.ID";
        
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
            // Return false if no matching client is found.
            return false;
        }
    }

/**
     * Retrieve comments data for clients.
     *
     * If $client_id is provided:
     * - If $client_id is an array, retrieve multiple statuses based on the provided IDs.
     * - If $client_id is a single value, retrieve a single status based on the provided ID.
     * If $client_id is not provided, retrieve all statuese.
     *
     * @param int|array $client_id Optional. An array of comments IDs.
     * @return array|bool|null Associative array of comments data if found, false if not found, or null on error.
     */
    public function getAllClientComments($client_id = 0) {
        
        if(ABOUTCLIENTS_DEBUG) error_log('AboutClientsUtilities->getStatuses()');
        global $wpdb;
        
        // Define the table name with the WordPress prefix.
        $table_name = $wpdb->prefix . ABOUTCLIENTS_PREFIX . 'client_comments a';
        $select = "a.*";
        $from = "$table_name";
        
        // Check if $id is an array.
        if (is_array($id)) {
            // Prepare a placeholder for each ID in the array.
            $placeholders = rtrim(str_repeat('%d,', count($id)), ',');
            // Prepare SQL query for multiple IDs.
            $sql = $wpdb->prepare("SELECT $select FROM $from WHERE a.client_id IN ($placeholders)", $client_id);
        } elseif (!empty($id)) {
            // Prepare SQL query for a single ID.
            $sql = $wpdb->prepare("SELECT $select FROM $from WHERE a.client_id = %d", $client_id);
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
            // Return false if no matching client is found.
            return false;
        }
        
    }

}