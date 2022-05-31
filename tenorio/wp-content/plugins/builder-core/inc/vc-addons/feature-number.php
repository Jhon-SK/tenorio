<?php
add_shortcode( 'builder_feature_number', function( $atts, $content = null ) {
    extract( shortcode_atts( array(
        'title_text'			            => '',
        'feature_text'			    => '',
        'heading_tag'			    => 'h3',
        'feature_number'            => '1',
        'feature_style'			    => 'left',
        'extra_class'               => '',
    ), $atts) );

    $output  = '<div class="feature-number '.esc_attr( $feature_style ) .' '. esc_attr( $extra_class ) .'">';
        if($feature_style == 'left') {
            $output .= '<div class="number pull-left">' . esc_html( $feature_number ) . '</div>';
            $output .= '<div class="number-content">';
            $output .= '<' . esc_attr( $heading_tag ) . ' class="featurenumber-title-text">' . esc_html( $title_text ) . '</' . esc_attr( $heading_tag ) . '>';
            $output .= '<p>' . esc_html( $feature_text ) . '</p>';
            $output .= '</div>';
        };
    if($feature_style == 'right') {
        $output .= '<div class="number pull-right">' . esc_html( $feature_number ) . '</div>';
        $output .= '<div class="number-content">';
        $output .= '<' . $heading_tag . ' class="featurenumber-title-text">' . esc_html( $title_text ) . '</' . $heading_tag . '>';
        $output .= '<p>' . esc_html( $feature_text ) . '</p>';
        $output .= '</div>';
    };
    $output .= '</div>';
    return $output;
} );

add_action( 'vc_before_init', 'builder_feature_number' );
function builder_feature_number() {

    vc_map( array(
        "name" => __( "Feature Number", "builder-core" ),
        "base" => "builder_feature_number",
        "class" => "",
        "category" => __( "Builder", "builder-core" ),
        "icon" => plugins_url().'/builder-core/assets/images/itl.png',
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => __( "Feature Number", "builder-core" ),
                "param_name" => "feature_number",
                "description" => __( "Enter your feature box title here", "builder-core" ),
                "group" => "General",
            ),
            array(
                "type" => "textfield",
                "heading" => __( "Title Text", "builder-core" ),
                "param_name" => "title_text",
                "description" => __( "Enter your feature box title here", "builder-core" ),
                "admin_label" => true,
                "group" => "General",
            ),
            array(
                "type" => "textarea",
                "heading" => __( "Feature Text", "builder-core" ),
                "param_name" => "feature_text",
                "description" => __( "Insert feature box text", "builder-core" ),
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
                "param_name" => "feature_style",
                "value" => array( 'Left' => 'left', 'Right' => 'right' ),
                "heading" => __("Feature Style", "builder-core"),
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
