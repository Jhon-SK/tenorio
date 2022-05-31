<?php
/**
 * Welcome class.
 *
 * @since 1.8.1
 *
 * @package SoliloquyWP
 * @author  SoliloquyWP Team
 */


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Welcome Class
 *
 * @since 1.7.0
 *
 * @package SoliloquyWP
 * @author  SoliloquyWP Team <help@soliloquywp.com>
 */
class Soliloquy_Welcome {

	public $pages = array(
		'soliloquy-lite-get-started',
		'soliloquy-lite-welcome',
		'soliloquy-lite-partners',
		'soliloquy-lite-upgrade',
		'soliloquy-lite-litevspro'
	);

	/**
	 * Holds the submenu pagehook.
	 *
	 * @since 1.7.0
	 *
	 * @var string`
	 */
	public $hook;

	public $installed_plugins;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.8.1
	 */
	public function __construct() {

		if ( ( defined( 'SOLILOQUY_WELCOME_SCREEN' ) && false === SOLILOQUY_WELCOME_SCREEN ) || apply_filters( 'soliloquy_whitelabel', false ) === true ) {
			//return;
		}

		// Add custom addons submenu.
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 15 );

		// Add custom CSS class to body.
		add_filter( 'admin_body_class', array( $this, 'admin_welcome_css' ), 15 );

		// Add scripts and styles.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		add_action( 'admin_menu', array( $this, 'remove_admin_menus'), 999 );

