<?php
/**
 * WooCommerce Compatibility
 */
if ( ! class_exists( 'Zita_Storefront_Woocommerce_Ext' ) ) :
    /**
     * Zita_Storefront_Woocommerce_Ext
     *
     * @since 1.0.0
     */
    class Zita_Storefront_Woocommerce_Ext{
         /**
         * Member Variable
         *
         * @var object instance
         */
        private static $instance;

        /**
         * Initiator
         */
        public static function get_instance() {
            if ( ! isset( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }
       /**
         * Constructor
         */
        public function __construct(){
         /* Shop page view list and grid view.
         */
         add_action('wp_enqueue_scripts', array($this, 'zita_storefront_enq'));


         add_action( 'woocommerce_before_shop_loop', array($this,'zita_storefront_shop_content_start'),1);
         add_action( 'woocommerce_after_shop_loop', array($this,'zita_storefront_shop_content_end'),1);

         add_action('woocommerce_before_shop_loop', array($this, 'zita_storefront_before_shop_loop'), 35);
         add_action('zita_woo_shop_add_to_cart_before', array($this, 'zita_storefront_list_after_shop_loop_item'),40);

        }  

                  /**
                   * Thumbnail wrap start.
                   */
                  function zita_storefront_shop_content_start(){
                    
                    echo '<div id="shop-product-wrap">';
                  }
              

               

                  /**
                   * Thumbnail wrap start.
                   */
                  function zita_storefront_shop_content_end(){
                    
                    echo '</div>';
                  }
               
        public function zita_storefront_enq(){
        $themeVersion = wp_get_theme()->get('Version');
        // Enqueue our style.css with our own version
        wp_enqueue_style('zita-storefront-styles', get_template_directory_uri() . '/style.css',array(), $themeVersion);
        wp_add_inline_style( 'zita-storefront-styles', $this->zita_storefront_custom_style());
        wp_enqueue_script('zita-storefront-custom-script', get_stylesheet_directory_uri() . '/js/custom.js',array(), $themeVersion,true);
        }


        function zita_storefront_before_shop_loop(){
        echo '<div class="zita-list-grid-switcher">';
        echo '<a title="' . esc_attr__('Grid View', 'zita-storefront') . '" href="#" data-type="grid" class="zita-grid-view selected"><i class="fa fa-th"></i></a>';

        echo '<a title="' . esc_attr__('List View', 'zita-storefront') . '" href="#" data-type="list" class="zita-list-view"><i class="fa fa-bars"></i></a>';

        echo '</div>';
        }
        // shop page content
        function zita_storefront_list_after_shop_loop_item(){
        if (is_shop()) { ?>
           <div class="shop-zita-product-excerpt"><?php the_excerpt(); ?></div>
        <?php     }
        }

      public  function zita_storefront_custom_style(){
        $zita_storefront_style=""; 
        $zita_storefront_theme_clr     = esc_html(get_theme_mod('zita_theme_clr','#006799'));  
        $zita_storefront_style.=" .zita-list-grid-switcher a.selected, .zita-list-grid-switcher a:hover{
                                background:{$zita_storefront_theme_clr}!important;
                                border: 1px solid {$zita_storefront_theme_clr}!important;
                            }";
        return $zita_storefront_style;
       }
       

    }
endif;  
Zita_Storefront_Woocommerce_Ext::get_instance();

function zita_storefront_setup(){
        // Add support for Custom Background.
        $args = array(
        'default-color' => '4c4040',
        );
        add_theme_support( 'custom-background',$args );
    }
add_action( 'after_setup_theme', 'zita_storefront_setup' );
   /**
 *Sidebar Function for Popular Business Theme
 */
if ( ! function_exists( 'zita_sidebar_layout' ) ){
function zita_sidebar_layout($page_post_meta_set='default', $default='right'){
    $default_layout = get_theme_mod('zita_sidebar_default_layout', $default );
    $page_layout = get_theme_mod('zita_sidebar_page_layout','default' );
    $blog_layout = get_theme_mod('zita_sidebar_blog_layout','no-sidebar');
    $archive_layout = get_theme_mod('zita_sidebar_archive_layout','default' );
    $woo_layout = get_theme_mod('zita_sidebar_woo_layout','default' );
    $singleproduct_layout = get_theme_mod('zita_single_sidebar_disable',true);
    $layout='';
if($page_post_meta_set=='default' || $page_post_meta_set==''){
    if((is_home()) && ($blog_layout!=='default')){
       $layout = $blog_layout;
    }
    elseif(is_page() && $page_layout!=='default'){
     $layout = $page_layout;
    }
    elseif((is_single() || is_archive()) && (class_exists( 'WooCommerce' ) && !is_woocommerce() && !is_product()) && $archive_layout!=='default'){
        $layout = $archive_layout;
    }
    elseif((class_exists( 'WooCommerce' )) && (is_woocommerce() || is_checkout() || is_cart() || is_account_page()) && $woo_layout!=='default'){
        $layout = $woo_layout;
    }
    elseif((class_exists( 'WooCommerce' )) && (is_product()) && ($singleproduct_layout ==true)){
    $layout = 'no-sidebar';
    }
    else{
    $layout = $default_layout;
    }   
    return apply_filters( 'zita_sidebar_layout', $layout, $default ); 
  }else{
   if(is_home() && $blog_layout!=='default'){
       $layout = $blog_layout;
    }
   elseif(is_page()){
       $layout = $page_post_meta_set;
    }
   elseif((is_single() || is_archive())){
       $layout = $page_post_meta_set;
    } 
   elseif((class_exists( 'WooCommerce' )) && (is_woocommerce() || is_checkout() || is_cart() || is_account_page())){
       $layout = $page_post_meta_set;
    }
   
   else{
       $layout = $default_layout;
    }
    return apply_filters( 'zita_sidebar_layout',$layout); 
   }
 }
}
//customizer
function zita_storefront_blog( $wp_customize ){
define('ZITASTOREFRONT_BLOG_LAYOUT', get_stylesheet_directory_uri() . "/images/zitastorefrontblog.png");
$wp_customize->add_setting(
            'zita_blog_layout', array(
                'default'           => 'zta-blog-layout-1',
                'sanitize_callback' => 'zita_shop_sanitize_radio',
            )
        );
$wp_customize->add_control(
            new Zita_WP_Customize_Control_Radio_Image(
                $wp_customize, 'zita_blog_layout', array(
                    'label'    => esc_html__( 'Blog Layout', 'zita-storefront' ),
                    'section'  => 'zita-blog-archive',
                    'choices'  => array(   
                        'zta-blog-layout-1' => array(
                            'url' => esc_url(ZITA_BLOG_ARCHIVE_LAYOUT_1),
                        ),
                        'zita-storefront-blog-layout' => array(
                            'url' => esc_url(ZITASTOREFRONT_BLOG_LAYOUT),
                        ),
                        
                    ),
                    'priority'   =>1,
                )
            )
);

$wp_customize->add_setting('zita_sidebar_blog_layout', array(
        'default'        => 'no-sidebar',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'zita_sanitize_select',
    ));
$wp_customize->add_control( 'zita_sidebar_blog_layout', array(
        'settings' => 'zita_sidebar_blog_layout',
        'label'   => __('Blog Layout','zita-storefront'),
        'section' => 'zita-section-sidebar-group',
        'type'    => 'select',
        'choices'    => array(
        'default'   => __('Default','zita-storefront'),
        'no-sidebar'   => __('No Sidebar','zita-storefront'),
        'left' => __('Left Sidebar','zita-storefront'),
        'right'=> __('Right Sidebar','zita-storefront'),    
        ),
        'priority'   => 4,
));
}
add_action( 'customize_register', 'zita_storefront_blog', 100 );