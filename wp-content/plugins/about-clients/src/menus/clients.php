<?php
    // Define submenu parameters.
    $sub_parent_slug = $menu_slug;
    $sub_page_title = __('Clients', 'aboutclients');
    $sub_menu_title = __($sub_page_title, 'aboutclients');
    $sub_menu_slug = $sub_parent_slug.'-clients';
    $sub_function =  [$this, 'adminPageFormClientsCallback'];

    // Add submenu page.
    add_submenu_page(
        $sub_parent_slug,
        $sub_page_title,
        $sub_menu_title,
        $capability,
        $sub_menu_slug,
        $sub_function
    );