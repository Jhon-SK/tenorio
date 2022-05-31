<?php

function builder_project_cpt() {
    $labels = array(
        'name'               => _x( 'Projects', 'post type general name', 'builder-core' ),
        'singular_name'      => _x( 'Project', 'post type singular name', 'builder-core' ),
        'menu_name'          => _x( 'Projects', 'admin menu', 'builder-core' ),
        'name_admin_bar'     => _x( 'Project', 'add new on admin bar', 'builder-core' ),
        'add_new'            => _x( 'Add New', 'service', 'builder-core' ),
        'add_new_item'       => __( 'Add New Project', 'builder-core' ),
        'new_item'           => __( 'New Project', 'builder-core' ),
        'edit_item'          => __( 'Edit Project', 'builder-core' ),
        'view_item'          => __( 'View Project', 'builder-core' ),
        'all_items'          => __( 'All Projects', 'builder-core' ),
        'search_items'       => __( 'Search Projects', 'builder-core' ),
        'parent_item_colon'  => __( 'Parent Project:', 'builder-core' ),
        'not_found'          => __( 'No project found.', 'builder-core' ),
        'not_found_in_trash' => __( 'No project found in Trash.', 'builder-core' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Description.', 'builder-core' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'proyectos' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-screenoptions',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' )
    );

    register_post_type( 'project', $args );
}
add_action( 'init', 'builder_project_cpt' );

function builder_project_taxonomy() {

    $labels = array(
        'name'              => _x( 'Project Types', 'taxonomy general name', 'builder-core' ),
        'singular_name'     => _x( 'Project Type', 'taxonomy singular name', 'builder-core' ),
        'search_items'      => __( 'Search Project Types', 'builder-core' ),
        'all_items'         => __( 'All Project Types', 'builder-core' ),
        'parent_item'       => __( 'Parent Project Type', 'builder-core' ),
        'parent_item_colon' => __( 'Parent Project Type:', 'builder-core' ),
        'edit_item'         => __( 'Edit Project Type', 'builder-core' ),
        'update_item'       => __( 'Update Project Type', 'builder-core' ),
        'add_new_item'      => __( 'Add New Project Type', 'builder-core' ),
        'new_item_name'     => __( 'New Project Type Name', 'builder-core' ),
        'menu_name'         => __( 'Project Type', 'builder-core' ),
    );

    $args = array(
        'hierarchical'      => false,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_nav_menus' => false,
        'rewrite'           => array( 'slug' => 'project-type' ),
    );

    register_taxonomy( 'project_type', array( 'project' ), $args );

}
add_action( 'init', 'builder_project_taxonomy', 0 );
