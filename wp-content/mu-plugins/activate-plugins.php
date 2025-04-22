<?php
add_action('init', function () {
    if (!function_exists('is_plugin_active')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    $plugins_to_activate = [
        'advanced-custom-fields/acf.php',
        'wp-graphql/wp-graphql.php',
        'wpgraphql-acf/wpgraphql-acf.php',
        'wp-stateless/wp-stateless-media.php',
    ];

    foreach ($plugins_to_activate as $plugin) {
        if (!is_plugin_active($plugin)) {
            activate_plugin($plugin);
        }
    }
});
