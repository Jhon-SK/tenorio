<?php
/**
 * Ajax Class.
 *
 * @since 2.5.0
 * @package SoliloquyWP Lite
 * @author SoliloquyWP Team <support@soliloquywp.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Soliloquy Ajax
 *
 * @since 2.5.0
 */
class Soliloquy_Ajax {

	/**
	 * Holds Singleton.
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Hold Soliloquy Class.
	 *
	 * @var Soliloquy_Lite
	 */
	public $base;

	/**
	 * Primary class constructor.
	 *
	 * @since 2.5
	 */
	public function __construct() {

		$this->base = Soliloquy_Lite::get_instance();

		add_action( 'wp_ajax_soliloquy_is_hosted_video', array( $this, 'is_hosted_video' ) );
		add_action( 'wp_ajax_soliloquy_upgrade_sliders', array( $this, 'upgrade_sliders' ) );
		add_action( 'wp_ajax_soliloquy_change_type', array( $this, 'change_type' ) );
		add_action( 'wp_ajax_soliloquy_load_image', array( $this, 'load_image' ) );
		add_action( 'wp_ajax_soliloquy_insert_slides', array( $this, 'insert_slides' ) );
		add_action( 'wp_ajax_soliloquy_sort_images', array( $this, 'sort_images' ) );
		add_action( 'wp_ajax_soliloquy_remove_slides', array( $this, 'remove_slides' ) );
		add_action( 'wp_ajax_soliloquy_remove_slide', array( $this, 'remove_slide' ) );
		add_action( 'wp_ajax_soliloquy_save_meta', array( $this, 'save_meta' ) );
		add_action( 'wp_ajax_soliloquy_bulk_save_meta', array( $this, 'bulk_save_meta' ) );
		add_action( 'wp_ajax_soliloquy_refresh', array( $this, 'refresh' ) );
		add_action( 'wp_ajax_soliloquy_load_slider_data', array( $this, 'load_slider_data' ) );
		add_action( 'wp_ajax_soliloquy_install_addon', array( $this, 'install_addon' ) );
		add_action( 'wp_ajax_soliloquy_activate_addon', array( $this, 'activate_addon' ) );
		add_action( 'wp_ajax_soliloquy_deactivate_addon', array( $this, 'deactivate_addon' ) );
		add_action( 'wp_ajax_soliloquy_init_sliders', array( $this, 'init_sliders' ) );
		add_action( 'wp_ajax_nopriv_soliloquy_init_sliders', array( $this, 'init_sliders' ) );
		add_action( 'wp_ajax_soliloquy_sort_addons', array( $this, 'sort_addons' ) );
		add_action( 'wp_ajax_soliloquy_change_slide_status', array( $this, 'change_slide_status' ) );
		add_action( 'wp_ajax_soliloquy_slider_view', array( $this, 'slider_view' ) );
		add_action( 'wp_ajax_soliloquy_get_attachment_links', array( $this, 'get_attachment_links' ) );
		add_action( 'wp_ajax_soliloquy_activate_partner', array( $this, 'activate_partner' ) );
		add_action( 'wp_ajax_soliloquy_deactivate_partner', array( $this, 'deactivate_partner' ) );
		add_action( 'wp_ajax_soliloquy_install_partner', array( $this, 'install_partner' ) );

	}

