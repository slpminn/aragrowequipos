<?php
    // Define submenu parameters.
    $sub_parent_slug = $menu_slug;
    $sub_page_title = __('Setup', 'aboutclients');
    $sub_menu_title = __($sub_page_title, 'aboutclients');
    $sub_menu_slug = $sub_parent_slug.'-setup';
    $sub_function =  [$this, 'adminPageFormSetupCallback'];

    // Add submenu page.
    add_submenu_page(
        $sub_parent_slug,
        $sub_page_title,
        $sub_menu_title,
        $capability,
        $sub_menu_slug,
        $sub_function
    );