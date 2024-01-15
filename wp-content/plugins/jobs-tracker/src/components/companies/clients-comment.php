<?php 
    require_once 'Clients-Table.php';
    $table = new ClientsTable();
    $items['client'] = $table->get_client_data($ID);
?>

<div class="container-fluid mt-3 about-clients" style="padding-right: 2rem">
    <div class="row">
        <div class="col-12">
            <div class="wrap aboutclients">
            <h2 class="primary"><?php echo __('About Clients - Add Comments to Client', 'aboutclients'); ?></h2>
            <form id="aboutclients-content" method="POST" action="admin.php?page=aboutclients-clients">
                <?php settings_fields('aboutclients-clients'); // Add necessary hidden fields to the form. ?>
                <input type="hidden" name="ID" id="ID" value="<?php echo $ID ?>" />
                <input type="hidden" name="step" id="step" value="save" />
                <?php echo $table->display_add_comment_form($items); // Display the table. ?>
            </form>
            </div>
        </div>
    </div>
</div>