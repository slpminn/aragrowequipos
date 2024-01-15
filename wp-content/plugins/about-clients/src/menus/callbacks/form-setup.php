<div class="wrap aboutclients">
    <h2 class="primary"><?php echo __('About Clients - Setup', 'aboutclients') ?></h2>
    <form method="post" action="options.php">
        <?php settings_fields('aboutclients-se'); // Add necessary hidden fields to the form. ?>
        <table class="form-table">
            <?php do_settings_fields('aboutclients-se', 'default') ?>
        </table>
        <?php submit_button(); ?>
    </form>
</div>