<div class="wrap jobstracker">
    <h2 class="primary"><?php echo __('Jobs Tracker','jobstracker').' - '.__('Setup', 'jobstracker') ?></h2>
    <form method="post" action="options.php">
        <?php settings_fields('jobstracker-se'); // Add necessary hidden fields to the form. ?>
        <table class="form-table">
            <?php do_settings_fields('jobstracker-se', 'default') ?>
        </table>
        <?php submit_button(); ?>
    </form>
</div>