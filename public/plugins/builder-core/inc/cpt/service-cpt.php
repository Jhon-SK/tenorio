<?php

function builder_service_cpt() {
    $labels = array(
        'name'               => _x( 'Services', 'post type general name', 'builder-core' ),
        'singular_name'      => _x( 'Service', 'post type singular name', 'builder-core' ),
        'menu_name'          => _x( 'Services', 'admin menu', 'builder-core' ),
        'name_admin_bar'     => _x( 'Service', 'add new on admin bar', 'builder-core' ),
        'add_new'            => _x( 'Add New Service', 'service', 'builder-core' ),
        'add_new_item'       => __( 'Add New Service', 'builder-core' ),
        'new_item'           => __( 'New Service', 'builder-core' ),
        'edit_item'          => __( 'Edit Service', 'builder-core' ),
        'view_item'          => __( 'View Service', 'builder-core' ),
        'all_items'          => __( 'All Services', 'builder-core' ),
        'search_items'       => __( 'Search Services', 'builder-core' ),
        'parent_item_colon'  => __( 'Parent Service:', 'builder-core' ),
        'not_found'          => __( 'No service found.', 'builder-core' ),
        'not_found_in_trash' => __( 'No service found in Trash.', 'builder-core' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Description.', 'builder-core' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'servicios' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-admin-tools',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' )
    );

    register_post_type( 'service', $args );
}
add_action( 'init', 'builder_service_cpt' );

function builder_service_taxonomy() {

    $labels = array(
        'name'              => _x( 'Service Types', 'taxonomy general name', 'builder-core' ),
        'singular_name'     => _x( 'Service Type', 'taxonomy singular name', 'builder-core' ),
        'search_items'      => __( 'Search Service Types', 'builder-core' ),
        'all_items'         => __( 'All Service Types', 'builder-core' ),
        'parent_item'       => __( 'Parent Service Type', 'builder-core' ),
        'parent_item_colon' => __( 'Parent Service Type:', 'builder-core' ),
        'edit_item'         => __( 'Edit Service Type', 'builder-core' ),
        'update_item'       => __( 'Update Service Type', 'builder-core' ),
        'add_new_item'      => __( 'Add New Service Type', 'builder-core' ),
        'new_item_name'     => __( 'New Service Type Name', 'builder-core' ),
        'menu_name'         => __( 'Service Type', 'builder-core' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_nav_menus' => false,
        'rewrite'           => array( 'slug' => 'tipo-servicio' ),
    );

    register_taxonomy( 'service_type', array( 'service' ), $args );

}
add_action( 'init', 'builder_service_taxonomy', 0 );
