<?php

    require_once 'Clients-Table.php';

    global $wpdb;

    $table = new ClientsTable();
    $table->prepare_items();

    $message = '';

    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'arc_custom_table'), count($request_id)) . '</p></div>';
    }
?>
    <div class="container-fluid mt-3 about-clients" style="padding-right: 2rem">
        <div class="row">
            <div class="col-12">
                <?php echo $message; ?>
            </div>
            <div class="col-12 d-flex align-items-center" style="margin-top: .5rem;">
                <form id="aboutclients-search" method="GET">
                <?php $table->search_box('Search Clients', 'search_request_id'); ?>
                </form>
            </div>
            <div class="col-12">
                <div class="wrap aboutclients">
                <h2 class="primary"><?php echo __('About Clients', 'aboutclients').' - '.__('Clients List', 'aboutclients'); ?></h2>
                <form id="aboutclients-content" method="POST" action="admin.php?page=aboutclients-clients">
                    <?php settings_fields('aboutclients-clients'); // Add necessary hidden fields to the form. ?>
                    <input type="hidden" name="ID" id="ID" value="<?php echo $ID ?>" />
                    <input type="hidden" name="step" id="step" value="<?php echo $step ?>" />
                </form>
                </div>
                <?php $table->display(); // Display the table created by prepare_items. ?>
            </div>
        </div>
</div>