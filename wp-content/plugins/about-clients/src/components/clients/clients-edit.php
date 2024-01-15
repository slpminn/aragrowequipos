<?php 
    require_once 'Clients-Table.php';
    $table = new ClientsTable();
    $items['client'] = $table->get_client_data($ID);
    $items['statuses'] = $table->get_all_statuses();
?>

<div class="container-fluid mt-3 about-clients" style="padding-right: 2rem">
    <div class="row">
        <div class="col-12">
            <div class="wrap aboutclients">
            <h2 class="primary"><?php echo __('About Clients - Edit Client', 'aboutclients'); ?></h2>
            <div class=" border border-secondary p-1">
                <form id="aboutclients-content" method="POST" action="admin.php?page=aboutclients-clients">
                    <?php settings_fields('aboutclients-clients'); // Add necessary hidden fields to the form. ?>
                    <input type="hidden" name="ID" id="ID" value="<?php echo $ID ?>" />
                    <input type="hidden" name="step" id="step" value="save" />
                    <?php echo $table->display_edit_client_form($items); // Display the table. ?>
                    <?php submit_button(); ?>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>