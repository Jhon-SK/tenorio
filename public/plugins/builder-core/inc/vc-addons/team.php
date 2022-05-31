<?php
add_shortcode( 'builder_team', function( $atts, $content = null ) {
    extract( shortcode_atts( array(
        'name'			            => '',
        'team_image'			    => '',
        'designation'			    => '',
        'description'			    => '',
        'social_icon'			    => 'fa fa-facebook',
        'social_link'               => '',
        'team_social'               => '',
        'extra_class'               => '',
    ), $atts) );

    $team_img = wp_get_attachment_image_src( $team_image, 'full' )[0];

    $socials = vc_param_group_parse_atts( $atts['team_social']);
    $is_array = is_array($socials) ? true:false;

    $output  = '<div class="team-wrap '.esc_attr( $extra_class ).'">';
        $output .= '<div class="team-img">';
            $output .= '<img src="'.esc_url( $team_img ).'" alt="'.esc_html( $name ).'" class="img-responsive">';
            $output .= '<div class="overley">';
                if(!empty($socials) && $is_array) :
                $output .= '<div class="team-social">';
                    foreach ($socials as $social) :
                    $output .= '<a href="'.$social["social_link"].'" target="_blank"><i class="'.$social["social_icon"].'"></i></a>';
                    endforeach;
                $output .= '</div>';
                endif;
            $output .= '</div>';
        $output .= '</div>';
        $output .= '<div class="team-content">';
            $output .= '<h4>'.esc_html( $name ).'</h4>';
            $output .= '<p class="designation">'.esc_html( $designation ).'</p>';
            $output .= '<p>'.esc_html( $description ).'</p>';
        $output .= '</div>';
    $output .= '</div>';

    return $output;
} );

add_action( 'vc_before_init', 'builder_team' );
function builder_team() {

    vc_map( array(
        "name" => __( "Team", "builder-core" ),
        "base" => "builder_team",
        "class" => "",
        "category" => __( "Builder", "builder-core" ),
        "icon" => plugins_url().'/builder-core/assets/images/itl.png',
        "params" => array(
            array(
                "type" => "attach_image",
                "heading" => __( "Team Image", "builder-core" ),
                "param_name" => "team_image",
                "group" => "General",
            ),
            array(
                "type" => "textfield",
                "heading" => __( "Name", "builder-core" ),
                "param_name" => "name",
                "description" => __( "Enter name here", "builder-core" ),
                "admin_label" => true,
                "group" => "General",
            ),
            array(
                "type" => "textfield",
                "heading" => __( "Designation", "builder-core" ),
                "param_name" => "designation",
                "description" => __( "Enter designation here", "builder-core" ),
                "group" => "General",
            ),
            array(
                "type" => "textarea",
                "heading" => __( "Description", "builder-core" ),
                "param_name" => "description",
                "description" => __( "Enter team description here", "builder-core" ),
                "group" => "General",
            ),
            array(
                "type" => "param_group",
                "value" => "",
                "heading" => __("Team Social", 'builder-core'),
                "param_name" => "team_social",
                "group" => "General",
                "params" => array(
                    array(
                        "type" => "iconpicker",
                        "heading" => __( "Select an icon", "builder-core" ),
                        "param_name" => "social_icon",
                        "value" => "fa fa-facebook",
                        "settings" => array(
                            "emptyIcon" => false,
                        ),
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __( "Social Link", "builder-core" ),
                        "param_name" => "social_link",
                        "value" => "http://facebook.com/username/",
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
