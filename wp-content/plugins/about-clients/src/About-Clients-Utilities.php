<?php
class AboutClientsUtilities {

    public function __construct() {
        if(ABOUTCLIENTS_DEBUG) error_log('AboutClientsUtilities->__construct()');
    }

    /**
     * Get Statuses Method.
     *
     * Retrieves statuses data based on the provided statuse ID or returns all statuses if no ID is specified.
     *
     * @param int $id Optional. The ID/s of the status/es to retrieve. Default is 0 (retrieve all statuses).
     */

     public function getStatuses($id = 0) {
        
        if(ABOUTCLIENTS_DEBUG) error_log('AboutClientsUtilities->getStatuses()');
        require_once 'utilities/get-statuses.php';
        
     
    }

    /**
     * Get Clients Method.
     *
     * Retrieves client data based on the provided client ID or returns all clients if no ID is specified.
     *
     * @param int $id Optional. The ID/s of the client/s to retrieve. Default is 0 (retrieve all clients).
     */

    public function getClients($id = 0) {
        
        if(ABOUTCLIENTS_DEBUG) error_log('AboutClientsUtilities->getClients()');
        require_once 'utilities/get-clients.php';
        
     
    }

}