	/**
	 * Returns the media link (direct image URL) for the given attachment ID
	 *
	 * @since
	 */
	function get_attachment_links() {

		// Check nonce
		check_admin_referer( 'soliloquy-save-meta', 'nonce' );

		// Get required inputs
		$attachment_id = absint( $_POST['attachment_id'] );

		// Return the attachment's links
		wp_send_json_success(
			array(
				'media_link'      => wp_get_attachment_url( $attachment_id ),
				'attachment_page' => get_attachment_link( $attachment_id ),
			)
		);

	}
	/**
	 * Upgrades sliders from v1 to v2. This also upgrades any current v2 users to the
	 * proper post type. This is a mess and it was my fault. :-( I apologize to my customers
	 * for making this so rough. You deserve better, and I will work hard to do better by
	 * you! Thanks for hanging in there faithfully with me!
	 *
	 * @since 1.0.0
	 */
	public function upgrade_sliders() {

		// Run a security check first.
		check_ajax_referer( 'soliloquy-upgrade', 'nonce' );

		// Increase the time limit to account for large slider sets and suspend cache invalidations.
		set_time_limit( Soliloquy_Common_Lite::get_instance()->get_max_execution_time() );
		wp_suspend_cache_invalidation( true );

		// Grab all sliders and convert them to the new system.
		$sliders = get_posts(
			array(
				'post_type'      => 'soliloquy',
				'posts_per_page' => -1,
			)
		);

		// Loop through sliders and convert them.
		foreach ( (array) $sliders as $slider ) {
			// Grab meta from the v1 sliders.
			$meta = get_post_meta( $slider->ID, '_soliloquy_settings', true );

			// Move meta from v1 to v2.
			$new_meta = array(
				'id'     => $slider->ID,
				'config' => array(),
				'slider' => array(),
				'status' => 'active',
			);

			if ( ! empty( $new_meta['config']['gutter'] ) ) {
				$new_meta['config']['gutter'] = 0;
			}

			if ( ! empty( $new_meta['config']['position'] ) ) {
				$new_meta['config']['position'] = 'none';
			}

			if ( ! empty( $new_meta['config']['mobile'] ) ) {
				$new_meta['config']['mobile'] = 0;
			}

			// Splice meta from v1 to v2.
			$new_meta['config']['title']  = $slider->post_title;
			$new_meta['config']['slug']   = $slider->post_name;
			$new_meta['config']['slider'] = 0;

			if ( ! empty( $meta['width'] ) ) {
				$new_meta['config']['slider_width'] = $meta['width'];
			}

			if ( ! empty( $meta['height'] ) ) {
				$new_meta['config']['slider_height'] = $meta['height'];
			}

			if ( ! empty( $meta['transition'] ) ) {
				$new_meta['config']['transition'] = $meta['transition'];
			}

			if ( ! empty( $meta['speed'] ) ) {
				$new_meta['config']['duration'] = $meta['speed'];
			}

			if ( ! empty( $meta['duration'] ) ) {
				$new_meta['config']['speed'] = $meta['duration'];
			}

			// Set to the classic theme to keep people from going nuts with a theme change.
			$new_meta['config']['slider_theme'] = 'classic';

			// Grab all attachments and add them to the slider.
			$attachments = get_posts(
				array(
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
					'post_type'      => 'attachment',
					'post_parent'    => $slider->ID,
					'post_status'    => null,
					'posts_per_page' => -1,
				)
			);

			// Loop through attachments and add them to the slider.
			foreach ( (array) $attachments as $slide ) {
				$url                              = wp_get_attachment_image_src( $slide->ID, 'full' );
				$alt_text                         = get_post_meta( $slide->ID, '_wp_attachment_image_alt', true );
				$new_meta['slider'][ $slide->ID ] = array(
					'status'  => 'active',
					'id'      => $slide->ID,
					'src'     => isset( $url[0] ) ? esc_url( $url[0] ) : '',
					'title'   => get_the_title( $slide->ID ),
					'link'    => get_post_meta( $slide->ID, '_soliloquy_image_link', true ),
					'alt'     => ! empty( $alt_text ) ? $alt_text : get_the_title( $slide->ID ),
					'caption' => ! empty( $slide->post_excerpt ) ? $slide->post_excerpt : '',
					'type'    => 'image',
				);
			}

			// Update the post meta for the new slider.
			update_post_meta( $slider->ID, '_sol_slider_data', $new_meta );

			// Force the post to update.
			wp_update_post( array( 'ID' => $slider->ID ) );

			// Flush caches for any sliders.
			Soliloquy_Common_Lite::get_instance()->flush_slider_caches( $slider->ID, $new_meta['config']['slug'] );
		}

		// Turn off cache suspension and flush the cache to remove any cache inconsistencies.
		wp_suspend_cache_invalidation( false );
		wp_cache_flush();

		// Update the option to signify that upgrading is complete.
		update_option( 'soliloquy_upgrade', true );

		// Send back the response.
		echo wp_json_encode( true );
		die;

	}

	/**
	 * Called by the media view when the video URL input is changed
	 * Checks if the supplied video URL is a locally hosted video URL or not
	 *
	 * @since 1.1.1
	 *
	 * @return void
	 */
	public function is_hosted_video() {

		// Run a security check first.
		check_ajax_referer( 'soliloquy-is-hosted', 'nonce' );

		// Setup vars.
		$video_url = ( isset( $_POST['video_url'] ) ? sanitize_text_field( wp_unslash( $_POST['video_url'] ) ) : '' );

		// Check a URL was defined.
		if ( empty( $video_url ) ) {
			wp_send_json_error( __( 'No video URL was defined', 'envira-videos' ) );
			die();
		}

		// Get video type.
		$video_type = Soliloquy_Common_Lite::get_instance()->get_video_type( $video_url, array(), array(), true );

		// Depending on the video type, return true or false to determine whether it's a self hosted video.
		$is_hosted_video = false;
		switch ( $video_type ) {
			case 'youtube':
			case 'vimeo':
			case 'wistia':
				$is_hosted_video = false;
				break;

			case 'mp4':
			case 'flv':
			case 'ogv':
			case 'webm':
				$is_hosted_video = true;
				break;

			default:
				// Allow addons to define whether the video type is hosted or third party.
				$is_hosted_video = apply_filters( 'soliloquy_is_hosted_video', $is_hosted_video, $video_type );
				break;
		}

		// Return.
		wp_send_json_success( $is_hosted_video );

	}

	/**
	 * Changes the type of slider to the user selection.
	 *
	 * @since 1.0.0
	 */
	public function change_type() {

		// Run a security check first.
		check_ajax_referer( 'soliloquy-change-type', 'nonce' );

		// Prepare variables.
		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : '';
		$post    = get_post( $post_id );
		$type    = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';

		// Retrieve the data for the type selected.
		ob_start();
		$instance = Soliloquy_Metaboxes_Lite::get_instance();
		$instance->images_display( $type, $post );
		$html = ob_get_clean();

		// Send back the response.
		echo wp_json_encode(
			array(
				'type' => $type,
				'html' => $html,
			)
		);
		die;

	}

