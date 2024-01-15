<?php 
    require_once 'Companies-Table.php';
    $table = new CompaniesTable();
    $items['company'] = $table->get_company_data($ID);
    $items['statuses'] = $table->get_all_statuses();
?>

<div class="container-fluid mt-3 jobs-tracker style="padding-right: 2rem">
    <div class="row">
        <div class="col-12">
            <div class="wrap jobstracker">
            <h2 class="primary"><?php echo __('Jobs Tracker','jobstracker').' - '.__('Edit Company', 'jobstracker'); ?></h2>
            <div class=" border border-secondary p-1">
                <form id="jobstgracker-content" method="POST" action="admin.php?page=jobstracker-clients">
                    <?php settings_fields('jobstracker-company'); // Add necessary hidden fields to the form. ?>
                    <input type="hidden" name="ID" id="ID" value="<?php echo $ID ?>" />
                    <input type="hidden" name="step" id="step" value="save" />
                    <?php echo $table->display_edit_company_form($items); // Display the table. ?>
                    <?php submit_button(); ?>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>