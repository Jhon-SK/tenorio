<?php
add_shortcode( 'builder_pricing_table', function( $atts, $content = null ) {
    extract( shortcode_atts( array(
        'pricing_title'			    => esc_html( "Pricing Title", "builder-core" ),
        'pricing_value'			    => esc_html( "199.99", "builder-core" ),
        'url'			            => esc_url( "#", "builder-core" ),
        'pricing_list_items'		=> '',
        'pricing_list_text'			=> esc_html__( "Pricing List Text", "builder-core" ),
        'extra_class'               => '',
    ), $atts) );

    $pricing_items = vc_param_group_parse_atts( $atts['pricing_list_items']);
    $is_array = is_array($pricing_items) ? true:false;

    $output  = '<div class="pricing-wrap '.esc_attr( $extra_class ).'">';
        $output .= '<ul class="pricing-table basic">';
            $output .= '<li><h2>'.esc_html( $pricing_title ).'</h2></li>';
            $output .= '<li class="price">'.esc_html("$", "builder-core").' '.esc_html( $pricing_value ).'</li>';
            if(!empty($pricing_items) && $is_array) {
                foreach ( $pricing_items as $item ) {
                    $output .= '<li>'.$item["pricing_list_text"].'</li>';
                }
            }
            $output .= '<li><a class="btn btn-default hvr-sweep-to-right" href="'.esc_url( $url ).'">'.esc_html__( "Purchase", "builder-core" ).'</a></li>';
        $output .= '</ul>';
    $output .= '</div>';

    return $output;
} );

add_action( 'vc_before_init', 'builder_pricing_table' );
function builder_pricing_table() {

    vc_map( array(
        "name" => __( "Pricing Table", "builder-core" ),
        "base" => "builder_pricing_table",
        "class" => "",
        "category" => __( "Builder", "builder-core" ),
        "icon" => plugins_url().'/builder-core/assets/images/itl.png',
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => __( "Pricing Title", "builder-core" ),
                "param_name" => "pricing_title",
                "std" => esc_html__( "Pricing Title", "builder-core" ),
                "admin_label" => true,
                "group" => "General",
            ),
            array(
                "type" => "textfield",
                "heading" => __( "Pricing Value", "builder-core" ),
                "std" => __( "199.99", "builder-core" ),
                "param_name" => "pricing_value",
                "group" => "General",
            ),
            array(
                "type" => "textfield",
                "heading" => __( "Button URL", "builder-core" ),
                "param_name" => "url",
                "std" => __( "#", "builder-core" ),
                "group" => "General",
            ),
            array(
                "type" => "param_group",
                "heading" => __("Team Social", 'builder-core'),
                "param_name" => "pricing_list_items",
                "group" => "General",
                "params" => array(
                    array(
                        "type" => "textfield",
                        "heading" => __( "Pricing List Text", "builder-core" ),
                        "param_name" => "pricing_list_text",
                        "std" => esc_html__( "Pricing List Text", "builder-core" ),
                        "value" => esc_html__( "Pricing List Text", "builder-core" ),
                    ),
                ),
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
