<?php 
    $ID = isset($_POST['ID'])?$_POST['ID']:false;
    $step = isset($_POST['step'])?strtolower($_POST['step']):'list';
    require_once ABOUTCLIENTS_PLUGIN_DIR."src/components/clients/clients-$step.php";
    var_dump(get_locale());