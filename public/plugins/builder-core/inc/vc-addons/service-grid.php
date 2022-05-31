<?php
add_shortcode( 'builder_service_grid', function( $atts, $content = null ) {
    extract( shortcode_atts( array(
        'service_type'			    => '',
        'service_count'			    => '3',
        'column'			        => '3',
        'content_length'			=> '32',
        'servicebox_alignment'		=> '',
        'link_icon'		            => '',
        'extra_class'               => '',
    ), $atts) );

    //variables start
    $grid = 12;
    $service_type = !empty( $service_type ) ? $service_type : '';
    $service_count =  isset( $service_count ) ? $service_count : 3;
    $column = isset( $column ) ? $column : 3;
    $content_length = isset( $content_length ) ? intval( $content_length ) : 32;
    $link_icon = isset( $link_icon ) ? $link_icon : 'fa fa-link';
    //variables end

    // wp_query and its argument start
    $args = array(
        'post_type' => 'service',
        'posts_per_page' => $service_count,
    );
    if( !empty( $service_type ) ) {
        $args['tax_query'] = array(
            'relation' => 'OR',
            array(
                'taxonomy' => 'service_type',
                'field' => 'slug',
                'terms'=> $service_type,
            ),
        );
    };
    $service_query = new WP_Query( $args );
    // wp_query and its argument end

    //display output start
    $output  = '<div class="row">';

    while ( $service_query->have_posts() ) : $service_query->the_post();

        $output .= '<div class="col-md-'.$grid/$column.' col-sm-'.$grid/$column.' col-xs-12 xs-mb-30">';

        $output .= '<div class="service-grid-box">';

        if( has_post_thumbnail() ) :
            $output .= '<div class="service-img-box">';
                $output .= '<img src="'.esc_url( get_the_post_thumbnail_url( null, 'builder_standard_service_thumb' ) ).'" class="img-responsive" alt="'.get_the_title().'">';
                $output .= '<div class="overley">';
                $output .= '<a href="'.get_the_permalink().'"><i class="'.$link_icon.'"></i></a>';
                $output .= '</div>';
            $output .= '</div>';
        endif;
        $output .= '<div class="service-contect-box">';
            $output .= '<h3><a href="'.get_the_permalink().'">'.get_the_title().'</a></h3>';
            $output .= '<p>'.wp_trim_words( get_the_content(), $content_length, ' ...' ).'</p>';
            $output .= '<a href="'.get_the_permalink().'" class="btn btn-service hvr-sweep-to-right">'.esc_html__( 'Saber más', 'buiulder-core' ).'</a>';
        $output .= '</div>';

        $output .= '</div>';

        $output .= '</div>';
    endwhile;
    wp_reset_postdata();
    $output .= '</div>';
    //display output end

    return $output;

} );

add_action( 'vc_before_init', 'builder_service_grid' );
function builder_service_grid() {

    vc_map( array(
        "name" => __( "Service Grid", "builder-core" ),
        "base" => "builder_service_grid",
        "class" => "",
        "category" => __( "Builder", "builder-core" ),
        "icon" => plugins_url().'/builder-core/assets/images/itl.png',
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => __( "Service Type", "builder-core" ),
                "param_name" => "service_type",
                "description" => __( "Enter service type slug or leave it blank", "builder-core" ),
                "admin_label" => true,
                "group" => "General",
            ),
            array(
                "type"          => "dropdown",
                "param_name"    => "service_count",
                "value"         => array(
                    '3'  => '3',
                    '4'  => '4',
                    '5'  => '5',
                    '6'  => '6',
                    '7'  => '7',
                    '8'  => '8',
                    '9'  => '9',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12',
                ),
                "heading"       => __( "Service Count", "builder-core" ),
                "description"   => __( "Select how many items you want to show", "builder-core" ),
                "std"           => "3",
                "group"         => "General",
            ),
            array(
                "type"          => "dropdown",
                "param_name"    => "column",
                "value"         => array(
                    '3' => '3',
                    '4' => '4',
                ),
                "heading"       => __( "Column", "builder-core" ),
                "description"   => __( "Select Column", "builder-core" ),
                "std"           => "3",
                "group"         => "General",
            ),
            array(
                "type" => "dropdown",
                "param_name" => "servicebox_alignment",
                "value" => array( 'Left' => 'left', 'Center' => 'center', 'Right' => 'right' ),
                "heading" => __("Service box Alignment", "builder-core"),
                "group" => "General",
            ),
            array(
                "type" => "textfield",
                "heading" => __( "Content Length", "builder-core" ),
                "param_name" => "content_length",
                "group" => "General",
            ),
            array(
                "type" => "iconpicker",
                "heading" => __( "Link icon", "builder-core" ),
                "param_name" => "link_icon",
                "description" => __( "Some description", "builder-core" ),
                "group" => "General",
            ),
            array(
                "type" => "textfield",
                "heading" => __( "Extra Class Name", "builder-core" ),
                "param_name" => "extra_class",
                "description" => __( "Style particular content element differently - add a class name and refer to it in custom CSS.", "builder-core" ),
                "group" => "General",
            ),
        )
    ));

}
