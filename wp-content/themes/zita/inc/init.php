<?php 
/**
 * all file includeed
 *
 * @param  
 * @return mixed|string
 */
get_template_part( 'inc/themes-hooks');
get_template_part('inc/class-zita-theme-options');
get_template_part('inc/admin-function');
get_template_part('customizer/customizer-constant');
get_template_part('inc/header-function');
get_template_part('inc/footer-function');
get_template_part('inc/blog-function');
get_template_part('inc/blog-single-function');
get_template_part('inc/default-string');
 //theme-option
get_template_part('lib/theme-option/class-zita-admin-settings');

 //pagination
get_template_part('inc/pagination/pagination');
get_template_part('inc/pagination/infinite-scroll');
 //customizer
get_template_part('customizer/extend-customizer/class-zita-wp-customize-panel');
get_template_part('customizer/extend-customizer/class-zita-wp-customize-section');
get_template_part('customizer/customizer-radio-image/class/class-zita-customize-control-radio-image');
get_template_part('customizer/customizer-range-value/class/class-zita-customizer-range-value-control');
get_template_part('customizer/color/class-control-color');
get_template_part('customizer/customize-buttonset/class-control-buttonset');
get_template_part('customizer/sortable/class-zita-control-sortable');
get_template_part('customizer/custom-customizer');
get_template_part('customizer/customizer');
//custom-style
get_template_part('inc/custom-style');
get_template_part('inc/sidebar-function');
get_template_part('inc/content-function');
//typography
get_template_part('customizer/customizer-font-selector/functions');
get_template_part('inc/typography-style');
//page meta box
get_template_part('lib/page-meta-box/zita-page-meta-box');
//woocommerce
get_template_part('inc/woocommerce/woocommerce-config');
get_template_part('inc/woocommerce/woocommerce-ajax');
// Probutton
/******************************/
get_template_part('customizer/pro-button/class-customize');