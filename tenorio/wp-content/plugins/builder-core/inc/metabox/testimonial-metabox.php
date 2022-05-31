<?php
add_action( 'cmb2_admin_init', 'builder_testimonial_metabox' );

function builder_testimonial_metabox() {

    // Start with an underscore to hide fields from custom fields list
    $prefix = '_builder_';

    $builder_testimonial = new_cmb2_box( array(
        'id'            => 'testimonial_meta',
        'title'         => esc_html__( 'Testimonial Metabox', 'builder-core' ),
        'object_types'  => array( 'testimonial' ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
    ) );

    $builder_testimonial->add_field( array(
        'name' => esc_html__( 'Dsignation', 'builder-core' ),
        'desc' => esc_html__( 'Insert designation', 'builder-core' ),
        'id'   => 'designation',
        'type' => 'text',
    ) );

}