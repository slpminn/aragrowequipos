<?php 
    $ID = isset($_POST['ID'])?$_POST['ID']:false;
    $step = isset($_POST['step'])?strtolower($_POST['step']):'list';
    //var_dump($step);
    require_once JOBSTRACKER_PLUGIN_DIR."src/components/companies/companies-$step.php";
    //var_dump(get_locale());