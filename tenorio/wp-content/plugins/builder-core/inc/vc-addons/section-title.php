<?php
add_shortcode( 'builder_section_title', function( $atts, $content = null ) {
    extract( shortcode_atts( array(
        'title'			            => '',
        'colored_title'			    => '',
        'heading_tag'			    => 'h3',
        'text_alignment'			=> '',
        'extra_class'               => '',
    ), $atts) );

    $output  = '<div class="section-title '.esc_attr( $extra_class ).'">';
    $output .= '<div class="'.esc_attr( $text_alignment ).'">';
    $output .= '<'.esc_attr( $heading_tag ).' class="section-title-text">'.esc_html( $title ).' <span>'.$colored_title.'</span></'.esc_attr( $heading_tag ).'>';
    $output .= '</div>';
    $output .= '</div>';
    return $output;
} );

add_action( 'vc_before_init', 'builder_section_title' );
function builder_section_title() {

    vc_map( array(
        "name" => __( "Section Title", "builder-core" ),
        "base" => "builder_section_title",
        "class" => "",
        "category" => __( "Builder", "builder-core" ),
        "icon" => plugins_url().'/builder-core/assets/images/itl.png',
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => __( "Title Text", "builder-core" ),
                "param_name" => "title",
                "description" => __( "Enter your section title here", "builder-core" ),
                "admin_label" => true,
                "group" => "General",
            ),
            array(
                "type" => "textfield",
                "heading" => __( "Title Text Last Part", "builder-core" ),
                "param_name" => "colored_title",
                "description" => __( "This text will add into section title with a span tag", "builder-core" ),
                "admin_label" => true,
                "group" => "General",
            ),
            array(
                "type"          => "dropdown",
                "param_name"    => "heading_tag",
                "value"         => array(
                    'H1' => 'h1',
                    'H2' => 'h2',
                    'H3' => 'h3',
                    'H4' => 'h4',
                    'H5' => 'h5',
                    'H6' => 'h6',
                ),
                "heading"       => __( "Heading Tag", "builder-core" ),
                "description"   => __( "Select a heading tag from H1 to H6", "builder-core" ),
                "std"           => "h3",
                "group"         => "General",
            ),
            array(
                "type" => "dropdown",
                "param_name" => "text_alignment",
                "value" => array( 'Left' => 'text-left', 'Center' => 'text-center', 'Right' => 'text-right' ),
                "heading" => __("Text Alignment", "builder-core"),
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