	/**
	 * Loads an image into a slider.
	 *
	 * @since 1.0.0
	 */
	public function load_image() {

		if ( !current_user_can( 'upload_files' ) ) {
			die();
		}

		// Run a security check first.
		check_ajax_referer( 'soliloquy-load-image', 'nonce' );

		// Prepare variables.
		$id      = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : '';
		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : '';

		// Set post meta to show that this image is attached to one or more Soliloquy sliders.
		$has_slider = get_post_meta( $id, '_sol_has_slider', true );
		if ( empty( $has_slider ) ) {
			$has_slider = array();
		}

		$has_slider[] = $post_id;
		update_post_meta( $id, '_sol_has_slider', $has_slider );

		// Set post meta to show that this image is attached to a slider on this page.
		$in_slider = get_post_meta( $post_id, '_sol_in_slider', true );
		if ( empty( $in_slider ) ) {
			$in_slider = array();
		}

		$in_slider[] = $id;
		update_post_meta( $post_id, '_sol_in_slider', $in_slider );

		// Set data and order of image in slider.
		$slider_data = get_post_meta( $post_id, '_sol_slider_data', true );
		if ( empty( $slider_data ) ) {
			$slider_data = array();
		}

		// If no slider ID has been set, set it now.
		if ( empty( $slider_data['id'] ) ) {
			$slider_data['id'] = $post_id;
		}

		// Set data and update the meta information.
		$slider_data = $this->prepare_slider_data( $slider_data, $id );
		update_post_meta( $post_id, '_sol_slider_data', $slider_data );

		// Run hook before building out the item.
		do_action( 'soliloquy_ajax_load_image', $id, $post_id );

		// Build out the individual HTML output for the slider image that has just been uploaded.
		$html = Soliloquy_Metaboxes_Lite::get_instance()->get_slider_item( $id, $slider_data['slider'][ $id ], 'image', $post_id );

		// Flush the slider cache.
		Soliloquy_Common_Lite::get_instance()->flush_slider_caches( $post_id );

		echo wp_json_encode( $html );
		die;

	}

	/**
	 * Inserts one or more slides into a slider.
	 *
	 * @since 1.0.0
	 */
	public function insert_slides() {

		if ( !current_user_can( 'upload_files' ) ) {
			die();
		}
		// Run a security check first.
		check_ajax_referer( 'soliloquy-insert-images', 'nonce' );

		// Prepare variables.
		$images  = isset( $_POST['images'] ) ? json_decode( wp_unslash( $_POST['images'] ), true ) : array();
		$videos  = isset( $_POST['videos'] ) ? wp_unslash( (array) $_POST['videos'] ) : array();
		$html    = isset( $_POST['html'] ) ? wp_unslash( (array) $_POST['html'] ) : array();
		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : false;

		// Grab and update any slider data if necessary.
		$in_slider = get_post_meta( $post_id, '_sol_in_slider', true );
		if ( empty( $in_slider ) ) {
			$in_slider = array();
		}

		// Set data and order of image in slider.
		$slider_data = get_post_meta( $post_id, '_sol_slider_data', true );
		if ( empty( $slider_data ) ) {
			$slider_data = array();
		}

		// If no slider ID has been set, set it now.
		if ( empty( $slider_data['id'] ) ) {
			$slider_data['id'] = $post_id;
		}

		// Loop through the images and add them to the slider.
		foreach ( (array) $images as $i => $id ) {

			// Update the attachment image post meta first.
			$has_slider = get_post_meta( $id, '_sol_has_slider', true );
			if ( empty( $has_slider ) ) {
				$has_slider = array();
			}

			$has_slider[] = $post_id;
			update_post_meta( $id, '_sol_has_slider', $has_slider );

			// Now add the image to the slider for this particular post.
			$in_slider[] = $id;
			$slider_data = $this->prepare_slider_data( $slider_data, $id['id'] );
		}

		// Loop through the videos and add them to the slider.
		foreach ( (array) $videos as $i => $data ) {

			// Pass over if the main items necessary for the video are not set.
			if ( ! isset( $data['title'] ) || ! isset( $data['url'] ) ) {
				continue;
			}

			// Generate a custom ID for the video.
			// Note: we don't use sanitize_title_with_dashes as this retains special accented characters, resulting in jQuery errors
			// when subsequently trying to edit an exitsing slide.
			$id = $slider_data['id'] . '-' . preg_replace( '/[^A-Za-z0-9]/', '', strtolower( $data['title'] ) );

			// Now add the image to the slider for this particular post.
			$in_slider[] = $id;
			$slider_data = $this->prepare_slider_data( $slider_data, $id, 'video', $data );

		}

		// Loop through the HTML and add them to the slider.
		foreach ( (array) $html as $i => $data ) {
			// Pass over if the main items necessary for the video are not set.
			if ( empty( $data['title'] ) || empty( $data['code'] ) ) {
				continue;
			}

			// Generate a custom ID for the video.
			$id = $slider_data['id'] . '-' . preg_replace( '/[^A-Za-z0-9]/', '', strtolower( $data['title'] ) );

			// Now add the image to the slider for this particular post.
			$in_slider[] = $id;
			$slider_data = $this->prepare_slider_data( $slider_data, $id, 'html', $data );
		}

		// Update the slider data.
		update_post_meta( $post_id, '_sol_in_slider', $in_slider );
		update_post_meta( $post_id, '_sol_slider_data', $slider_data );

		// Run hook before finishing.
		do_action( 'soliloquy_ajax_insert_slides', $images, $videos, $html, $post_id );

		// Flush the slider cache.
		Soliloquy_Common_Lite::get_instance()->flush_slider_caches( $post_id );

		// Return a HTML string comprising of all gallery images, so the UI can be updated.
		$html = '';
		foreach ( (array) $slider_data['slider'] as $id => $data ) {
			$html .= Soliloquy_Metaboxes_Lite::get_instance()->get_slider_item( $id, $data, ( ! empty( $data['type'] ) ? $data['type'] : 'image' ), $post_id );
		}

		wp_send_json_success( $html );

	}

