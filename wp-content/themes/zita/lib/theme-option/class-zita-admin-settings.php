<?php
/**
 * Admin settings helper
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package     zita
 * @author      zita
 * @copyright   Copyright (c) 2018, Zita
 * @link        https://wpzita.com/
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ){
	exit;
}
/**
 * Add admin notice when active theme, just show one time
 *
 * @return bool|null
 */
add_action( 'admin_notices', 'zita_admin_notice' );
function zita_admin_notice() {
  global $current_user;
  $user_id   = $current_user->ID;
  $theme_data  = wp_get_theme();
  $zta_icon    = apply_filters( 'zita_page_top_icon', true );
  $zta_nme     = apply_filters( 'zita_welcome_page_notice_header_site_title','');
  if ( !get_user_meta( $user_id, esc_html( $theme_data->get( 'TextDomain' ) ) . '_notice_ignore' ) ) {
    ?>
    <div class="notice zita-notice">
      <h3>
        <?php
        if(!$zta_icon){
     
        	 printf( esc_html__( 'Thanks for choosing %1$s - Version %2$s', 'zita' ), esc_html($zta_nme ), esc_html( $theme_data->Version ) );
        }else{
        /* translators: %1$s: theme name, %2$s theme version */
        printf( esc_html__( 'Thanks for choosing %1$s - Version %2$s', 'zita' ), esc_html($theme_data->Name), esc_html( $theme_data->Version ) );
        }
        ?>
      </h3>
      <p>
        <?php
        if(!$zta_icon){
        printf( __( 'Visit our %1$s options page to take full advantage of theme.', 'zita' ), esc_html( $zta_nme ), esc_url( admin_url( 'themes.php?page=zita' ) ) );
        printf( '<a href="%1$s" class="notice-dismiss dashicons dashicons-dismiss dashicons-dismiss-icon"></a>', '?' . esc_html( $theme_data->get( 'TextDomain' ) ) . '_notice_ignore=0' );
        }else{
         /* translators: %1$s: theme name, %2$s link */
        printf( __( 'Visit our %1$s options page to take full advantage of theme.', 'zita' ), esc_html( $theme_data->Name ), esc_url( admin_url( 'themes.php?page=zita' ) ) );
        printf( '<a href="%1$s" class="notice-dismiss dashicons dashicons-dismiss dashicons-dismiss-icon"></a>', '?' . esc_html( $theme_data->get( 'TextDomain' ) ) . '_notice_ignore=0' );	
        }
        ?>
      </p>
      <p>
        <a href="<?php echo esc_url( admin_url( 'themes.php?page=zita' ) ) ?>" style="text-decoration: none;">
          <?php
          if(!$zta_icon){
          /* translators: %s theme name */
          printf( esc_html__( 'Get started with %s', 'zita' ), esc_html( $zta_nme ) );
          }else{
          /* translators: %s theme name */
          printf( esc_html__( 'Get started with %s', 'zita' ), esc_html( $theme_data->Name ) );
          }
          
          ?>
        </a>
      </p>
    </div>
    <?php
  }
}
add_action( 'admin_init', 'zita_notice_ignore' );
function zita_notice_ignore() {
  global $current_user;
  $theme_data  = wp_get_theme();
  $user_id   = $current_user->ID;
  /* If user clicks to ignore the notice, add that to their user meta */
  if ( isset( $_GET[ esc_html( $theme_data->get( 'TextDomain' ) ) . '_notice_ignore' ] ) && '0' == $_GET[ esc_html( $theme_data->get( 'TextDomain' ) ) . '_notice_ignore' ] ) {
    add_user_meta( $user_id, esc_html( $theme_data->get( 'TextDomain' ) ) . '_notice_ignore', 'true', true );
  }
}

/*****************************************/

