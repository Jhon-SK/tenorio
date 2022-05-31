<?php
add_shortcode( 'builder_buttons', function( $atts, $content = null ) {
    extract( shortcode_atts( array(
        'button_text'			    => esc_html__( "Your button text", "builder-core" ),
        'button_type'			    => 'default',
        'url'			            => '',
        'target'			        => '',
        'extra_class'               => '',
    ), $atts) );

    $output  = '<div class="button-wrap '.esc_attr( $extra_class ).'">';
        $output .= '<a href="'.esc_url( $url ).'" class="btn btn-'.esc_attr( $button_type ).' hvr-sweep-to-right" target="'.esc_attr( $target ).'">'.esc_html( $button_text ).'</a>';
    $output .= '</div>';
    return $output;
} );

add_action( 'vc_before_init', 'builder_buttons' );
function builder_buttons() {

    vc_map( array(
        "name" => __( "Buttons", "builder-core" ),
        "base" => "builder_buttons",
        "class" => "",
        "category" => __( "Builder", "builder-core" ),
        "icon" => plugins_url().'/builder-core/assets/images/itl.png',
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => __( "Button Text", "builder-core" ),
                "param_name" => "button_text",
                "std" => __( "Your button text", "builder-core" ),
                "admin_label" => true,
                "group" => "General",
            ),
            array(
                "type" => "textfield",
                "heading" => __( "URL", "builder-core" ),
                "param_name" => "url",
                "std" => '',
                "group" => "General",
            ),
            array(
                "type" => "dropdown",
                "param_name" => "button_type",
                "value" => array(
                    __( "Default", "builder-core" )   => "default",
                    __( "Primary", "builder-core" )   => "primary",
                    __( "Success", "builder-core" )   => "success",
                    __( "Info", "builder-core" )      => "info",
                    __( "Warning", "builder-core" )   => "warning",
                    __( "Danger", "builder-core" )    => "danger",
                    __( "Link", "builder-core" )     => "link",
                ),
                "heading" => __( "Button Type", "builder-core" ),
                "std" => __( "Default", "builder-core" ),
                "group" => "General",
            ),
            array(
                "type" => "dropdown",
                "param_name" => "target",
                "value" => array(
                    __( "Same Window", "builder-core" )   => "_self",
                    __( "New Window", "builder-core" )    => "_blank",
                ),
                "heading" => __( "Target", "builder-core" ),
                "std" => __( "Same Window", "builder-core" ),
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