	/**
	 * Sorts images based on user-dragged position in the slider.
	 *
	 * @since 1.0.0
	 */
	public function sort_images() {

		// Run a security check first.
		check_ajax_referer( 'soliloquy-sort', 'nonce' );

		// Prepare variables.
		$order       = isset( $_POST['order'] ) ? explode( ',', wp_unslash( $_POST['order'] ) ) : array();
		$post_id     = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : false;
		$slider_data = get_post_meta( $post_id, '_sol_slider_data', true );

		// Copy the slider config, removing the slides.
		$new_order = $slider_data;
		unset( $new_order['slider'] );
		$new_order['slider'] = array();

		// Loop through the order and generate a new array based on order received.
		foreach ( $order as $id ) {
			$new_order['slider'][ $id ] = $slider_data['slider'][ $id ];
		}

		// Update the slider data.
		update_post_meta( $post_id, '_sol_slider_data', $new_order );

		// Flush the slider cache.
		Soliloquy_Common_Lite::get_instance()->flush_slider_caches( $post_id );

		echo wp_json_encode( true );
		die;

	}

	/**
	 * Removes multiple images from a slider
	 *
	 * @since 2.5
	 */
	public function remove_slides() {

		// Run a security check first.
		check_ajax_referer( 'soliloquy-remove-slide', 'nonce' );

		// Prepare variables.
		$post_id     = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : false;
		$attach_ids  = isset( $_POST['attachment_ids'] ) ? (array) wp_unslash( $_POST['attachment_ids'] ) : array();
		$slider_data = get_post_meta( $post_id, '_sol_slider_data', true );
		$in_slider   = get_post_meta( $post_id, '_sol_in_slider', true );

		foreach ( (array) $attach_ids as $attach_id ) {
			$has_slider = get_post_meta( $attach_id, '_sol_has_slider', true );

			// Unset the image from the slider, in_slider and has_slider checkers.
			unset( $slider_data['slider'][ $attach_id ] );

			$key = array_search( $attach_id, (array) $in_slider, true );

			if ( false !== $key ) {
				unset( $in_slider[ $key ] );
			}

			$key = array_search( $post_id, (array) $has_slider, true );

			if ( false !== $key ) {
				unset( $has_slider[ $key ] );
			}

			// Update the attachment data.
			update_post_meta( $attach_id, '_sol_has_slider', $has_slider );
		}

		// Update the gallery data.
		update_post_meta( $post_id, '_sol_slider_data', $slider_data );
		update_post_meta( $post_id, '_sol_in_slider', $in_slider );

		// Run hook before finishing the reponse.
		do_action( 'soliloquy_ajax_remove_slides', $attach_id, $post_id );

		// Flush the gallery cache.
		Soliloquy_Common_Lite::get_instance()->flush_slider_caches( $post_id );

		echo wp_json_encode( true );
		die;

	}

	/**
	 * Removes an image from a slider.
	 *
	 * @since 1.0.0
	 */
	public function remove_slide() {

		// Run a security check first.
		check_ajax_referer( 'soliloquy-remove-slide', 'nonce' );

		// Prepare variables.
		$post_id     = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : false;
		$attach_id   = isset( $_POST['attachment_id'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['attachment_id'] ) ) ) : false;
		$slider_data = get_post_meta( $post_id, '_sol_slider_data', true );
		$in_slider   = get_post_meta( $post_id, '_sol_in_slider', true );
		$has_slider  = get_post_meta( $attach_id, '_sol_has_slider', true );

		// Unset the image from the slider, in_slider and has_slider checkers.
		unset( $slider_data['slider'][ $attach_id ] );
		$key = array_search( $attach_id, (array) $in_slider, true );
		if ( false !== $key ) {
			unset( $in_slider[ $key ] );
		}