if ( ! class_exists( 'Zita_Admin_Settings' ) ){
    /**
	 * Zita Admin Settings
	 */
	class Zita_Admin_Settings{

    /**
		 * View all actions
		 *
		 * @since 1.0
		 * @var array $view_actions
		 */
		static public $view_actions = array();

		/**
		 * Menu page title
		 *
		 * @since 1.0
		 * @var array $menu_page_title
		 */
		static public $menu_page_title = 'Zita Theme';

		/**
		 * Page title
		 *
		 * @since 1.0
		 * @var array $page_title
		 */
		static public $page_title = 'Zita';

		/**
		 * Plugin slug
		 *
		 * @since 1.0
		 * @var array $plugin_slug
		 */
		static public $plugin_slug = 'zita';

		/**
		 * Default Menu position
		 *
		 * @since 1.0
		 * @var array $default_menu_position
		 */
		static public $default_menu_position = 'themes.php';

		/**
		 * Parent Page Slug
		 *
		 * @since 1.0
		 * @var array $parent_page_slug
		 */
		static public $parent_page_slug = 'general';

		/**
		 * Current Slug
		 *
		 * @since 1.0
		 * @var array $current_slug
		 */
		static public $current_slug = 'general';

		/**
		 * Constructor
		 */
		function __construct() {

			if ( ! is_admin() ) {
				return;
			}

			add_action( 'after_setup_theme', __CLASS__ . '::init_admin_settings', 99 );
		}
        /**
		 * Admin settings init
		 */
		static public function init_admin_settings() {
			self::$menu_page_title = apply_filters( 'zita_menu_page_title', __( 'Zita Options', 'zita' ) );
			self::$page_title      = apply_filters( 'zita_page_title', __( 'Zita', 'zita' ) );

			if ( isset( $_REQUEST['page'] ) && strpos( $_REQUEST['page'], self::$plugin_slug ) !== false ) {
				add_action( 'admin_enqueue_scripts', __CLASS__ . '::styles_scripts' );
            
				
			}
			// Let extensions hook into saving.
				do_action( 'zita_admin_settings_scripts' );
				self::save_settings();
            add_action( 'admin_enqueue_scripts', __CLASS__ . '::admin_scripts' );
			add_action( 'admin_menu', __CLASS__ . '::add_admin_menu', 99 );

			add_action( 'zita_menu_general_action', __CLASS__ . '::general_page',99 );
			add_action( 'zita_header_right_section', __CLASS__ . '::top_header_right_section' );
			add_filter( 'admin_title', __CLASS__ . '::zita_admin_title', 10, 2 );
			add_action( 'zita_welcome_page_right_sidebar_content', __CLASS__ . '::zita_welcome_page_starter_sites_section', 10 );
			add_action( 'zita_welcome_page_right_sidebar_content', __CLASS__ . '::zita_welcome_pro_section', 12 );
			add_action( 'zita_welcome_page_right_sidebar_content', __CLASS__ . '::zita_welcome_page_knowledge_base_scetion', 12 );
			add_action( 'zita_welcome_page_right_sidebar_content', __CLASS__ . '::zita_welcome_page_community_scetion', 12 );
			add_action( 'zita_welcome_page_right_sidebar_content', __CLASS__ . '::zita_welcome_page_five_star_scetion', 13 );
			add_action( 'zita_welcome_page_content', __CLASS__ . '::zita_welcome_page_content' );
			// AJAX.
			add_action( 'wp_ajax_zita-sites-plugin-activate', __CLASS__ . '::required_plugin_activate' );

		}
		 /**
		 * View actions
		 */
		static public function get_view_actions() {

			if ( empty( self::$view_actions ) ) {

				$actions            = array(
					'general' => array(
						'label' => __( 'Welcome', 'zita' ),
						'show'  => ! is_network_admin(),
					),
				);
				self::$view_actions = apply_filters( 'zita_menu_options', $actions );
			}

			return self::$view_actions;
		}
        /**
		 * Save All admin settings here
		 */
		static public function save_settings() {

			// Only admins can save settings.
			if ( ! current_user_can( 'manage_options' ) ){
				return;
			}

			// Let extensions hook into saving.
			do_action( 'zita_admin_settings_save' );
		}

        /**
		 * Enqueues the needed CSS/JS for the builder's admin settings page.
		 *
		 * @since 1.0
		 */
		static public function styles_scripts(){
			// Styles.
			wp_enqueue_style( 'zita-admin-settings', ZITA_THEME_URI . 'lib/theme-option/assets/css/zita-admin-menu-settings.css', array(), ZITA_THEME_VERSION );
			// Script.
			wp_enqueue_script( 'zita-admin-settings', ZITA_THEME_URI . 'lib/theme-option/assets/js/zita-admin-menu-settings.js', array( 'jquery', 'wp-util', 'updates' ), ZITA_THEME_VERSION );

			$localize = array(
				'ajaxUrl'             => admin_url( 'admin-ajax.php' ),
				'btnActivating'       => __( 'Activating Importer Plugin ', 'zita' ) . '&hellip;',
				'zitaSitesLink'      => admin_url( 'themes.php?page=zita-site-library' ),
				'zitaSitesLinkTitle' => __( 'See Library', 'zita' ),
			);
			wp_localize_script( 'zita-admin-settings', 'zita', apply_filters( 'zita_theme_js_localize', $localize ) );
		}

		/**
		 * Enqueues the needed CSS/JS for Backend.
		 *
		 * @since 1.0
		 */
		static public function admin_scripts(){
			// Styles.
			wp_enqueue_style( 'zita-admin', ZITA_THEME_URI . 'lib/theme-option/assets/css/zita-admin.css', array(), ZITA_THEME_VERSION );

		}
        /**
		 * Add main menu
		 *
		 * @since 1.0
		 */
		static public function add_admin_menu() {

			$parent_page    = self::$default_menu_position;
			$page_title     = self::$menu_page_title;
			$capability     = 'manage_options';
			$page_menu_slug = self::$plugin_slug;
			$page_menu_func = __CLASS__ . '::menu_callback';

			if ( apply_filters( 'zita_dashboard_admin_menu', true ) ) {
				add_theme_page( $page_title, $page_title, $capability, $page_menu_slug, $page_menu_func );
			} else {
				do_action( 'zita_register_admin_menu', $parent_page, $page_title, $capability, $page_menu_slug, $page_menu_func );
			}
		}

        /**
		 * Menu callback
		 *
		 * @since 1.0
		 */
		static public function menu_callback() {

			$current_slug = isset( $_GET['action'] ) ? esc_attr( $_GET['action'] ) : self::$current_slug;

			$active_tab   = str_replace( '_', '-', $current_slug );
			$current_slug = str_replace( '-', '_', $current_slug );

			$ast_icon           = apply_filters( 'zita_page_top_icon', true );
			$ast_visit_site_url = apply_filters( 'zita_site_url', 'https://wpzita.com' );
			$ast_wrapper_class  = apply_filters( 'zita_welcome_wrapper_class', array( $current_slug ) );
			$my_theme = wp_get_theme();
			$zta_theme_version = $my_theme->get( 'Version' );
            
			?>
			<div class="zta-menu-page-wrapper wrap zta-clear <?php echo esc_attr( implode( ' ', $ast_wrapper_class ) ); ?>">
					<div class="zta-theme-page-header">
						<div class="zta-container zta-flex">
							<div class="zta-theme-title">
								<a href="<?php echo esc_url( $ast_visit_site_url ); ?>" target="_blank" rel="noopener" >
								<?php if ( $ast_icon ) { ?>
									<img src="<?php echo esc_url( ZITA_THEME_URI . 'lib/theme-option/assets/images/wpzita.png' ); ?>" class="zta-theme-icon" alt="<?php echo esc_attr( self::$page_title ); ?> " >
									<span class="zita-theme-version"><?php echo  esc_html($zta_theme_version); ?></span>
								<?php } ?>
								<?php do_action( 'zita_welcome_page_header_title' ); ?>
								</a>
							</div>
							<?php do_action( 'zita_header_right_section' ); ?>
						</div>
					</div>
				<?php do_action( 'zita_menu_' . esc_attr( $current_slug ) . '_action' ); ?>
			</div>
			<?php
		}
        /**
		 * Include general page
		 *
		 * @since 1.0
		 */
		static public function general_page(){
			get_template_part( 'lib/theme-option/view-general');
		}
        
        /**
		 * Include Welcome page right starter sites content
		 *
		 * @since 1.2.4
		 */
		static public function zita_welcome_page_starter_sites_section(){
			?>
			<div class="postbox">
				<h2 class="hndle zta-normal-cusror">
					<span class="dashicons dashicons-admin-customizer"></span>
					<span><?php echo esc_html( apply_filters( 'zita_sites_menu_page_title', __( 'Import Starter Site', 'zita' ) ) ); ?></span>
				</h2>
				<img class="zta-starter-sites-img" src="<?php echo esc_url( ZITA_THEME_URI . 'lib/theme-option/assets/images/zita-starter.png' ); ?>">
				<div class="inside">
					<p>
						<?php
							$zita_starter_sites_doc_link      = apply_filters( 'zita_starter_sites_documentation_link', zita_get_pro_url( 'https://wpzita.com/zita-site-library/', 'zita-dashboard', 'how-zita-sites-works', 'welcome-page' ) );
							$zita_starter_sites_doc_link_text = apply_filters( 'zita_starter_sites_doc_link_text', __( 'Site Library', 'zita' ) );
							printf(
								/* translators: %1$s: Starter site link. */
								esc_html__( 'Get amazing free websites with %1$s. As %1$s offers you ready to use  %2$s ', 'zita' ),
								self::$page_title,
								! empty( $zita_starter_sites_doc_link ) ? '<a href=' . esc_url( $zita_starter_sites_doc_link ) . ' target="_blank" rel="noopener">' . esc_html( $zita_starter_sites_doc_link_text ) . '</a>' :
								esc_html( $zita_starter_sites_doc_link_text )
							);
						?>
					</p>
					<p>
						<?php
							esc_html_e( 'Just one click and import your favorite site. Enjoy creating your project as you want.', 'zita' );
						?>
					</p>
						<?php
						// Sita Sites - Installed but Inactive.
						// Sita Premium Sites - Inactive.
						if ( file_exists( WP_PLUGIN_DIR . '/zita-site-library/zita-site-library.php' ) && is_plugin_inactive( 'zita-site-library/zita-site-library.php' )){

							$class       = 'button zta-sites-inactive';
							$button_text = __( 'Activate Importer Plugin', 'zita' );
							$data_slug   = 'zita-site-library';
							$data_init   = '/zita-site-library/zita-site-library.php';

							// Sita Sites - Not Installed.
							// Sita Premium Sites - Inactive.
						} elseif ( ! file_exists( WP_PLUGIN_DIR . '/zita-site-library/zita-site-library.php' ) ) {

							$class       = 'button zta-sites-notinstalled';
							$button_text = __( 'Install Importer Plugin', 'zita' );
							$data_slug   = 'zita-site-library';
							$data_init   = '/zita-site-library/zita-site-library.php';

						}
						else {
							$class       = 'active';
							$button_text = __( 'See Library', 'zita' );
							$link        = admin_url( 'themes.php?page=zita-site-library' );
						}

						printf(
							'<a class="ztabtn %1$s" %2$s %3$s %4$s> %5$s </a>',
							esc_attr( $class ),
							isset( $link ) ? 'href="' . esc_url( $link ) . '"' : '',
							isset( $data_slug ) ? 'data-slug="' . esc_attr( $data_slug ) . '"' : '',
							isset( $data_init ) ? 'data-init="' . esc_attr( $data_init ) . '"' : '',
							esc_html( $button_text )
						);
						?>
					<div>
					</div>
				</div>
			</div>

			<?php
		}
        /**
		 * Include Welcome page right side knowledge base content
		 *
		 * @since 1.2.4
		 */
		static public function zita_welcome_page_knowledge_base_scetion(){
			?>

			<div class="postbox">
				<h2 class="hndle zta-normal-cusror">
					<span class="dashicons dashicons-book"></span>
					<span><?php esc_html_e( 'Learn More', 'zita' ); ?></span>
				</h2>
				<div class="inside">
					<p>
						<?php
						printf(
							esc_html__( 'Want to know how it works, take a look on this and get whole knowledge about %1$s. Learn %1$s', 'zita' ),
							self::$page_title
						);
						?>
					</p>
					<?php
					$zita_knowledge_base_doc_link      = apply_filters( 'zita_knowledge_base_documentation_link', zita_get_pro_url( 'https://wpzita.com/docs/', 'zita-dashboard', 'visit-documentation', 'welcome-page' ) );
					$zita_knowledge_base_doc_link_text = apply_filters( 'zita_knowledge_base_documentation_link_text', __( 'Visit Us', 'zita' ) );

					printf(
						
						'%1$s',
						! empty( $zita_knowledge_base_doc_link ) ? '<a  class="ztabtn" href=' . esc_url( $zita_knowledge_base_doc_link ) . ' target="_blank" rel="noopener">' . esc_html( $zita_knowledge_base_doc_link_text ) . '</a>' :
						esc_html( $zita_knowledge_base_doc_link_text )
					);
					?>
				</div>
			</div>
			<?php
		}




		     /**
		 * Include Welcome page right side knowledge base content
		 *
		 * @since 1.2.4
		 */
		static public function zita_welcome_pro_section(){
			?>

			<div class="postbox">
				<h2 class="hndle zta-normal-cusror">
					<span class="dashicons dashicons-book"></span>
					<span><?php esc_html_e( 'Zita Premium 3.0', 'zita' ); ?></span>
				</h2>
				<div class="inside">
					<p>
						<?php
							echo esc_html__( 'Zita Pro is specially build for page builders. You can create stunning websites using easy drag & drop page builder addons.', 'zita' );
						?>
					</p>
					<?php
					$zita_knowledge_base_doc_link      = apply_filters( 'zita_knowledge_base_documentation_link', zita_get_pro_url( 'https://themehunk.com/zita-pro-wordpress-theme/', 'zita-dashboard', 'visit-documentation', 'welcome-page' ) );
					$zita_knowledge_base_doc_link_text = apply_filters( 'zita_knowledge_base_documentation_link_text', __( 'BUY NOW', 'zita' ) );

					printf(
						
						'%1$s',
						! empty( $zita_knowledge_base_doc_link ) ? '<a  class="ztabtn buy-pro" href=' . esc_url( $zita_knowledge_base_doc_link ) . ' target="_blank" rel="noopener">' . esc_html( $zita_knowledge_base_doc_link_text ) . '</a>' :
						esc_html( $zita_knowledge_base_doc_link_text )
					);
					?>
				</div>
			</div>
			<?php
		}

		/**
		 * Include Welcome page right side Zita community content
		 *
		 * @since 1.2.4
		 */
		static public function zita_welcome_page_community_scetion(){
			?>

			<div class="postbox">
				<h2 class="hndle zta-normal-cusror">
					<span class="dashicons dashicons-groups"></span>
					<span>
						<?php
						printf(
							/* translators: %1$s: Zita Theme name. */
							esc_html__( '%1$s Community', 'zita' ),
							self::$page_title
						);
						?>
				</h2>
				<div class="inside">
					<p>
						<?php
						printf(
							
							esc_html__( 'Join the community of generous %1$s users.  Get connected, share opinion, ask questions and Help each other!', 'zita' ),
							self::$page_title
						);
						?>
					</p>
					<?php
					$zita_community_group_link      = apply_filters( 'zita_community_group_link', 'https://www.facebook.com/groups/2098900630396072/' );
					$zita_community_group_link_text = apply_filters( 'zita_community_group_link_text', __( 'Join Our Facebook Group', 'zita' ) );

					printf(
						
						'%1$s',
						! empty( $zita_community_group_link ) ? '<a class="ztabtn" href=' . esc_url( $zita_community_group_link ) . ' target="_blank" rel="noopener">' . esc_html( $zita_community_group_link_text ) . '</a>' :
						esc_html( $zita_community_group_link_text )
					);
					?>
				</div>
			</div>
			<?php
		}
        
        /**
		 * Include Welcome page right side Five Star Support
		 *
		 * @since 1.2.4
		 */
		static public function zita_welcome_page_five_star_scetion(){
			?>
			<div class="postbox">
				<h2 class="hndle zta-normal-cusror">
					<span class="dashicons dashicons-sos"></span>
					<span><?php esc_html_e( 'Customer Support', 'zita' ); ?></span>
				</h2>
				<div class="inside">
					<p>
						<?php
						printf(
							
							esc_html__( 'We start with what the customer needs and we work backwards. You\'re absolutely free to contact us and %1$s team will be happy to help you.', 'zita' ),
							self::$page_title
						);
						?>
					</p>
					<?php
						$zita_support_link       = apply_filters( 'zita_support_link', zita_get_pro_url( 'https://themehunk.com/contact-us/', 'submit-a-ticket', 'welcome-page' ) );
						$zita_support_link_text  = apply_filters( 'zita_support_link_text', __( 'Submit a Ticket', 'zita' ) );

						printf(
							/* translators: %1$s: zita Knowledge doc link. */
							'%1$s',
							! empty( $zita_support_link ) ? '<a class="ztabtn" href=' . esc_url( $zita_support_link ) . ' target="_blank" rel="noopener">' . esc_html( $zita_support_link_text ) . '</a>' :
							esc_html( $zita_support_link_text )
						);
					?>
				</div>
			</div>
			<?php
		}

         
		/**
		 * Include Welcome page content
		 *
		 * @since 1.2.4
		 */
		static public function zita_welcome_page_content() {

			$zita_addon_tagline = apply_filters( 'zita_addon_list_tagline', __( 'Get More Options with Zita Pro!', 'zita' ) );
			$zta_visit_pro_feature_site_url = apply_filters( 'zita_pro_site_url', 'https://themehunk.com/zita-pro-wordpress-theme/' );
			// Quick settings.
			$quick_settings = apply_filters(
				'zita_quick_settings',
				array(
					'logo-favicon' => array(
						'title'     => __( 'Upload Logo', 'zita' ),
						'dashicon'  => 'dashicons-format-image',
						'quick_url' => admin_url( 'customize.php?autofocus[control]=custom_logo' ),
					),
					'colors'       => array(
						'title'     => __( 'Set Colors', 'zita' ),
						'dashicon'  => 'dashicons-admin-customizer',
						'quick_url' => admin_url( 'customize.php?autofocus[section]=zita-gloabal-color' ),
					),
					'typography'   => array(
						'title'     => __( 'Typography', 'zita' ),
						'dashicon'  => 'dashicons-editor-textcolor',
						'quick_url' => admin_url( 'customize.php?autofocus[panel]=zita-base-typography' ),
					),
					'layout'       => array(
						'title'     => __( 'Layout Options', 'zita' ),
						'dashicon'  => 'dashicons-layout',
						'quick_url' => admin_url( 'customize.php?autofocus[panel]=zita-panel-layout' ),
					),
					'header'       => array(
						'title'     => __( 'Header Options', 'zita' ),
						'dashicon'  => 'dashicons-align-center',
						'quick_url' => admin_url( 'customize.php?autofocus[section]=zita-section-header-group' ),
					),
					'blog-layout'  => array(
						'title'     => __( 'Blog Layouts', 'zita' ),
						'dashicon'  => 'dashicons-welcome-write-blog',
						'quick_url' => admin_url( 'customize.php?autofocus[section]=zita-section-blog-group' ),
					),
					'footer'       => array(
						'title'     => __( 'Footer Settings', 'zita' ),
						'dashicon'  => 'dashicons-admin-generic',
						'quick_url' => admin_url( 'customize.php?autofocus[section]=zita-section-footer-group' ),
					),
					'sidebars'     => array(
						'title'     => __( 'Sidebar Options', 'zita' ),
						'dashicon'  => 'dashicons-align-left',
						'quick_url' => admin_url( 'customize.php?autofocus[section]=zita-section-sidebar-group' ),
					),
					'preloader'     => array(
						'title'     => __( 'Pre Loader', 'zita' ),
						'dashicon'  => 'dashicons-image-rotate',
						'quick_url' => admin_url( 'customize.php?autofocus[section]=zita-pre-loader' ),
					),
					'social'     => array(
						'title'     => __( 'Social Icon', 'zita' ),
						'dashicon'  => 'dashicons-groups',
						'quick_url' => admin_url( 'customize.php?autofocus[section]=zita-social-icon' ),
					),
					'site-button'     => array(
						'title'     => __( 'Site Button', 'zita' ),
						'dashicon'  => 'dashicons-admin-post',
						'quick_url' => admin_url( 'customize.php?autofocus[section]=zita-site-button' ),
					),
					'woocommerce'     => array(
						'title'     => __( 'Shop', 'zita' ),
						'dashicon'  => 'dashicons-cart',
						'quick_url' => admin_url( 'customize.php?autofocus[panel]=woocommerce' ),
					),
					'scroll-to-top'     => array(
						'title'     => __( 'Scroll to top', 'zita' ),
						'dashicon'  => 'dashicons-arrow-up-alt',
						'quick_url' => admin_url( 'customize.php?autofocus[section]=zita-scroll-to-top-section' ),
					),
					'search'     => array(
						'title'     => __( 'Search', 'zita' ),
						'dashicon'  => 'dashicons-search',
						'quick_url' => admin_url( 'customize.php?autofocus[section]=zita-search' ),
					),
				)
			);
			?>
			<div class="postbox">
				<h2 class="hndle zta-normal-cusror"><span><?php esc_html_e( 'Visit to Customizer Settings:', 'zita' ); ?></span></h2>
					<div class="zta-quick-setting-section">
						<?php
						if ( ! empty( $quick_settings ) ) :
							?>
							<div class="zta-quick-links">
								<ul class="zta-flex">
									<?php
									foreach ( (array) $quick_settings as $key => $link ) {
										echo '<li class=""><span class="dashicons ' . esc_attr( $link['dashicon'] ) . '"></span><a class="ast-quick-setting-title" href="' . esc_url( $link['quick_url'] ) . '" target="_blank" rel="noopener">' . esc_html( $link['title'] ) . '</a></li>';
									}
									?>
								</ul>
							</div>
						<?php endif; ?>
					</div>
			</div>
  <?php if(zita_pro_activation_class()=='' ){?>      
			<div class="postbox zita-pro-link">
				<h2 class="hndle zta-normal-cusror zta-addon-heading zta-flex"><span><a href="<?php echo esc_url( $zta_visit_pro_feature_site_url ); ?>" target="_blank"><?php echo esc_html( $zita_addon_tagline ); ?></a></span>
					<?php do_action( 'zita_addon_bulk_action' ); ?>
				</h2>
			</div>
		<?php }else{ ?>

<!-- ********************************* -->
<!-- Addon feature zita-pro -->
<!-- *********************************-->
<?php 
$settings = Zita_Ext_White_Label_Markup::get_white_labels();
if($settings['zita-agency']['hide_branding']==''){
    $extensions = apply_filters(
				'zita_addon_list',
				array(
					'white-level' => array(
						'title'     => __( 'White Label', 'zita' ),
						'class'     => 'zta-addon',
						'title_url' => 'https://wpzita.com/docs/white-label/',
						'links'     => array(
							array(
								'link_class'   => 'ztabtn zta-learn-more',
								'link_url'     => esc_url( admin_url( 'themes.php?page=zita&action=white-label' ) ),
								'link_text'    => __( 'Setting', 'zita' ),
								'target_blank' => false,
							),
						),
					),
				)
			);
			?>
            <div class="postbox">
			
					<div class="zta-addon-list-section">
						<?php
						if ( ! empty( $extensions ) ) :
							?>
							<div>
								<h2 class="hndle zta-normal-cusror"><span><?php esc_html_e( 'White Label Setting:', 'zita' ); ?></span></h2>
								<ul class="zta-addon-list">
									<?php
									foreach ( (array) $extensions as $addon => $info ) {
										$title_url     = ( isset( $info['title_url'] ) && ! empty( $info['title_url'] ) ) ? 'href="' . esc_url( $info['title_url'] ) . '"' : '';
										$anchor_target = ( isset( $info['title_url'] ) && ! empty( $info['title_url'] ) ) ? "target='_blank' rel='noopener'" : '';

										echo '<li id="' . esc_attr( $addon ) . '"  class="' . esc_attr( $info['class'] ) . '"><a class="zta-addon-title"' . $title_url . $anchor_target . ' >' . esc_html( $info['title'] ) . '</a><div class="zta-addon-link-wrapper">';

										foreach ( $info['links'] as $key => $link ) {
											printf(
												'<a class="%1$s" %2$s %3$s> %4$s </a>',
												esc_attr( $link['link_class'] ),
												isset( $link['link_url'] ) ? 'href="' . esc_url( $link['link_url'] ) . '"' : '',
												( isset( $link['target_blank'] ) && $link['target_blank'] ) ? 'target="_blank" rel="noopener"' : '',
												esc_html( $link['link_text'] )
											);
										}
										echo '</div></li>';
									}
									?>
								</ul>
							</div>
						<?php endif; ?>
					</div>
			</div>
<!-- ********************************* -->
<!-- Addon feature zita-pro -->
<!-- *********************************-->
<?php } } ?>
			<?php
		}
        
		/**
		 * Update Admin Title.
		 *
		 * @since 1.0.19
		 *
		 * @param string $admin_title Admin Title.
		 * @param string $title Title.
		 * @return string
		 */
		static public function zita_admin_title( $admin_title, $title ) {

			$screen = get_current_screen();
			if ( 'appearance_page_zita' == $screen->id ) {

				$view_actions = self::get_view_actions();

				$current_slug = isset( $_GET['action'] ) ? esc_attr( $_GET['action'] ) : self::$current_slug;
				$active_tab   = str_replace( '_', '-', $current_slug );

				if ( 'general' != $active_tab && isset( $view_actions[ $active_tab ]['label'] ) ) {
					$admin_title = str_replace( $title, $view_actions[ $active_tab ]['label'], $admin_title );
				}
			}

			return $admin_title;
		}

        /**
		 * Zita Header Right Section Links
		 *
		 * @since 1.2.4
		 */
		static public function top_header_right_section(){

			$top_links = apply_filters(
				'zita_header_top_links',
				array(
					'zita-theme-info' => array(
						'title' => __( 'Easy to use, Fully Customizable, Unique options', 'zita' ),
					),
				)
			);

			if ( ! empty( $top_links ) ) {
				?>
				<div class="zta-top-links">
					<ul>
						<?php
						foreach ( (array) $top_links as $key => $info ) {
							/* translators: %1$s: Top Link URL wrapper, %2$s: Top Link URL, %3$s: Top Link URL target attribute */
							printf(
								'<li><%1$s %2$s %3$s > %4$s </%1$s>',
								isset( $info['url'] ) ? 'a' : 'span',
								isset( $info['url'] ) ? 'href="' . esc_url( $info['url'] ) . '"' : '',
								isset( $info['url'] ) ? 'target="_blank" rel="noopener"' : '',
								esc_html( $info['title'] )
							);
						}
						?>
						</ul>
					</div>
				<?php
			}
		}
       /**
		 * Required Plugin Activate
		 *
		 * @since 1.2.4
		 */
		static public function required_plugin_activate() {

			if ( ! current_user_can( 'install_plugins' ) || ! isset( $_POST['init'] ) || ! $_POST['init'] ) {
				wp_send_json_error(
					array(
						'success' => false,
						'message' => __( 'No plugin specified', 'zita' ),
					)
				);
			}

			$plugin_init = ( isset( $_POST['init'] ) ) ? esc_attr( $_POST['init'] ) : '';

			$activate = activate_plugin( $plugin_init, '', false, true );

			if ( is_wp_error( $activate ) ) {
				wp_send_json_error(
					array(
						'success' => false,
						'message' => $activate->get_error_message(),
					)
				);
			}

			wp_send_json_success(
				array(
					'success' => true,
					'message' => __( 'Plugin Successfully Activated', 'zita' ),
				)
			);

		}
	}
   new Zita_Admin_Settings;
}