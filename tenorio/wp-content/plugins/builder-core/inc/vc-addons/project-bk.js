<?php
function builder_isotope_scripts() {
    wp_enqueue_script( 'isotope-script', plugins_url() . '/builder-core/assets/js/isotope-scripts.js', array( 'isotope' ), false, true );
}
add_action( 'wp_enqueue_scripts', 'builder_isotope_scripts' );

add_shortcode( 'builder_project', function( $atts, $content = null ) {
    extract( shortcode_atts( array(
        'item_count'			    => '8',
        'column'			        => '4',
        'extra_class'               => '',
    ), $atts) );

    $item_count = isset( $item_count ) ? intval( $item_count ) : 8;
    $column = isset( $column ) ? intval( $column ) : 4;

    global $post;

    // get prject type for filter start
    $terms = get_terms( 'project_type' );
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
    $output  = '<div class="container">';
        $output .= '<div class="row">';
            $output .= '<div class="col-xs-12">';
                $output .= '<div class="project-nav">';
                    $output .= '<ul>';
                    $output .= '<li class="active" data-filter="*">'.esc_html__( "Show All", "builder-core" ).'</li>';
                    foreach ($terms as $term) :
                    $output .= '<li data-filter=".'.$term->slug.'">'.$term->name.'</li>';
                        endforeach;
                    $output .= '</ul>';
                $output .= '</div>'; //.project-nav
            $output .= '</div>'; //.col
        $output .= '</div>'; //.row
    $output .= '</div>'; //.continer
    endif;
    // get prject type for filter end
    
    // wp_query and its argument start
    $args = array(
        'post_type' => 'project',
        'posts_per_page' => $item_count,
    );
    $posts = get_posts( $args );

    $output .= '<div class="project-addon">';
        foreach ($posts as $post) :
            setup_postdata( $post );
            $filters = get_the_terms( $post->ID, 'project_type' );
            $filters_name = '';
            if ($filters) {
                foreach ( $filters as $filter ) {
                    $filters_name .= ' '.$filter->slug;
                }
            }
            $output .= '<div class="project-item col-'.esc_attr( $column ).' '.esc_html( $filters_name ).'">';
                if(has_post_thumbnail($post->ID)) {
                    $output .= '<img src="'.get_the_post_thumbnail_url( $post->ID, "builder_standard_project_thumb" ).'" alt="'.get_the_title().'">';
                }
                $output .= '<div class="project-overley">';
                    $output .= '<div class="content">';
                    $output .= '<center><h3><a href="'.get_the_permalink( $post->ID ).'">'.get_the_title( $post->ID ).'</a></h3></center>';
                    $output .= '<div class="project-tags">';
                    foreach ( $filters as $filter ) :
                    $output .= '<span>'.$filter->name.'</span>';
                    endforeach;
                    $output .= '</div>';
                    $output .= '</div>'; //.content
                $output .= '</div>'; //.portfolio-overley
            $output .= '</div>'; //.project-item
        endforeach;
    $output .= '</div>'; //.project

    return $output;

} );

add_action( 'vc_before_init', 'builder_project' );
function builder_project() {

    vc_map( array(
        "name" => __( "Project", "builder-core" ),
        "base" => "builder_project",
        "class" => "",
        "category" => __( "Builder", "builder-core" ),
        "icon" => plugins_url().'/builder-core/assets/images/itl.png',
        "params" => array(
            array(
                "type"          => "dropdown",
                "param_name"    => "item_count",
                "value"         => array(
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12',
                ),
                "heading"       => esc_html__( "Item Count", "builder-core" ),
                "std"           => "8",
                "group"         => "General",
            ),
            array(
                "type"          => "dropdown",
                "param_name"    => "column",
                "value"         => array(
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ),
                "heading"       => esc_html__( "Column", "builder-core" ),
                "std"           => "4",
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
