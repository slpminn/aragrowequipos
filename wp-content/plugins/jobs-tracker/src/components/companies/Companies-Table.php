<?php

class CompaniesTable extends WP_List_Table
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
        $this->utilitiesAInstance = new jobstrackerUtilities();
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
        $total_pages  = ( $total_items ) ? ceil($total_items / JOBSTRACKER_ITEMS_PER_PAGE) : 1;

        $this->set_pagination_args(
            array(
                'total_items' => $total_items,
                'per_page'    => JOBSTRACKER_ITEMS_PER_PAGE,
                'total_pages' => $total_pages,
            )
        );

        $this->items = array_slice($data, ( ( $current_page - 1 ) * JOBSTRACKER_ITEMS_PER_PAGE ), JOBSTRACKER_ITEMS_PER_PAGE);
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
        
        $items = $this->utilitiesAInstance->getCompanies();
        return $items;

    }

    /**
     *  Define Table Columns
     */
    function get_columns()
    {
        return array(
            'ID'            => '<span style="font-weight: 500">'.__('Company ID', 'jobstracker').'</span', // Render a checkbox instead of text
            'company_name'   => __('Company Name', 'jobstracker'),
            'actions'        => __('Actions', 'jobstracker'),   // New dynamic column
            'company_active' => __('Company Active', 'jobstracker'),
            'status_name'   => __('Company Status', 'jobstracker'),
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

    function column_company_active($item)
    {
        if ($item['company_active']) 
            return __('YES','jobstracker');
        else
            return __('NO','jobstracker');
    }

    function column_company_name($item)
    {
        return "</strong>{$item['company_name']}</strong>";
    }

    function column_company_status($item)
    {
        return __($item['company_status'],'jobstracker');
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
            'company_name'    => array( 'company_name'),
            'company_active'    => array( 'company_active'),
            'status_name' => array( 'status_name')
        );
    }

    function get_actions($id)
    {

        $actions[] = array(
            'class' => 'edit',
            'link'  => sprintf(
                '<button type="submit" class="btn btn-icon btn-sm btn-primary action" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="%s" data-id="%s" data-nonce="%s" style="margin: 0 5px" title="%s"><i class="fa-solid fa-pencil"></i></button>',
                'edit',
                $id,
                wp_create_nonce('company_edit_nonce'),
                __('Edit the Company', 'jobstracker')
            ),
        );

        $actions[] = array(
            'class' => 'edit',
            'link'  => sprintf(
                '<button type="submit" class="btn btn-icon btn-sm btn-primary action data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="%s" data-id="%s" data-nonce="%s" style="margin: 0 5px" title="%s"><i class="fa-solid fa-comment"></i></button>',
                'speciality',
                $id,
                wp_create_nonce('company_speciality_nonce'),
                __('Specialities of the Company', 'jobstracker')
            ),
        );

        $actions[] = array(
            'class' => 'edit',
            'link'  => sprintf(
                '<button type="submit" class="btn btn-icon btn-sm btn-primary action data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="%s" data-id="%s" data-nonce="%s" style="margin: 0 5px" title="%s"><i class="fa-solid fa-eye"></i></button>',
                'view',
                $id,
                wp_create_nonce('company_view_nonce'),
                __('View the Company', 'jobstracker')
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

    function get_company_data($ID) {
        $item = $this->utilitiesAInstance->getCompanies($ID);
        return $item;
    }
    function get_all_company_specialties($ID) {
        $item = $this->utilitiesAInstance->getAllCompanySpecialties($ID);
        return $item;
    }

    function get_all_statuses() {
        $item = $this->utilitiesAInstance->getStatuses();
        return $item;
    }

    function display_edit_company_form($items) {
        $form = '<div class="form-group row">
            <label class="col-sm-2 col-form-label" for="ID">'.__('Company Id','jobstracker').'</label>
            <div class="col-sm-10">'.$items['company']['ID'].'</div>
        </div>';
        
        $form .= '<div class="form-group row">
            <label for="company_name" class="col-sm-2 col-form-label">'.__('Company Name','jobstracker').'</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="company_name" value="'.$items['company']['company_name'].'" id="company_name" placeholder="Enter Company Name">
            </div>
        </div>';
        
        $form .= '<div class="form-group row">
            <label class="col-sm-2 col-form-label" for="company_status">'.__( 'Company Status','jobstracker' ).'</label>
            <div class="col-sm-10">
                <select name="company_status" id="company_status" class="form-control">
                    <option selected>Choose...</option>';
        foreach($items['statuses'] as $status) {
            $selected = ($items['company']['company_status_id'] == $status['ID'])?'selected':'';
            $form .= "<option value='{$status['ID']}' $selected>{$status['status_name']}</option>";
        }
        $form .= '        </select>
            </div>
        </div>';
        
        $checked = ($items['company']['company_active'])?'checked':'';
        $form .= '<div class="form-group row">
            <label for="company_active" class="col-sm-2 col-form-label">'.__('Company Active','jobstracker').'</label>
            <div class="col-sm-10 form-check">
                <input type="checkbox" class="form-control" name="company_active" value="1" id="company_active" '.$checked.'>
            </div>
        </div>';

        return $form;
    }

    function display_add_comment_form($items) {
        $form = '<h2>Add Comments Form Fields Here</h2>';
        return $form;
    }
}
