<?php
add_shortcode( 'builder_testimonial', function( $atts, $content = null ) {
    extract( shortcode_atts( array(
        'testimonial_count'			=> '3',
        'show_image'			    => '0',
        'content_length'			=> '32',
        'servicebox_alignment'		=> '',
        'link_icon'		            => '',
        'extra_class'               => '',
    ), $atts) );

    //variables start
    $content_length = isset( $content_length ) ? intval( $content_length ) : 32;
    //variables end

    // wp_query and its argument start
    $args = array(
        'post_type' => 'testimonial',
        'posts_per_page' => $testimonial_count,
    );
    $testimonial_query = new WP_Query( $args );
    // wp_query and its argument end
    //display output start
    $output  = '<div class="testimonial-carousel '.esc_attr( $extra_class ).'">';
    if( $testimonial_query->have_posts() ) :
        while ( $testimonial_query->have_posts() ) : $testimonial_query->the_post();
            $designation = get_post_meta( get_the_ID(), 'designation', true );

            $output .= '<div class="feedback-item">';
                if($show_image == 1 ) {
                    $output .= '<div class="feedback-img"><img src="' . esc_url(get_the_post_thumbnail_url(null, 'thumbnail')) . '" alt=""></div>';
                }
                $output .= '<div class="feedback-text">'.wp_trim_words( get_the_content(), $content_length, '' ).'</div>';
                $output .= '<h4>'.get_the_title().'</h4>';
                $output .= '<p class="designation">'.esc_html( $designation ).'</p>';
            $output .= '</div>';
            endwhile;

        endif;
    $output .= '</div>';
    //display output end

    $output .= '<script type="text/javascript">
    jQuery(document).ready(function($){
        $( ".testimonial-carousel" ).owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            smartSpeed: 1000,
        });
    });</script>';
    return $output;

} );

add_action( 'vc_before_init', 'builder_testimonial' );
function builder_testimonial() {

    vc_map( array(
        "name" => __( "Testimonial", "builder-core" ),
        "base" => "builder_testimonial",
        "class" => "",
        "category" => __( "Builder", "builder-core" ),
        "icon" => plugins_url().'/builder-core/assets/images/itl.png',
        "params" => array(
            array(
                "type"          => "dropdown",
                "param_name"    => "testimonial_count",
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
                "heading"       => __( "Testimonial Count Count", "builder-core" ),
                "description"   => __( "Select how many items you want to show", "builder-core" ),
                "std"           => "3",
                "group"         => "General",
            ),
            array(
                "type" => "textfield",
                "heading" => __( "Content Length", "builder-core" ),
                "param_name" => "content_length",
                "group" => "General",
            ),
            array(
                "type"          => "dropdown",
                "param_name"    => "show_image",
                "value"         => array(
                    'Yes' => '1',
                    'No' => '0',
                ),
                "heading"       => __( "Show Image", "builder-core" ),
                "std"           => "0",
                "group"         => "General",
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
