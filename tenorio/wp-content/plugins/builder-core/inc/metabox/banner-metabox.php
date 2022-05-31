<?php
add_action( 'cmb2_admin_init', 'builder_banner_metabox' );

function builder_banner_metabox() {

    // Start with an underscore to hide fields from custom fields list
    $prefix = '_builder_';

    $builder_banner = new_cmb2_box( array(
        'id'            => 'banner_meta',
        'title'         => esc_html__( 'Banner', 'builder-core' ),
        'object_types'  => array( 'page', 'post', 'project', 'service' ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
    ) );

    $builder_banner->add_field( array(
        'name' => esc_html__( 'Banner Image', 'builder-core' ),
        'id'   => 'banner_img',
        'type' => 'file',
        'options' => array(
            'url' => false,
        ),
    ) );

    $builder_banner->add_field( array(
        'name' => esc_html__( 'Disable Banner', 'builder-core' ),
        'desc' => esc_html__( 'Check this field to disable banner on it\'s single page' ),
        'id'   => 'disable_banner',
        'type' => 'checkbox',
    ) );

}