		$key = array_search( $post_id, (array) $has_slider, true );

		if ( false !== $key ) {
			unset( $has_slider[ $key ] );
		}

		// Update the slider data.
		update_post_meta( $post_id, '_sol_slider_data', $slider_data );
		update_post_meta( $post_id, '_sol_in_slider', $in_slider );
		update_post_meta( $attach_id, '_sol_has_slider', $has_slider );

		// Run hook before finishing the reponse.
		do_action( 'soliloquy_ajax_remove_slide', $attach_id, $post_id );

		// Flush the slider cache.
		Soliloquy_Common_Lite::get_instance()->flush_slider_caches( $post_id );

		echo wp_json_encode( true );
		die;

	}

	/**
	 * Saves the metadata for an image in a slider.
	 *
	 * @since 1.0.0
	 */
	public function save_meta() {

		// Run a security check first.
		check_ajax_referer( 'soliloquy-save-meta', 'nonce' );

		// Prepare variables.
		$post_id     = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : false;
		$attach_id   = isset( $_POST['attach_id'] ) ? intval( $_POST['attach_id'] ) : false;
		$meta        = isset( $_POST['meta'] ) ? wp_unslash( $_POST['meta'] ) : false;
		$slider_data = get_post_meta( $post_id, '_sol_slider_data', true );

		// Go ahead and ensure to store the attachment ID.
		$slider_data['slider'][ $attach_id ]['id'] = $attach_id;

		// Save the different types of default meta fields for images, videos and HTML slides.
		if ( isset( $meta['status'] ) ) {
			$slider_data['slider'][ $attach_id ]['status'] = trim( esc_html( $meta['status'] ) );
		}

		if ( isset( $meta['title'] ) ) {
			$slider_data['slider'][ $attach_id ]['title'] = trim( esc_html( $meta['title'] ) );
		}

		if ( isset( $meta['alt'] ) ) {
			$slider_data['slider'][ $attach_id ]['alt'] = trim( esc_html( $meta['alt'] ) );
		}

		if ( isset( $meta['link'] ) ) {
			$slider_data['slider'][ $attach_id ]['link'] = esc_url( $meta['link'] );
		}

		if ( isset( $meta['linktab'] ) && $meta['linktab'] ) {
			$slider_data['slider'][ $attach_id ]['linktab'] = 1;
		} else {
			$slider_data['slider'][ $attach_id ]['linktab'] = 0;
		}

		if ( isset( $meta['caption'] ) ) {
			$slider_data['slider'][ $attach_id ]['caption'] = trim( $meta['caption'] );
		}

		if ( isset( $meta['url'] ) ) {
			$slider_data['slider'][ $attach_id ]['url'] = esc_url( $meta['url'] );
		}

		if ( isset( $meta['src'] ) ) {
			$slider_data['slider'][ $attach_id ]['src'] = esc_url( $meta['src'] );
		}

		if ( isset( $meta['code'] ) ) {
			$slider_data['slider'][ $attach_id ]['code'] = trim( $meta['code'] );
		}

		// Allow filtering of meta before saving.
		$slider_data = apply_filters( 'soliloquy_ajax_save_meta', $slider_data, $meta, $attach_id, $post_id );

		// Update the slider data.
		update_post_meta( $post_id, '_sol_slider_data', $slider_data );

		// Flush the slider cache.
		Soliloquy_Common_Lite::get_instance()->flush_slider_caches( $post_id );

		wp_send_json_success();

	}

	/**
	 * Ajax Method to save bulk meta.
	 *
	 * @return void
	 */
	public function bulk_save_meta() {

		// Run a security check first.
		check_ajax_referer( 'soliloquy-save-meta', 'nonce' );

		// Prepare variables.
		$post_id   = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : false;
		$attach_id = isset( $_POST['image_ids'] ) ? wp_unslash( $_POST['image_ids'] ) : false;
		$meta      = isset( $_POST['meta'] ) ? wp_unslash( $_POST['meta'] ) : false;

		if ( false === $post_id ) {
			wp_send_json_error();
		}

		$slider_data = get_post_meta( $post_id, '_sol_slider_data', true );

		if ( empty( $slider_data ) || ! is_array( $slider_data ) ) {
			wp_send_json_error();
		}

		// Iterate through gallery images, updating the metadata.
		foreach ( $attach_id as $image_id ) {

			// If the image isn't in the gallery, something went wrong - so skip this image.
			if ( ! isset( $slider_data['slider'][ $image_id ] ) ) {
				continue;
			}

			// Go ahead and ensure to store the attachment ID.
			$slider_data['slider'][ $image_id ]['id'] = $image_id;

			if ( isset( $meta['alt'] ) ) {
				$slider_data['slider'][ $image_id ]['alt'] = trim( esc_html( $meta['alt'] ) );
			}

			if ( isset( $meta['status'] ) ) {
				$slider_data['slider'][ $image_id ]['status'] = trim( esc_html( $meta['status'] ) );
			}

			if ( isset( $meta['link'] ) ) {
				$slider_data['slider'][ $image_id ]['link'] = esc_url( $meta['link'] );
			}

			if ( isset( $meta['linktab'] ) && $meta['linktab'] ) {
				$slider_data['slider'][ $image_id ]['linktab'] = 1;
			} else {
				$slider_data['slider'][ $image_id ]['linktab'] = 0;
			}

			if ( isset( $meta['caption_bulk'] ) ) {
				$slider_data['slider'][ $image_id ]['caption'] = trim( $meta['caption_bulk'] );
			}

			if ( isset( $meta['url'] ) ) {
				$slider_data['slider'][ $image_id ]['url'] = esc_url( $meta['url'] );
			}

			if ( isset( $meta['src'] ) ) {
				$slider_data['slider'][ $image_id ]['src'] = esc_url( $meta['src'] );
			}
			$slider_data = apply_filters( 'soliloquy_ajax_save_bulk_meta', $slider_data, $meta, $image_id, $post_id );
		}

		// Update the slider data.
		update_post_meta( $post_id, '_sol_slider_data', $slider_data );

		// Flush the slider cache.
		Soliloquy_Common_Lite::get_instance()->flush_slider_caches( $post_id );

		// Done.
		wp_send_json_success();

	}
	/**
	 * Refreshes the DOM view for a slider.
	 *
	 * @since 1.0.0
	 */
	public function refresh() {

		// Run a security check first.
		check_ajax_referer( 'soliloquy-refresh', 'nonce' );

		// Prepare variables.
		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : false;
		$slider  = '';

		if ( false === $post_id ) {
			echo wp_json_encode( array( 'error' => true ) );
			die;
		}
		// Grab all slider data.
		$slider_data = get_post_meta( $post_id, '_sol_slider_data', true );

		// If there are no slider items, don't do anything.
		if ( empty( $slider_data ) || empty( $slider_data['slider'] ) ) {
			echo wp_json_encode( array( 'error' => true ) );
			die;
		}

		// Loop through the data and build out the slider view.
		foreach ( (array) $slider_data['slider'] as $id => $data ) {
			$slider .= Soliloquy_Metaboxes_Lite::get_instance()->get_slider_item( $id, $data, $data['type'], $post_id );
		}

		echo wp_json_encode( array( 'success' => $slider ) );
		die;

	}

	/**
	 * Retrieves and return slider data for the specified ID.
	 *
	 * @since 1.0.0
	 */
	public function load_slider_data() {

		// Prepare variables and grab the slider data.
		$slider_id   = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : false;
		$slider_data = get_post_meta( $slider_id, '_sol_slider_data', true );

		// Send back the slider data.
		echo wp_json_encode( $slider_data );
		die;

	}

	/**
	 * Helper function to prepare the metadata for an image in a slider.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $slider_data  Array of data for the slider.
	 * @param int    $id             The Post ID to prepare data for.
	 * @param string $type        The type of slide to prepare (defaults to image).
	 * @param array  $data         Data to be used for the slide.
	 * @return array $slider_data Amended slider data with updated image metadata.
	 */
	public function prepare_slider_data( $slider_data, $id, $type = 'image', $data = array() ) {

		// Get global option for slide status.
		$publishing_default = get_option( 'soliloquy-publishing-default', 'pending' );

		switch ( $type ) {
			case 'image':
				$attachment = get_post( $id );
				$url        = wp_get_attachment_image_src( $id, 'full' );
				$alt_text   = get_post_meta( $id, '_wp_attachment_image_alt', true );
				$slide      = array(
					'status'        => $publishing_default,
					'id'            => $id,
					'attachment_id' => $id,
					'src'           => isset( $url[0] ) ? esc_url( $url[0] ) : '',
					'title'         => get_the_title( $id ),
					'link'          => '',
					'alt'           => ! empty( $alt_text ) ? $alt_text : get_the_title( $id ),
					'caption'       => ! empty( $attachment->post_excerpt ) ? $attachment->post_excerpt : '',
					'type'          => $type,
				);
				break;
			case 'video':
				$slide = array(
					'status'  => $publishing_default,
					'id'      => $id,
					'src'     => isset( $data['src'] ) ? esc_url( $data['src'] ) : '',
					'title'   => isset( $data['title'] ) ? esc_html( $data['title'] ) : '',
					'url'     => isset( $data['url'] ) ? esc_url( $data['url'] ) : '',
					'caption' => isset( $data['caption'] ) ? trim( $data['caption'] ) : '',
					'type'    => $type,
				);

				// If no thumbnail specified, attempt to get it from the video.
				if ( empty( $data['src'] ) ) {
					// Get Video Thumbnail.
					if ( preg_match( '#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#', $data['url'], $y_matches ) ) {
						// YouTube.
						$video_id = $y_matches[0];

						// Get HD or SD thumbnail.
						$data['src'] = $this->get_youtube_thumbnail_url( $video_id );
					} elseif ( preg_match( '#(?:https?:\/\/(?:[\w]+\.)*vimeo\.com(?:[\/\w]*\/videos?)?\/([0-9]+)[^\s]*)#i', $data['url'], $v_matches ) ) {
						// Vimeo.
						$video_id = $v_matches[1];

						// Get highest resolution thumbnail.
						$data['src'] = $this->get_vimeo_thumbnail_url( $video_id );
					} elseif ( preg_match( '/https?:\/\/(.+)?(wistia.com|wi.st)\/.*/i', $data['url'], $w_matches ) ) {
						// Wistia.
						$parts    = explode( '/', $w_matches[0] );
						$video_id = array_pop( $parts );

						// Get image from API.
						$res = wp_remote_get( 'http://fast.wistia.net/oembed?url=' . urlencode( $item['url'] ) );
						$bod = wp_remote_retrieve_body( $res );
						$api = json_decode( $bod, true );
						if ( ! empty( $api['thumbnail_url'] ) ) {
							$data['src'] = remove_query_arg( 'image_crop_resized', $api['thumbnail_url'] );
						}
					} else {
						// Unknown.
						$video_id = false;
					}

					// If a thumbnail was found, import it to the local filesystem.
					$stream = Soliloquy_Import::get_instance()->import_remote_image( $data['src'], $data, $id, 0, true );
					if ( ! is_wp_error( $stream ) ) {
						if ( empty( $stream['error'] ) || isset( $stream['error'] ) && ! $stream['error'] ) {
							$slide['attachment_id'] = $stream['attachment_id'];
							$slide['src']           = $stream['url'];
						}
					}
				}

				break;
			case 'html':
				$slide = array(
					'status' => $publishing_default,
					'id'     => $id,
					'title'  => isset( $data['title'] ) ? esc_html( $data['title'] ) : '',
					'code'   => isset( $data['code'] ) ? trim( $data['code'] ) : '',
					'type'   => $type,
				);
				break;
		}
		// If slider data is not an array (i.e. we have no slides), just add the slide to the array.
		if ( ! isset( $slider_data['slider'] ) || ! is_array( $slider_data['slider'] ) ) {
			$slider_data['slider']        = array();
			$slider_data['slider'][ $id ] = $slide;

		} else {
			// Add this image to the start or end of the gallery, depending on the setting.
			$slide_position = get_option( 'soliloquy_slide_position' );

			switch ( $slide_position ) {
				case 'before':
					// Add slide to start of slides array.
					// Store copy of slides, reset slider array and rebuild.
					$slides                       = $slider_data['slider'];
					$slider_data['slider']        = array();
					$slider_data['slider'][ $id ] = $slide;
					foreach ( $slides as $old_slide_id => $old_slide ) {
						$slider_data['slider'][ $old_slide_id ] = $old_slide;
					}
					break;
				case 'after':
				default:
					// Add slide, this will default to the end of the array.
					$slider_data['slider'][ $id ] = $slide;
					break;
			}
		}

		// Filter and return.
		$slider_data = apply_filters( 'soliloquy_ajax_item_data', $slider_data, $id, $type );

		return $slider_data;

	}

	/**
	 * Helper Method to change Slider view.
	 *
	 * @return void
	 */
	public function slider_view() {

		// Run a security check first.
		check_ajax_referer( 'soliloquy-save-meta', 'nonce' );
		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : false;
		$view    = isset( $_POST['view'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['view'] ) ) ) : false;

		if ( false === $post_id ) {
			wp_send_json_error();
		}

		$slider_data = get_post_meta( $post_id, '_sol_slider_data', true );

		// Save the different types of default meta fields for images, videos and HTML slides.
		if ( false !== $view ) {
			$slider_data['admin_view'] = trim( esc_html( $view ) );
		}

		// Allow filtering of meta before saving.
		$slider_data = apply_filters( 'soliloquy_ajax_change_status', $slider_data, $meta, $attach_id, $post_id );

		// Update the slider data.
		update_post_meta( $post_id, '_sol_slider_data', $slider_data );

		// Flush the slider cache.
		Soliloquy_Common_Lite::get_instance()->flush_slider_caches( $post_id );

		wp_send_json_success();

	}

	/**
	 * Ajax Method to change slide status.
	 *
	 * @return void
	 */
	public function change_slide_status() {

		// Run a security check first.
		check_ajax_referer( 'soliloquy-save-meta', 'nonce' );

		// Prepare variables.
		$post_id     = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : '';
		$attach_id   = isset( $_POST['slide_id'] ) ? wp_unslash( $_POST['slide_id'] ) : '';
		$status      = isset( $_POST['status'] ) ? sanitize_text_field( wp_unslash( $_POST['status'] ) ) : '';
		$slider_data = get_post_meta( $post_id, '_sol_slider_data', true );

		// Go ahead and ensure to store the attachment ID.
		$slider_data['slider'][ $attach_id ]['id'] = $attach_id;

		// Save the different types of default meta fields for images, videos and HTML slides.
		if ( isset( $status ) ) {
			$slider_data['slider'][ $attach_id ]['status'] = trim( esc_html( $status ) );
		}

		// Allow filtering of meta before saving.
		$slider_data = apply_filters( 'soliloquy_ajax_change_status', $slider_data, $status, $attach_id, $post_id );

		// Update the slider data.
		update_post_meta( $post_id, '_sol_slider_data', $slider_data );

		// Flush the slider cache.
		Soliloquy_Common_Lite::get_instance()->flush_slider_caches( $post_id );

		wp_send_json_success();

	}

	/**
	 * Grabs JS and executes it for any uninitialised sliders on screen
	 *
	 * Used by soliloquyInitManually() JS function, which in turn is called
	 * by AJAX requests e.g. after an Infinite Scroll event.
	 *
	 * @since 1.0.0
	 */
	public function init_sliders() {

		// Run a security check first.
		check_ajax_referer( 'soliloquy-ajax-nonce', 'ajax_nonce' );

		// Check we have some slider IDs.
		if ( ! isset( $_REQUEST['ids'] ) ) {
			die();
		}

		// Setup instance.
		$instance = Soliloquy_Shortcode::get_instance();
		$base     = Soliloquy::get_instance();

		// Build JS for each slider.
		$js          = '';
		$request_ids = isset ( $_REQUEST['ids'] ) ? wp_unslash( $_REQUEST['ids'] ) : array();
		foreach ( $request_ids as $slider_id ) {

			// Get slider.
			$data = $base->get_slider( $slider_id );

			// If no slider found, skip.
			if ( ! $data ) {

				if ( class_exists( 'Soliloquy_Dynamic_Common' ) ) {

					$dynamic_id = Soliloquy_Dynamic_Common::get_instance()->get_dynamic_id();
					$defaults   = get_post_meta( $dynamic_id, '_sol_slider_data', true );

					$data       = $defaults;
					$data['id'] = 'custom_' . $slider_id;

				} else {

					continue;

				}

			}

			$js .= $instance->slider_init_single( $data, true );

		}

		// Output JS.
		echo $js;
		die();

	}
	function activate_partner() {
		// Run a security check first.
		check_admin_referer( 'envira-gallery-activate-partner', 'nonce' );

		// Activate the addon.
		if ( isset( $_POST['basename'] ) ) {
			$activate = activate_plugin( wp_unslash( $_POST['basename'] ) );  // @codingStandardsIgnoreLine

			if ( is_wp_error( $activate ) ) {
				echo wp_json_encode( array( 'error' => $activate->get_error_message() ) );
				die;
			}
		}

		echo wp_json_encode( true );
		die;

	}

	function deactivate_partner() {
		// Run a security check first.
		check_admin_referer( 'envira-gallery-deactivate-partner', 'nonce' );

		// Deactivate the addon.
		if ( isset( $_POST['basename'] ) ) {
			$deactivate = deactivate_plugins( wp_unslash( $_POST['basename'] ) );  // @codingStandardsIgnoreLine
		}

		echo wp_json_encode( true );
		die;
	}
	function install_partner() {

		check_admin_referer( 'envira-gallery-install-partner', 'nonce' );
		// Install the addon.
		if ( isset( $_POST['download_url'] ) ) {

			$download_url = esc_url_raw( wp_unslash( $_POST['download_url'] ) );
			global $hook_suffix;

			// Set the current screen to avoid undefined notices.
			set_current_screen();

			// Prepare variables.
			$method = '';
			$url    = add_query_arg(
				array(
					'page' => 'pdfemb_list_options',
				),
				admin_url( 'options-general.php' )
			);
			$url    = esc_url( $url );

			// Start output bufferring to catch the filesystem form if credentials are needed.
			ob_start();
			$creds = request_filesystem_credentials( $url, $method, false, false, null );
			if ( false === $creds ) {
				$form = ob_get_clean();
				echo wp_json_encode( array( 'form' => $form ) );
				die;
			}

			// If we are not authenticated, make it happen now.
			if ( ! WP_Filesystem( $creds ) ) {
				ob_start();
				request_filesystem_credentials( $url, $method, true, false, null );
				$form = ob_get_clean();
				echo wp_json_encode( array( 'form' => $form ) );
				die;
			}

			// We do not need any extra credentials if we have gotten this far, so let's install the plugin.
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			require_once plugin_dir_path( ENVIRA_FILE ) . 'includes/global/Installer_Skin.php';

			// Create the plugin upgrader with our custom skin.
			$skin      = new Envira_Lite_Installer_Skin();
			$installer = new Plugin_Upgrader( $skin );
			$installer->install( $download_url );

			// Flush the cache and return the newly installed plugin basename.
			wp_cache_flush();

			if ( $installer->plugin_info() ) {
				$plugin_basename = $installer->plugin_info();

				wp_send_json_success( array( 'plugin' => $plugin_basename ) );

				die();
			}
		}

		// Send back a response.
		echo wp_json_encode( true );
		die;

	}
	/**
	 * Returns the singleton instance of the class.
	 *
	 * @since 2.5
	 *
	 * @return object The Soliloquy_Ajax object.
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Soliloquy_Ajax ) ) {
			self::$instance = new Soliloquy_Ajax();
		}

		return self::$instance;

	}

}

$soliloquy_ajax = Soliloquy_Ajax::get_instance();
