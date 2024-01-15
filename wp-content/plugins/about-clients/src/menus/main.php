<?php

 // Define main menu parameters.
 $page_title = __('About Clients', 'aboutclients');
 $menu_title = __('About Clients', 'aboutclients');
 $menu_slug = 'aboutclients';
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