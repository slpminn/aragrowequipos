<?php

class ClientsTable extends WP_List_Table
{

    // Declare a global variable
    var $utilitiesAInstance;

    function __construct()
    {
        global $status, $page;
        parent::__construct(
            array(
                'singular' => 'reason',
                'plural'   => 'reasons',
            )
        );
        $this->utilitiesAInstance = new AboutClientsUtilities();
    }

    /**
     * Prepare the items to define data set
     */
    function prepare_items()
    {
        $data = $this->get_table_data();
        // is_bool is true when $data is 0/false.
        if (! is_array($data)) {
            $data = array();
        }
        $current_page = $this->get_pagenum();
        $total_items  = count($data);
        $total_pages  = ( $total_items ) ? ceil($total_items / ABOUTCLIENTS_ITEMS_PER_PAGE) : 1;

        $this->set_pagination_args(
            array(
                'total_items' => $total_items,
                'per_page'    => ABOUTCLIENTS_ITEMS_PER_PAGE,
                'total_pages' => $total_pages,
            )
        );

        $this->items = array_slice($data, ( ( $current_page - 1 ) * ABOUTCLIENTS_ITEMS_PER_PAGE ), ABOUTCLIENTS_ITEMS_PER_PAGE);
        /**
         * Explanation if per page is 2.
         * First Page: 1 - 1 => 0 * 2 => 0, so load array items: 0,1
         * Second Page: 2 - 1 => 1 * 2 => 2, so load array items: 2,3
         * Third Page: 3 - 1 => 2 * 2 => 4, so load array items: 4,5
         * .......
         */

        $columns  = $this->get_columns();
        $hidden   = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array( $columns, $hidden, $sortable );
    }

    /**
     * Gets the data
     */
    function get_table_data()
    {
        
        $items = $this->utilitiesAInstance->getClients();
        return $items;

    }

    /**
     *  Define Table Columns
     */
    function get_columns()
    {
        return array(
            'ID'            => '<span style="font-weight: 500">'.__('Client ID', 'aboutclients').'</span', // Render a checkbox instead of text
            'client_name'   => __('Client Name', 'aboutclients'),
            'actions'        => __('Actions', 'aboutclients'),   // New dynamic column
            'client_active' => __('Client Active', 'aboutclients'),
            'status_name'   => __('Client Status', 'aboutclients'),
        );
    }

    /**
     *  Print Columns Default if no modification needed.
     */
    function column_default($item, $column_name)
    {
      // Define what to display in the dynamic column
      if ( 'actions' === $column_name ) {
           return $this->get_actions($item['ID']);
      } else return $item[ $column_name ];
    }

    /**
     * Modify columns default look and behavior and Print Content
     */

    function column_client_active($item)
    {
        if ($item['client_active']) 
            return 'YES';
        else
            return 'NO';
    }

    function column_client_name($item)
    {
        return "</strong>{$item['client_name']}</strong>";
    }

    function column_action($item)
    {

        $output = '<div class="actions" style="display: flex">';
        $count  = count($actions);
        $index  = 0;

        foreach ($actions as $action) {
            if ($index === 0) {
                $output .= '<span style="margin-right: 5px;">' . $action['link'] . '</span>';
            } else {
                $output .= $action['link'];
            }
            ++$index;
        }

        $output .= '</div>';

        return $output;
    }

    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['ID']
        );
    }

    /**
     * Define Hidden columns.
     */
    function get_hidden_columns()
    {
        return array();
    }

    /**
     * Define Sortable columns.
     */
    function get_sortable_columns()
    {
        return array(
            'ID'            => array( 'ID', false ),
            'client_name'    => array( 'client_name'),
            'client_active'    => array( 'client_active'),
            'status_name' => array( 'status_name')
        );
    }

    function get_actions($id)
    {

        $actions[] = array(
            'class' => 'edit',
            'link'  => sprintf(
                '<button type="submit" class="btn btn-icon btn-sm btn-primary action" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="%s" data-id="%s" data-nonce="%s" style="margin: 0 5px" title="%s"><i class="fa-solid fa-pencil"></i></button>',
                __('Edit', 'aboutclients'),
                $id,
                wp_create_nonce('client_edit_nonce'),
                __('Edit the client', 'aboutclients')
            ),
        );

        $actions[] = array(
            'class' => 'edit',
            'link'  => sprintf(
                '<button type="submit" class="btn btn-icon btn-sm btn-primary action data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="%s" data-id="%s" data-nonce="%s" style="margin: 0 5px" title="%s"><i class="fa-solid fa-comment"></i></button>',
                __('Comment', 'aboutclients'),
                $id,
                wp_create_nonce('client_comment_nonce'),
                __('Comments for the client', 'aboutclients')
            ),
        );

        $actions[] = array(
            'class' => 'edit',
            'link'  => sprintf(
                '<button type="submit" class="btn btn-icon btn-sm btn-primary action data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="%s" data-id="%s" data-nonce="%s" style="margin: 0 5px" title="%s"><i class="fa-solid fa-eye"></i></button>',
                __('View Client', 'aboutclients'),
                $id,
                wp_create_nonce('client_view_nonce'),
                __('View the Client', 'aboutclients')
            ),
        );

        $index = 0;
        $output = '';

        foreach ($actions as $action) {
            if ($index === 0) {
                $output .= '<span style="margin-right: 5px;">' . $action['link'] . '</span>';
            } else {
                $output .= ' | '.$action['link'];
            }
            ++$index;
        }

        $output .= '</div>';

        return $output;

    }

    /**
     * Client-Table - Extended functions
     */

    function get_client_data($ID) {
        $item = $this->utilitiesAInstance->getClients($ID);
        return $item;
    }
    function get_all_client_comments($ID) {
        $item = $this->utilitiesAInstance->getClientComments($ID);
        return $item;
    }

    function get_all_statuses() {
        $item = $this->utilitiesAInstance->getStatuses();
        return $item;
    }

    function display_edit_client_form($items) {
        $form = '<div class="form-group row">
            <label class="col-sm-2 col-form-label" for="ID">'.__('Client Id','aboutclients').'</label>
            <div class="col-sm-10">'.$items['client']['ID'].'</div>
        </div>';
        
        $form .= '<div class="form-group row">
            <label for="client_name" class="col-sm-2 col-form-label">'.__('Client Name','aboutclients').'</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="client_name" value="'.$items['client']['client_name'].'" id="client_name" placeholder="Enter Client Name">
            </div>
        </div>';
        
        $form .= '<div class="form-group row">
            <label class="col-sm-2 col-form-label" for="client_status">'.__( 'Client Status','aboutclients' ).'</label>
            <div class="col-sm-10">
                <select name="client_status" id="client_status" class="form-control">
                    <option selected>Choose...</option>';
        foreach($items['statuses'] as $status) {
            $selected = ($items['client']['client_status_id'] == $status['ID'])?'selected':'';
            $form .= "<option value='{$status['ID']}' $selected>{$status['status_name']}</option>";
        }
        $form .= '        </select>
            </div>
        </div>';
        
        $form .= '<div class="form-group row">
            <label for="client_active" class="col-sm-2 col-form-label">'.__('Client Active','aboutclients').'</label>
            <div class="col-sm-10 form-check">
                <input type="checkbox" class="form-control" name="client_active" value="1" id="client_active">
            </div>
        </div>';

        return $form;
    }

    function display_add_comment_form($items) {
        $form = '<h2>Add Comments Form Fields Here</h2>';
        return $form;
    }
}
