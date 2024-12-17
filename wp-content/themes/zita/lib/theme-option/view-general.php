<?php
/**
 * View General
 *
 * @package     Zita
 * @author      Zita
 * @copyright   Copyright (c) 2018, Zita
 * @since       Zita 1.0.0
 */
?>
<div class="zta-container zta-welcome">
		<div id="poststuff">
			<div id="post-body" class="columns-2">
					<div class="postbox-container zta-sidebar" id="postbox-container-1">
					<div id="side-sortables">
						<?php do_action( 'zita_welcome_page_right_sidebar_before' ); ?>
						<?php do_action( 'zita_welcome_page_right_sidebar_content' ); ?>
						<?php do_action( 'zita_welcome_page_right_sidebar_after' ); ?>
					</div>
				</div>
				<div id="post-body-content">
					<!-- All WordPress Notices below header -->
					<h1 class="screen-reader-text"><?php esc_html_e( 'Zita', 'zita' ); ?> </h1>
						<?php do_action( 'zita_welcome_page_content_before' ); ?>
						<?php do_action( 'zita_welcome_page_content' ); ?>
						<?php do_action( 'zita_welcome_page_content_after' ); ?>
				</div>
			
			</div>
			<!-- /post-body -->
			<br class="clear">
		</div>


</div>
