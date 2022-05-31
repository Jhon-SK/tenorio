<?php
add_shortcode( 'builder_feature_box', function( $atts, $content = null ) {
    extract( shortcode_atts( array(
        'title_text'			            => '',
        'feature_text'			    => '',
        'heading_tag'			    => 'h3',
        'icon_type'                 => 'fontawesome',
        'fontawesome_icon'          => '',
        'flat_icon'                 => '',
        'feature_alignment'			=> 'text-left',
        'extra_class'               => '',
    ), $atts) );

    $icon = ( $icon_type === 'flaticon' ) ? 'flaticon '.$flat_icon : $fontawesome_icon;

    $output  = '<div class="feature-box '.esc_attr( $extra_class ).'">';
        $output .= '<div class="'.esc_attr( $feature_alignment ).'">';
            $output .= '<div class="featurebox-icon">';
            $output .= '<i class="'.esc_html( $icon ).'"></i>';
            $output .= '</div>';
            $output .= '<'.esc_attr( $heading_tag ).' class="featurebox-title-text">'.esc_html( $title_text ).'</'.esc_attr( $heading_tag ).'>';
            $output .= '<p>'.esc_html( $feature_text ).'</p>';
        $output .= '</div>';
    $output .= '</div>';
    return $output;
} );

add_action( 'vc_before_init', 'builder_feature_box' );
function builder_feature_box() {

    vc_map( array(
        "name" => __( "Feature Box", "builder-core" ),
        "base" => "builder_feature_box",
        "class" => "",
        "category" => __( "Builder", "builder-core" ),
        "icon" => plugins_url().'/builder-core/assets/images/itl.png',
        "params" => array(
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
                "param_name" => "feature_alignment",
                "value" => array( 'Left' => 'text-left', 'Center' => 'text-center', 'Right' => 'text-right' ),
                "heading" => __("Feature box Alignment", "builder-core"),
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