		// Misc.
		add_action( 'admin_print_scripts', array( $this, 'disable_admin_notices' ) );

	}

	/**
	 * Add custom CSS to admin body tag.
	 *
	 * @since 1.8.1
	 * @param array $classes CSS Classes.
	 * @return array
	 */
	public function admin_welcome_css( $classes ) {

		if ( ! is_admin() ) {
			return;
		}

		$classes .= ' soliloquy-welcome-enabled ';

		return $classes;

	}

     /**
      * Register and enqueue addons page specific JS.
      *
      * @since 1.5.0
      */
     public function enqueue_admin_scripts() {
		if ( isset( $_GET['post_type'] ) && isset( $_GET['page'] ) && 'soliloquy' === wp_unslash( $_GET['post_type'] ) && in_array( wp_unslash( $_GET['page'] ), $this->pages ) ) { // @codingStandardsIgnoreLine

		wp_register_script( SOLILOQUY_SLUG . '-welcome-script', plugins_url( 'assets/js/welcome.js', SOLILOQUY_FILE ), array( 'jquery' ), SOLILOQUY_VERSION, true );
		wp_enqueue_script( SOLILOQUY_SLUG . '-welcome-script' );
		wp_localize_script(
			SOLILOQUY_SLUG . '-welcome-script',
			'soliloquy_welcome',
			array(
			    'activate_nonce'      => wp_create_nonce( 'soliloquy-activate-partner' ),
			    'active'           => __( 'Status: Active', 'soliloquy' ),
			    'activate'         => __( 'Activate', 'soliloquy' ),
			    'get_addons_nonce'   => wp_create_nonce( 'soliloquy-get-addons' ),
			    'activating'       => __( 'Activating...', 'soliloquy' ),
			    'ajax'             => admin_url( 'admin-ajax.php' ),
			    'deactivate'       => __( 'Deactivate', 'soliloquy' ),
			    'deactivate_nonce' => wp_create_nonce( 'soliloquy-deactivate-partner' ),
			    'deactivating'     => __( 'Deactivating...', 'soliloquy' ),
			    'inactive'         => __( 'Status: Inactive', 'soliloquy' ),
			    'install'          => __( 'Install', 'soliloquy' ),
			    'install_nonce'    => wp_create_nonce( 'soliloquy-install-partner' ),
			    'installing'       => __( 'Installing...', 'soliloquy' ),
			    'proceed'          => __( 'Proceed', 'soliloquy' ),
			)
		);
	}
     }

	/**
	 * Register and enqueue addons page specific CSS.
	 *
	 * @since 1.8.1
	 */
	public function enqueue_admin_styles() {

		if ( isset( $_GET['post_type'] ) && isset( $_GET['page'] ) && 'soliloquy' === wp_unslash( $_GET['post_type'] ) && in_array( wp_unslash( $_GET['page'] ), $this->pages ) ) { // @codingStandardsIgnoreLine

			wp_register_style( SOLILOQUY_SLUG . '-welcome-style', plugins_url( 'assets/css/welcome.css', SOLILOQUY_FILE ), array(), SOLILOQUY_VERSION );
			wp_enqueue_style( SOLILOQUY_SLUG . '-welcome-style' );


		}

        // Run a hook to load in custom styles.
        do_action( 'soliloquy_addons_styles' );

	}

	/**
	 * Remove Submenus from sidebar
	 *
	 * @since 1.8.1
	 */
	public function remove_admin_menus() {

		global $submenu;

		unset($submenu['edit.php?post_type=soliloquy'][13]);
		unset($submenu['edit.php?post_type=soliloquy'][14]);
		unset($submenu['edit.php?post_type=soliloquy'][15]);
		unset($submenu['edit.php?post_type=soliloquy'][16]);


	}

	/**
	 * Making page as clean as possible
	 *
	 * @since 1.8.1
	 */
	public function disable_admin_notices() {

		global $wp_filter;

		if ( isset( $_GET['post_type'] ) && isset( $_GET['page'] ) && 'soliloquy' === wp_unslash( $_GET['post_type'] ) && in_array( wp_unslash( $_GET['page'] ), $this->pages ) ) { // @codingStandardsIgnoreLine

			if ( isset( $wp_filter['user_admin_notices'] ) ) {
				unset( $wp_filter['user_admin_notices'] );
			}
			if ( isset( $wp_filter['admin_notices'] ) ) {
				unset( $wp_filter['admin_notices'] );
			}
			if ( isset( $wp_filter['all_admin_notices'] ) ) {
				unset( $wp_filter['all_admin_notices'] );
			}
		}

	}

	public function get_partners() {

		$partners = array(
			'shortpixel' => array(
				'name'        => 'ShortPixel Image Optimizer',
				'description' => 'Speed up your website & boost your SEO by compressing old & new images and PDFs. AVIF & WebP convert and optimize support.',
				'icon'        => plugins_url( 'assets/images/partners/shortpixel.png', SOLILOQUY_FILE ),
				'url'         => 'https://downloads.wordpress.org/plugin/shortpixel-image-optimiser.zip',
				'basename'    => 'shortpixel-image-optimiser/wp-shortpixel.php',
			),
			'soliloquy' => array(
				'name' => 'Gallery Plugin for WordPress – Envira Photo Gallery',
				'description' => 'Envira Gallery is the fastest, easiest to use WordPress image gallery plugin. Lightbox with Drag & Drop builder that helps you create beautiful galleries.',
				'icon' => plugins_url( 'assets/images/partners/envira.png', SOLILOQUY_FILE ),
				'url' => 'https://downloads.wordpress.org/plugin/envira-gallery-lite.zip',
				'basename' => 'soliloquy-lite/soliloquy-lite.php'
			),
			'pdf-embedder' => array(
				'name' => 'PDF Embedder',
				'description' => 'Embed PDF files directly into your posts and pages, with intelligent responsive resizing, and no third-party services or iframes.',
				'icon' => plugins_url( 'assets/images/partners/pdf-embedder.png', SOLILOQUY_FILE ),
				'url' => 'https://downloads.wordpress.org/plugin/pdf-embedder.zip',
				'basename' => 'pdf-embedder/pdf_embedder.php'
			),
			'google_drive_embedder' => array(
				'name' => 'Google Drive Embedder',
				'description' => 'Browse for Google Drive documents and embed directly in your posts/pages. This WordPress plugin extends the Google Apps Login plugin so no extra user …',
				'icon' => plugins_url( 'assets/images/partners/google-drive.png', SOLILOQUY_FILE ),
				'url' => 'https://downloads.wordpress.org/plugin/google-drive-embedder.zip',
				'basename' => 'google-drive-embedder/google_drive_embedder.php',
			),
			'google_apps_login' => array(
				'name' => 'Google Apps Login',
				'description' => 'Simple secure login and user management through your Google Workspace for WordPress (uses secure OAuth2, and MFA if enabled)',
				'icon' => plugins_url( 'assets/images/partners/google-apps.png', SOLILOQUY_FILE ),
				'url' => 'https://downloads.wordpress.org/plugin/google-apps-login.zip',
				'basename' => 'google-apps-login/google_apps_login.php',

			),
			'all_in_one' => array(
				'name' => 'All-In-One Intranet',
				'description' => 'Instantly turn your WordPress installation into a private corporate intranet',
				'icon' => plugins_url( 'assets/images/partners/allinone.png', SOLILOQUY_FILE ),
				'url' => 'https://downloads.wordpress.org/plugin/all-in-one-intranet.zip',
				'basename' => 'all-in-one-intranet/basic_all_in_one_intranet.php',

			),

		);
		return $partners;
	}

	/**
	 * Register the Welcome submenu item for soliloquy.
	 *
	 * @since 1.8.1
	 */
	public function admin_menu() {
		$whitelabel = apply_filters( 'soliloquy_whitelabel', false ) ? '' : __( 'Soliloquy ', 'soliloquy' );
		// Register the submenus.
		add_submenu_page(
			'edit.php?post_type=soliloquy',
			$whitelabel . __( 'Get Started', 'soliloquy' ),
			'<span style="color:#FFA500"> ' . __( 'Get Started', 'soliloquy' ) . '</span>',
			apply_filters( 'soliloquy_menu_cap', 'manage_options' ),
			SOLILOQUY_SLUG . '-get-started',
			array( $this, 'help_page' )
		);

		add_submenu_page(
			'edit.php?post_type=soliloquy',
			$whitelabel . __( 'Upgrade SoliloquyWP', 'soliloquy' ),
			'<span style="color:#FFA500"> ' . __( 'Upgrade SoliloquyWP', 'soliloquy' ) . '</span>',
			apply_filters( 'soliloquy_menu_cap', 'manage_options' ),
			SOLILOQUY_SLUG . '-upgrade',
			array( $this, 'upgrade_page' )
		);

		add_submenu_page(
			'edit.php?post_type=soliloquy',
			$whitelabel . __( 'Lite vs Pro', 'soliloquy' ),
			'<span style="color:#FFA500"> ' . __( 'Lite vs Pro', 'soliloquy' ) . '</span>',
			apply_filters( 'soliloquy_menu_cap', 'manage_options' ),
			SOLILOQUY_SLUG . '-litevspro',
			array( $this, 'lite_vs_pro_page' )
		);

		add_submenu_page(
			'edit.php?post_type=soliloquy',
			$whitelabel . __( 'Welcome', 'soliloquy' ),
			'<span style="color:#FFA500"> ' . __( 'Welcome', 'soliloquy' ) . '</span>',
			apply_filters( 'soliloquy_menu_cap', 'manage_options' ),
			SOLILOQUY_SLUG . '-welcome',
			array( $this, 'welcome_page' )
		);

		add_submenu_page(
			'edit.php?post_type=soliloquy',
			$whitelabel . __( 'Partners', 'soliloquy' ),
			'<span style="color:#FFA500"> ' . __( 'Partners', 'soliloquy' ) . '</span>',
			apply_filters( 'soliloquy_menu_cap', 'manage_options' ),
			SOLILOQUY_SLUG . '-partners',
			array( $this, 'partners_page' )
		);

	}

	/**
	 * Output welcome text and badge for What's New and Credits pages.
	 *
	 * @since 1.8.1
	 */
	public static function welcome_text() {

		// Switch welcome text based on whether this is a new installation or not.
		$welcome_text = ( self::is_new_install() )
			? esc_html( 'Thank you for installing Soliloquy Lite! soliloquy provides great slider features for your WordPress site!', 'soliloquy' )
			: esc_html( 'Thank you for updating! Soliloquy Lite %s has many recent improvements that you will enjoy.', 'soliloquy' );

		?>
<?php /* translators: %s: version */ ?>
<h1 class="welcome-header">
	<?php printf( esc_html__( 'Welcome to %1$s Soliloquy Lite %2$s', 'soliloquy' ), '&nbsp;', esc_html( self::display_version() ) ); ?>
</h1>

<div class="about-text">
	<?php
			if ( self::is_new_install() ) {
				echo esc_html( $welcome_text );
			} else {
				printf( $welcome_text, self::display_version() ); // @codingStandardsIgnoreLine
			}
			?>
</div>

<?php
	}

	/**
	 * Output tab navigation
	 *
	 * @since 2.2.0
	 *
	 * @param string $tab Tab to highlight as active.
	 */
	public static function tab_navigation( $tab = 'whats_new' ) {
		?>

<h3 class="nav-tab-wrapper">
	<a class="nav-tab
			<?php
			if ( isset( $_GET['page'] ) && 'soliloquy-lite-welcome' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) : // @codingStandardsIgnoreLine
				?>
				nav-tab-active<?php endif; ?>" href="
				<?php
				echo esc_url(
					admin_url(
						add_query_arg(
							array(
								'post_type' => 'soliloquy',
								'page'      => 'soliloquy-lite-welcome',
							),
							'edit.php'
						)
					)
				);
				?>
														">
		<?php esc_html_e( 'What&#8217;s New', 'soliloquy' ); ?>
	</a>
	<a class="nav-tab
			<?php
			if ( isset( $_GET['page'] ) && 'soliloquy-get-started' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) : // @codingStandardsIgnoreLine
				?>
				nav-tab-active<?php endif; ?>" href="
				<?php
				echo esc_url(
					admin_url(
						add_query_arg(
							array(
								'post_type' => 'soliloquy',
								'page'      => 'soliloquy-lite-get-started',
							),
							'edit.php'
						)
					)
				);
				?>
														">
		<?php esc_html_e( 'Get Started', 'soliloquy' ); ?>
	</a>
	<a class="nav-tab
			<?php
			if ( isset( $_GET['page'] ) && 'soliloquy-lite-litevspro' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) : // @codingStandardsIgnoreLine
				?>
				nav-tab-active<?php endif; ?>" href="
				<?php
				echo esc_url(
					admin_url(
						add_query_arg(
							array(
								'post_type' => 'soliloquy',
								'page'      => 'soliloquy-lite-litevspro',
							),
							'edit.php'
						)
					)
				);
				?>
														">
		<?php esc_html_e( 'Lite vs Pro', 'soliloquy' ); ?>
	</a>
	<a class="nav-tab
			<?php
			if ( isset( $_GET['page'] ) && 'soliloquy-lite-upgrade' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) : // @codingStandardsIgnoreLine
				?>
				nav-tab-active<?php endif; ?>" href="
				<?php
				echo esc_url(
					admin_url(
						add_query_arg(
							array(
								'post_type' => 'soliloquy',
								'page'      => 'soliloquy-lite-upgrade',
							),
							'edit.php'
						)
					)
				);
				?>
														">
		<?php esc_html_e( 'Unlock Pro', 'soliloquy' ); ?>
	</a>
	<a class="nav-tab
			<?php
			if ( isset( $_GET['page'] ) && 'soliloquy-lite-partners' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) : // @codingStandardsIgnoreLine
				?>
				nav-tab-active<?php endif; ?>" href="
				<?php
				echo esc_url(
					admin_url(
						add_query_arg(
							array(
								'post_type' => 'soliloquy',
								'page'      => 'soliloquy-lite-partners',
							),
							'edit.php'
						)
					)
				);
				?>
														">
		<?php esc_html_e( 'Partners', 'soliloquy' ); ?>
	</a>
</h3>

<?php
	}

	/**
	 * Output the about screen.
	 *
	 * @since 1.8.5
	 */
	public function welcome_page() {
		?>
<?php self::tab_navigation( __METHOD__ ); ?>
<div class="soliloquy-welcome-wrap soliloquy-welcome">

	<div class="soliloquy-welcome-main">

		<div class="soliloquy-welcome-panel">
			<div class="wraps upgrade-wrap">
				<h2 class="headline-title">
					<?php printf( esc_html__( 'Welcome to Soliloquy Lite %1$s', 'soliloquy' ), esc_html( self::display_version() ) ); ?>
				</h2>

				<h4 class="headline-subtitle">
					<?php esc_html_e( 'The Best Responsive WordPress Slider Plugin… Without the High Costs..', 'soliloquy' ); ?>
					</h2>
			</div>
			<div class="wraps about-wsrap">

				<div class="soliloquy-panel soliloquy-lite-updates-panel">
					<h3 class="title"><?php esc_html_e( 'Recent Updates To Soliloquy Lite:', 'soliloquy' ); ?></h3>

					<div class="soliloquy-recent soliloquy three-column">
						<div class="soliloquy column">
							<h4 class="title"><?php esc_html_e( 'Bug Fixes', 'soliloquy' ); ?> <span
									class="badge updated">UPDATED</span></h4>
							<?php /* translators: %1$s: link */ ?>
							<p><?php printf( esc_html__( 'Bugs improving PHP 8+ support.' ) ); ?>
							</p>
						</div>
						<div class="soliloquy column">
							<h4 class="title"><?php esc_html_e( 'Gutenberg Block', 'soliloquy' ); ?></h4>
							<?php /* translators: %1$s: link */ ?>
							<p><?php printf( esc_html__( 'Improved support for the Soliloquy Lite Gutenberg block. Bugs' ) ); ?>
							</p>
						</div>

						<div class="soliloquy column">
							<h4 class="title"><?php esc_html_e( 'Enhancements', 'soliloquy' ); ?></h4>
							<p><?php printf( esc_html__( 'UI tweaks and Improvements.', 'soliloquy' ) ); ?>
							</p>
						</div>
					</div>

				</div>

			</div>

		</div>

	</div>

</div> <!-- wrap -->

<?php
	}

	/**
	 * Output the about screen.
	 *
	 * @since 1.8.1
	 */
	public function help_page() {
		?>
<?php self::tab_navigation( __METHOD__ ); ?>

<div class="soliloquy-welcome-wrap soliloquy-help">

	<div class="soliloquy-get-started-main">

		<div class="soliloquy-get-started-section">
			<div class="wraps upgrade-wrap">

				<h2 class="headline-title"><?php esc_html_e( 'Gettings Started With Soliloquy!', 'soliloquy' ); ?>
				</h2>

				<h4 class="headline-subtitle">
					<?php esc_html_e( 'Are you ready to get started on creating your first slider?', 'soliloquy' ); ?>
				</h4>

			</div>

			<div class="soliloquy-admin-upgrade-panel soliloquy-panel">

				<div class="section-text-column text-left">

					<h2>Upgrade to a complete Soliloquy experience</h2>

					<p>Get the most out of Soliloquy by <a target="_blank"
							href="<?php echo Soliloquy_Common_Admin_Lite::get_instance()->get_upgrade_link( false, 'gettingstartedtab', 'upgradetounlockallitspowerfulfeatures' ); ?>">upgrading
							to unlock all of its powerful features</a>.</p>

					<p>With Soliloquy Pro, you can unlock amazing features like:</p>

					<ul>
						<li>Get your slider set up in minutes with pre-built customizable templates </li>
						<li>Have more people find you on Google by making your sliders SEO friendly </li>
						<li>Native video slide support for YouTube, Vimeo and Wistia. Just add a video slide,
							enter in your URL, set a video thumbnail and off you go. </li>
						<li>A large selection of 10+ killer addons that can extend the base functionality of the
							slider.</li>

						</li>
					</ul>
					<a href="#" class="button soliloquy-button soliloquy-primary-button">Unlock Pro</a>
				</div>

				<div class="feature-photo-column">
					<img class="feature-photo"
						src="<?php echo esc_url( plugins_url( 'assets/images/soliloquy-admin.png', SOLILOQUY_FILE ) ); ?>" />
				</div>

			</div> <!-- panel -->

			<div class="soliloquy-admin-get-started-banner bottom">

				<div class="banner-text">
					<h3>Start Creating Responsive Sliders</h3>
					<p>Customize and Publish in Minutes... What are you waiting for?</p>
				</div>
				<div class="banner-button">
					<a target="_blank"
						href="<?php echo Soliloquy_Common_Admin_Lite::get_instance()->get_upgrade_link( false, 'getstartedtab', 'getsoliloquynowbutton' ); ?>"
						class="button button-primary">Get Soliloquy Pro Now</a>
				</div>

			</div> <!-- banner -->
			<div class="soliloquy-admin-3-col soliloquy-help-section">
				<div class="soliloquy-cols">
					<svg xmlns="http://www.w3.org/2000/svg" width="50px" viewBox="0 0 512 512" fill="#454346">
						<path
							d="M432 0H48C21.6 0 0 21.6 0 48v416c0 26.4 21.6 48 48 48h384c26.4 0 48-21.6 48-48V48c0-26.4-21.6-48-48-48zm-16 448H64V64h352v384zM128 224h224v32H128zm0 64h224v32H128zm0 64h224v32H128zm0-192h224v32H128z">
						</path>
					</svg>
					<h3>Help and Documention</h3>
					<p>The Soliloquy Slider wiki has helpful documentation, tips, tricks, and code snippets to
						help you get started.</p>
					<a href="<?php echo Soliloquy_Common_Admin_Lite::get_instance()->get_upgrade_link( 'https://soliloquywp.com/docs/', 'getstartedtab', 'docs' ); ?>"
						class="button soliloquy-button soliloquy-primary-button">Browse the docs</a>
				</div>
				<div class="soliloquy-cols">
					<svg xmlns="http://www.w3.org/2000/svg" width="50px" viewBox="0 0 512 512" fill="#A32323">
						<path
							d="M256 0C114.615 0 0 114.615 0 256s114.615 256 256 256 256-114.615 256-256S397.385 0 256 0zm-96 256c0-53.02 42.98-96 96-96s96 42.98 96 96-42.98 96-96 96-96-42.98-96-96zm302.99 85.738l-88.71-36.745C380.539 289.901 384 273.355 384 256s-3.461-33.901-9.72-48.993l88.71-36.745C473.944 196.673 480 225.627 480 256s-6.057 59.327-17.01 85.738zM341.739 49.01l-36.745 88.71C289.902 131.461 273.356 128 256 128s-33.901 3.461-48.993 9.72l-36.745-88.711C196.673 38.057 225.628 32 256 32c30.373 0 59.327 6.057 85.739 17.01zM49.01 170.262l88.711 36.745C131.462 222.099 128 238.645 128 256s3.461 33.901 9.72 48.993l-88.71 36.745C38.057 315.327 32 286.373 32 256s6.057-59.327 17.01-85.738zM170.262 462.99l36.745-88.71C222.099 380.539 238.645 384 256 384s33.901-3.461 48.993-9.72l36.745 88.71C315.327 473.942 286.373 480 256 480s-59.327-6.057-85.738-17.01z">
						</path>
					</svg>
					<h3>Get Support</h3>
					<p>Submit a support ticket and our world class support will be in touch.</p>
					<a href="<?php echo Soliloquy_Common_Admin_Lite::get_instance()->get_upgrade_link( false, 'getstartedtab', 'support' ); ?>"
						class="button soliloquy-button soliloquy-primary-button">Unlock Pro</a>
				</div>
				<div class="soliloquy-cols">
					<svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 125.23 125.23"
						width="50px">
						<path fill="#162937" fillRule="evenodd"
							d="M112.51 31.91l-7.67-7.68v15.36H89.49l7.67 7.67a21.72 21.72 0 010 30.71l-3.84 3.84v15.35h15.36l3.84-3.84a43.43 43.43 0 00-.01-61.41z">
						</path>
						<path fill="#162937" fillRule="evenodd"
							d="M85.65 89.48L78 97.16a21.72 21.72 0 01-30.71 0l-3.84-3.84H28.07v15.36l3.84 3.83a43.43 43.43 0 0061.41 0l7.68-7.68H85.65z">
						</path>
						<path fill="#ff3700" fillRule="evenodd"
							d="M35.75 85.65L28.07 78a21.72 21.72 0 010-30.71l3.84-3.83V28.07H16.56l-3.84 3.84a43.42 43.42 0 000 61.41L20.4 101V85.65z">
						</path>
						<path fill="#162937" fillRule="evenodd"
							d="M39.59 35.75l7.67-7.68a21.72 21.72 0 0130.71 0l3.84 3.84h15.35V16.56l-3.84-3.84a43.42 43.42 0 00-61.41 0l-7.67 7.68h15.35z">
						</path>
					</svg>
					<h3>Enjoying Soliloquy?</h3>
					<p>Submit a support ticket and our world class support will be in touch.</p>
					<a href="https://wordpress.org/plugins/soliloquy-lite/#reviews"
						class="button soliloquy-button soliloquy-primary-button">Leave a Review</a>
				</div>
			</div>
		</div>

	</div> <!-- wrap -->


	<?php
	}

	/**
	 * Output the upgrade screen.
	 *
	 * @since 1.8.1
	 */
	public function upgrade_page() {
		?>
	<?php self::tab_navigation( __METHOD__ ); ?>

	<div class="soliloquy-welcome-wrap soliloquy-help">

		<div class="soliloquy-get-started-main">


			<div class="soliloquy-get-started-panel">

				<div class="wraps upgrade-wrap">

					<h2 class="headline-title"><?php esc_html_e( 'Make Your Sliders Amazing!', 'soliloquy' ); ?>
					</h2>

					<h4 class="headline-subtitle">
						<?php esc_html_e( 'Upgrade To Soliloquy Pro and can get access to our full suite of features.', 'soliloquy' ); ?>
					</h4>

					<a target="_blank"
						href="<?php echo Soliloquy_Common_Admin_Lite::get_instance()->get_upgrade_link( false, 'upgradesoliloquytab', 'upgradetosoliloquyprobutton' ); ?>"
						class="button soliloquy-button soliloquy-primary-button">Upgrade To Soliloquy Pro</a>

				</div>

				<div class="upgrade-list">

					<div>
						<div class="interior">
							<a
								href="<?php echo Soliloquy_Common_Admin_Lite::get_instance()->get_upgrade_link( "https://soliloquywp.com/addons/carousel/", "upgradesoliloquytab", "carouseladdon", "" ); ?>">
								<div class="feature-icon">
									<img
										src="<?php echo esc_url( plugins_url( 'assets/images/features/carousel-addon.png', SOLILOQUY_FILE ) ); ?>" />
								</div>
								<h5>Carousel Addon</h5>
								<p>Create a responsive carousel slider in WordPress for your images, photos,
									videos, and even galleries.</p>
						</div>
						</a>
					</div>
					<div>
						<div class="interior">
							<a
								href="<?php echo Soliloquy_Common_Admin_Lite::get_instance()->get_upgrade_link( "https://soliloquywp.com/addons/thumbnails/", "upgradesoliloquytab", "thumbnailaddon", "" ); ?>">
								<div class="feature-icon">
									<img
										src="<?php echo esc_url( plugins_url( 'assets/images/features/thumbnails-addon.png', SOLILOQUY_FILE ) ); ?>" />
								</div>
								<h5>Thumbnails Addon</h5>
								<p>Soliloquy's Thumbnails Addon allows you to add thumbnail images as navigation
									for your WordPress slider.</p>
						</div>
						</a>
					</div>
					<div>
						<div class="interior">
							<a
								href="<?php echo Soliloquy_Common_Admin_Lite::get_instance()->get_upgrade_link( "https://soliloquywp.com/addons/instagram/", "upgradesoliloquytab", "instagramaddon", "" ); ?>">
								<div class="feature-icon">
									<img
										src="<?php echo esc_url( plugins_url( 'assets/images/features/instagram-addon.png', SOLILOQUY_FILE ) ); ?>" />
								</div>
								<h5>Instagram Addon</h5>
								<p>Soliloquy’s Instagram addon allows you to import your images from Instagram
									into WordPress with just a few clicks.</p>
						</div>
						</a>
					</div>
					<div>
						<div class="interior">
							<a
								href="<?php echo Soliloquy_Common_Admin_Lite::get_instance()->get_upgrade_link( "https://soliloquywp.com/addons/pdf/", "upgradesoliloquytab", "pdfaddon", "" ); ?>">
								<div class="feature-icon">
									<img
										src="<?php echo esc_url( plugins_url( 'assets/images/features/pdf-addon.png', SOLILOQUY_FILE ) ); ?>" />
								</div>
								<h5>PDF Slider Addon</h5>
								<p>Soliloquy's PDF Addon allows you to create responsive WordPress sliders from
									your presentation slides and other PDF files.</p>
						</div>
						</a>
					</div>
					<div>
						<div class="interior">
							<a
								href="<?php echo Soliloquy_Common_Admin_Lite::get_instance()->get_upgrade_link( "https://soliloquywp.com/addons/schedule/", "upgradesoliloquytab", "socialaddon", "" ); ?>">
								<div class="feature-icon">
									<img
										src="<?php echo esc_url( plugins_url( 'assets/images/features/schedule-addon.png', SOLILOQUY_FILE ) ); ?>" />
								</div>
								<h5>Schedule Addon</h5>
								<p>Easily schedule both sliders and individual slides to be displayed at
									specific time intervals. Perfect for highlighting time-sensitive content.
								</p>
						</div>
						</a>
					</div>
					<div>
						<div class="interior">
							<a
								href="<?php echo Soliloquy_Common_Admin_Lite::get_instance()->get_upgrade_link( "https://soliloquywp.com/addons/dynamic/", "upgradesoliloquytab", "imageproofing", "" ); ?>">
								<div class="feature-icon">
									<img
										src="<?php echo esc_url( plugins_url( 'assets/images/features/dynamic-addon.png', SOLILOQUY_FILE ) ); ?>" />
								</div>
								<h5>Dynamic Addon</h5>
								<p>The Dynamic Addon is extremely powerful because it lets you dynamically
									create WordPress sliders from blog posts, testimonials, Instagram images,
									and more.</p>
						</div>
						</a>
					</div>
					<div>
						<div class="interior">
							<a
								href="<?php echo Soliloquy_Common_Admin_Lite::get_instance()->get_upgrade_link( "https://soliloquywp.com/addons/woocommerce-addon/", "upgradesoliloquytab", "ecommerce", "" ); ?>">
								<div class="feature-icon">

									<img
										src="<?php echo esc_url( plugins_url( 'assets/images/features/woo-addon.png', SOLILOQUY_FILE ) ); ?>" />
								</div>
								<h5>Ecommerce</h5>
								<p>WooCommerce integration allows you to easily create beautiful and dynamic
									product sliders.</p>
						</div>
						</a>
					</div>
					<div>
						<div class="interior">
							<a
								href="<?php echo Soliloquy_Common_Admin_Lite::get_instance()->get_upgrade_link( "https://soliloquywp.com/addons/featured-content/", "upgradesoliloquytab", "featuredcontent", "" ); ?>">
								<div class="feature-icon">
									<img
										src="<?php echo esc_url( plugins_url( 'assets/images/features/featured-content-addon.png', SOLILOQUY_FILE ) ); ?>" />
								</div>
								<h5>Featured Content Addon</h5>
								<p>Easily create WordPress sliders based on blog posts, pages, products,
									testimonials, and other custom post types.</p>
						</div>
						</a>
					</div>

					</ul>

				</div>

			</div>

		</div>

	</div> <!-- wrap -->


	<?php
	}

	/**
	 * Output the upgrade screen.
	 *
	 * @since 1.8.1
	 */
	public function lite_vs_pro_page() {
		?>
	<?php self::tab_navigation( __METHOD__ ); ?>

	<div class="soliloquy-welcome-wrap soliloquy-help">

		<div class="soliloquy-get-started-main">


			<div class="soliloquy-get-started-panel">

				<div id="soliloquy-admin-litevspro" class="wrap soliloquy-admin-wrap">

					<div class="wraps upgrade-wrap">
						<h2 class="headline-title">
							<strong>Lite</strong> vs <strong>Pro</strong>
						</h2>

						<h4 class="headline-subtitle">Get the most out of Soliloquy by upgrading to Pro and
							unlocking all of the powerful features.</h2>
					</div>

					<div
						class="soliloquy-admin-litevspro-section no-bottom soliloquy-admin-litevspro-section-table">

						<table cellspacing="0" cellpadding="0" border="0">
							<thead>
								<th>Feature</th>
								<th>Lite</th>
								<th>Pro</th>
							</thead>
							<tbody>
								<tr class="soliloquy-admin-columns">
									<td class="soliloquy-admin-litevspro-first-column">
										<p>Slider Themes</p>
									</td>
									<td class="soliloquy-admin-litevspro-lite-column">
										<p class="features-partial">
											<strong>Basic Slider Theme</strong>
										</p>
									</td>
									<td class="soliloquy-admin-litevspro-pro-column">
										<p class="features-full">
											<strong>All Slider Themes</strong>
											Enhance the appearance of your WordPress slider layout with
											beautiful and custom slider themes!
										</p>
									</td>
								</tr>

								<tr class="soliloquy-admin-columns">
									<td class="soliloquy-admin-litevspro-first-column">
										<p>Lightbox Features</p>
									</td>
									<td class="soliloquy-admin-litevspro-lite-column">
										<p class="features-partial">
											<strong>No Lightbox</strong>
										</p>
									</td>
									<td class="soliloquy-admin-litevspro-pro-column">
										<p class="features-full">
											<strong>All Advanced Lightbox Features</strong>
											Multiple themes for your Slider Lightbox display, Titles,
											Transitions, Fullscreen, Counter, Thumbnails
										</p>
									</td>
								</tr>

								<tr class="soliloquy-admin-columns">
									<td class="soliloquy-admin-litevspro-first-column">
										<p>Mobile Features</p>
									</td>
									<td class="soliloquy-admin-litevspro-lite-column">
										<p class="features-partial">
											<strong>Basic Mobile Slider</strong>
										</p>
									</td>
									<td class="soliloquy-admin-litevspro-pro-column">
										<p class="features-full">
											<strong>All Advanced Mobile Settings</strong>Customize all
											aspects of your user's mobile sliders display experience to be
											different than the default desktop
										</p>
									</td>
								</tr>
								<tr class="soliloquy-admin-columns">
									<td class="soliloquy-admin-litevspro-first-column">
										<p>HTML Sliders</p>
									</td>
									<td class="soliloquy-admin-litevspro-lite-column">
										<p class="features-none">
											<strong>No HTML Slider</strong>
										</p>
									</td>
									<td class="soliloquy-admin-litevspro-pro-column">
										<p class="features-full">
											<strong>Custom HTML Slides</strong> Add custom fully customizable
											html slides
										</p>
									</td>
								</tr>
								<tr class="soliloquy-admin-columns">
									<td class="soliloquy-admin-litevspro-first-column">
										<p>Video Sliders</p>
									</td>
									<td class="soliloquy-admin-litevspro-lite-column">
										<p class="features-none">
											<strong> No Videos </strong>
										</p>
									</td>
									<td class="soliloquy-admin-litevspro-pro-column">
										<p class="features-full">
											<strong>All Videos Sliders</strong> Import your own videos or
											from any major video sharing platform
										</p>
									</td>
								</tr>
								<tr class="soliloquy-admin-columns">
									<td class="soliloquy-admin-litevspro-first-column">
										<p>Advanced Slider Features </p>
									</td>
									<td class="soliloquy-admin-litevspro-lite-column">
										<p class="features-none">
											<strong> No Advanced Features </strong>
										</p>
									</td>
									<td class="soliloquy-admin-litevspro-pro-column">
										<p class="features-full">
											<strong>All Advanced Features</strong>Carousel, Ecommerce,
											Dynamic, Thumbnails, and Expanded Slider Configurations
										</p>
									</td>
								</tr>
								<tr class="soliloquy-admin-columns">
									<td class="soliloquy-admin-litevspro-first-column">
										<p>Soliloquy Addons</p>
									</td>
									<td class="soliloquy-admin-litevspro-lite-column">
										<p class="features-none">
											<strong>No Addons Included </strong>
										</p>
									</td>
									<td class="soliloquy-admin-litevspro-pro-column">
										<p class="features-full">
											<strong> All Addons Included</strong>WooCommerce, Carousel,
											Lightbox, Schedule, PDF Slider, Thumbnail Navigation, Defaults
											Addon and more
										</p>
									</td>
								</tr>
								<tr class="soliloquy-admin-columns">
									<td class="soliloquy-admin-litevspro-first-column">
										<p>Customer Support </p>
									</td>
									<td class="soliloquy-admin-litevspro-lite-column">
										<p class="features-none">
											<strong>Limited Customer Support</strong>
										</p>
									</td>
									<td class="soliloquy-admin-litevspro-pro-column">
										<p class="features-full">
											<strong> Priority Customer Support</strong>Dedicated prompt
											service via email from our top tier support team. Your request is
											assigned the highest priority
										</p>
									</td>
								</tr>

							</tbody>
						</table>

					</div>

					<div class="soliloquy-admin-litevspro-section soliloquy-admin-litevspro-section-hero">
						<div class="soliloquy-admin-about-section-hero-main no-border">
							<h3 class="call-to-action">
								<a class="button soliloquy-button soliloquy-primary-button"
									href="<?php echo Soliloquy_Common_Admin_Lite::get_instance()->get_upgrade_link( false, 'litevsprotab', 'getsoliloquyprotoday' ); ?>"
									target="_blank" rel="noopener noreferrer">Get Soliloquy Pro Today and
									Unlock all the Powerful Features!</a>
							</h3>

							<p>
								<strong>Bonus:</strong> Soliloquy Lite users get <span
									class="soliloquy-deal 20-percent-off">special discount</span>, using the
								code in the link above.
							</p>
						</div>
					</div>

				</div>

			</div>

		</div>

	</div> <!-- wrap -->


	<?php
	}

	public function partners_page() {

		self::tab_navigation( __METHOD__ );

		$this->installed_plugins = get_plugins();

		 ?>

	<div class="soliloquy-welcome-wrap soliloquy-help">

		<div class="soliloquy-get-started-main">

			<div class="soliloquy-get-started-panel">

				<div class="wraps upgrade-wrap">

					<h2 class="headline-title"><?php esc_html_e( 'See Our Partners!', 'soliloquy' ); ?></h2>

					<h4 class="headline-subtitle">
						<?php esc_html_e( 'We have partnered with these amazing companies for further enhancement to your Soliloquy experience.', 'soliloquy' ); ?>
					</h4>
					<div class="lionsher-partners-wrap">
						<?php foreach( $this->get_partners() as $partner ) :

							$this->get_plugin_card( $partner );

						endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php

	}

	public function get_plugin_card( $plugin = false ) {

		if ( ! $plugin ) {
			return;
		}
		$this->installed_plugins = get_plugins();

		if ( ! isset( $this->installed_plugins[ $plugin['basename'] ] ) ) { ?>
	<div class="lionsher-partners">
		<div class="lionsher-partners-main">
			<div>
				<img src="<?php esc_attr_e( $plugin['icon'], 'soliloquy-lite' ); ?>" width="64px" />
			</div>
			<div>
				<h3><?php esc_html_e( $plugin['name'], 'soliloquy-lite' ); ?></h3>
				<p class="lionsher-partner-excerpt"><?php esc_html_e( $plugin['description'], 'pdf-embedder' ); ?>
				</p>
			</div>
		</div>
		<div class="lionsher-partners-footer">
			<div class="lionsher-partner-status">Status:&nbsp;<span>Not Installed</span></div>
			<div class="lionsher-partners-install-wrap">
				<a href="#" target="_blank"
					class="button button-primary lionsher-partners-button lionsher-partners-install"
					data-url="<?php echo $plugin['url']; ?>"
					data-basename="<?php echo $plugin['basename']; ?>">Install Plugin</a>
				<span class="spinner lionsher-gallery-spinner"></span>
			</div>
		</div>
	</div>
	<?php } else {
			if ( is_plugin_active( $plugin['basename'] ) ) { ?>
	<div class="lionsher-partners">
		<div class="lionsher-partners-main">
			<div>
				<img src="<?php esc_attr_e( $plugin['icon'], 'pdf-embedder' ); ?>" width="64px" />
			</div>
			<div>
				<h3><?php esc_html_e( $plugin['name'], 'pdf-embedder' ); ?></h3>
				<p class="lionsher-partner-excerpt"><?php esc_html_e( $plugin['description'], 'pdf-embedder' ); ?>
				</p>
			</div>
		</div>
		<div class="lionsher-partners-footer">
			<div class="lionsher-partner-status">Status:&nbsp;<span>Active</span></div>
			<div class="lionsher-partners-install-wrap">
				<a href="#" target="_blank"
					class="button button-primary lionsher-partners-button lionsher-partners-deactivate"
					data-url="<?php echo $plugin['url']; ?>"
					data-basename="<?php echo $plugin['basename']; ?>">Deactivate</a>
				<span class="spinner lionsher-gallery-spinner"></span>
			</div>
		</div>
	</div>
	<?php } else { ?>
	<div class="lionsher-partners">
		<div class="lionsher-partners-main">
			<div>
				<img src="<?php esc_attr_e( $plugin['icon'], 'pdf-embedder' ); ?>" width="64px" />
			</div>
			<div>
				<h3><?php esc_html_e( $plugin['name'], 'pdf-embedder' ); ?></h3>
				<p class="lionsher-partner-excerpt"><?php esc_html_e( $plugin['description'], 'pdf-embedder' ); ?>
				</p>
			</div>
		</div>
		<div class="lionsher-partners-footer">
			<div class="lionsher-partner-status">Status:&nbsp;<span>Inactive</span></div>
			<div class="lionsher-partners-install-wrap">
				<a href="#" target="_blank"
					class="button button-primary lionsher-partners-button lionsher-partners-activate"
					data-url="<?php echo $plugin['url']; ?>"
					data-basename="<?php echo $plugin['basename']; ?>">Activate</a>
				<span class="spinner lionsher-gallery-spinner"></span>
			</div>
		</div>
	</div>
	<?php }
		}
	}

	/**
	 * Return true/false based on whether a query argument is set.
	 *
	 * @return bool
	 */
	public static function is_new_install() {

		if ( get_transient( '_soliloquy_is_new_install' ) ) {
			delete_transient( '_soliloquy_is_new_install' );
			return true;
		}

		if ( isset( $_GET['is_new_install'] ) && 'true' === strtolower( sanitize_text_field( wp_unslash( $_GET['is_new_install'] ) ) ) ) { // @codingStandardsIgnoreLine
			return true;
		} elseif ( isset( $_GET['is_new_install'] ) ) { // @codingStandardsIgnoreLine
			return false;
		}

	}

	/**
	 * Return a user-friendly version-number string, for use in translations.
	 *
	 * @since 2.2.0
	 *
	 * @return string
	 */
	public static function display_version() {

		return SOLILOQUY_VERSION;

	}


}


$soliloquy_welcome = new Soliloquy_Welcome;