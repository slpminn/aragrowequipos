<?php

 // Define main menu parameters.
 $page_title = __('Jobs Tracker', 'jobstracker');
 $menu_title = __('Jobs Tracker', 'jobstracker');
 $menu_slug = 'jobstracker';
 $capability = 'manage_options';
 $function =  [$this, 'adminPageFormCallback'];
 $icon_url = '';
 $position = 6;

 // Add main menu page.
 add_menu_page(
     $page_title,
     $menu_title,
     $capability,
     $menu_slug,
     $function,
     $icon_url,
     $position
 );