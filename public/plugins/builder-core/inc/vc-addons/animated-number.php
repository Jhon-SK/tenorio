<?php
add_shortcode( 'builder_animated_counter', function( $atts, $content = null ) {
    extract( shortcode_atts( array(
        'counter_number'			=> '',
        'title_text'			    => '',
        'icon_type'			        => 'fontawesome',
        'fontawesome_icon'			=> '',
        'flat_icon'                 => '',
        'extra_class'               => '',
    ), $atts) );

    $icon = ( $icon_type === 'flaticon' ) ? 'flaticon '.$flat_icon : $fontawesome_icon;
    $count = intval($counter_number);

    $output  = '<div class="animated-counter '.esc_attr( $extra_class ).'">';
        $output .= '<div class="count-box" data-count="'.esc_html( $count ).'">';
            $output .= '<div class="icon"><i class="'.esc_html( $icon ).'"></i></div>';
            $output .= '<span class="timer"></span>';
            $output .= '<h4>'.esc_html( $title_text ).'</h4>';
        $output .= '</div>';
    $output .= '</div>';
    return $output;
} );

add_action( 'vc_before_init', 'builder_animated_counter' );
function builder_animated_counter() {

    vc_map( array(
        "name" => __( "Animaed Counter", "builder-core" ),
        "base" => "builder_animated_counter",
        "class" => "",
        "category" => __( "Builder", "builder-core" ),
        "icon" => plugins_url().'/builder-core/assets/images/itl.png',
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => __( "Counter Number", "builder-core" ),
                "param_name" => "counter_number",
                "description" => __( "Enter number here", "builder-core" ),
                "group" => "General",
            ),
            array(
                "type" => "textfield",
                "heading" => __( "Title Text", "builder-core" ),
                "param_name" => "title_text",
                "description" => __( "Enter your counter title here", "builder-core" ),
                "admin_label" => true,
                "group" => "General",
            ),
            array(
                "type" => "dropdown",
                "param_name" => "icon_type",
                "value" => array( 'FontAwesome' => 'fontawesome', 'FlatIcon' => 'flaticon'),
                "heading" => __( "Icon Type", "builder-core" ),
                "group" => "General",
            ),
            array(
                "type" => "iconpicker",
                "heading" => __( "Feature Icon", "builder-core" ),
                "param_name" => "fontawesome_icon",
                "description" => __( "Some description", "builder-core" ),
                "dependency" => array(
                    'element' => 'icon_type',
                    'value' => 'fontawesome'
                ),
                "group" => "General",
            ),
            array(
                "type" => "textfield",
                "heading" => __( "Icon Name", "builder-core" ),
                "param_name" => "flat_icon",
                "description" => __( "Enter your feature box title here", "builder-core" ),
                "dependency"    => array(
                    'element'   => 'icon_type',
                    'value'     => 'flaticon'
                ),
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
