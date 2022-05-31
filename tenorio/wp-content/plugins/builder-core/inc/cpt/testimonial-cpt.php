<?php
add_action( 'init', 'builder_testimonial_cpt' );

function builder_testimonial_cpt() {
    $labels = array(
        'name'               => _x( 'Testimonials', 'post type general name', 'builder-core' ),
        'singular_name'      => _x( 'Testimonial', 'post type singular name', 'builder-core' ),
        'menu_name'          => _x( 'Testimonials', 'admin menu', 'builder-core' ),
        'name_admin_bar'     => _x( 'Testimonial', 'add new on admin bar', 'builder-core' ),
        'add_new'            => _x( 'Add New', 'service', 'builder-core' ),
        'add_new_item'       => __( 'Add New Testimonial', 'builder-core' ),
        'new_item'           => __( 'New Testimonial', 'builder-core' ),
        'edit_item'          => __( 'Edit Testimonial', 'builder-core' ),
        'view_item'          => __( 'View Testimonial', 'builder-core' ),
        'all_items'          => __( 'All Testimonials', 'builder-core' ),
        'search_items'       => __( 'Search Testimonials', 'builder-core' ),
        'parent_item_colon'  => __( 'Parent Testimonial:', 'builder-core' ),
        'not_found'          => __( 'No testimonial found.', 'builder-core' ),
        'not_found_in_trash' => __( 'No testimonial found in Trash.', 'builder-core' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Description.', 'builder-core' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'testimonial' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'show_in_nav_menus'  => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-format-quote',
        'supports'           => array( 'title', 'editor', 'thumbnail' )
    );

    register_post_type( 'testimonial', $args );
}