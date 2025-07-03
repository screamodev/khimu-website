<?php
/**
 * Content template for submenu page.
 *
 * @package    Accessibility_Onetap
 * @since      1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="wrap">
	<!-- Header -->
	<header class="top">
		<div class="mycontainer">
			<div class="myrow myrow1">
				<div class="left">
					<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/logo.png' ); ?>" alt="<?php echo esc_attr__( 'logo', 'accessibility-onetap' ); ?>">
				</div>
				<div class="right">
					<a target="_blank" href="<?php echo esc_url( 'https://wponetap.com/support/' ); ?>" class="button">
						<?php esc_html_e( 'Support', 'accessibility-onetap' ); ?>
					</a>
				</div>
			</div>
			<div class="myrow myrow2">
				<h2><?php esc_html_e( 'Accessibility Settings', 'accessibility-onetap' ); ?></h2>
				<p class="desc"><?php esc_html_e( 'Customize your browsing experience by enabling or disabling various accessibility modules.', 'accessibility-onetap' ); ?>
					<br>
					<?php esc_html_e( 'For the best results, we recommend keeping all features turned on to maximize ease of use and inclusivity.', 'accessibility-onetap' ); ?>
				</p>
				<div class="box-button-cta">
					<a target="_blank" href="<?php echo esc_url( 'https://wponetap.com/pricing/' ); ?>" class="button button-primary">
						<?php esc_html_e( 'Get Pro Version', 'accessibility-onetap' ); ?>
					</a>
					<a target="_blank" href="<?php echo esc_url( 'https://wponetap.com/' ); ?>" class="button">
						<?php esc_html_e( 'See Whats Include', 'accessibility-onetap' ); ?>
					</a>
				</div>
			</div>
		</div>
	</header>
	<section class="box-button-navigation">
		<div class="separator"></div>
		<ul>
			<li>
				<a class="button" href="#anchorAccessibilityProfiles">
					<?php esc_html_e( 'Accessibility Profiles', 'accessibility-onetap' ); ?>
				</a>
			</li>
			<li>
				<a class="button" href="#anchorContentlModules">
					<?php esc_html_e( 'Content Modules', 'accessibility-onetap' ); ?>
				</a>
			</li>
			<li>
				<a class="button" href="#anchorModulesColors">
					<?php esc_html_e( 'Colors', 'accessibility-onetap' ); ?>
				</a>
			</li>
			<li>
				<a class="button" href="#anchorOrientation">
					<?php esc_html_e( 'Orientation', 'accessibility-onetap' ); ?>
				</a>
			</li>
		</ul>
	</section>
	<?php $this->settings_api->show_forms(); ?>
</div>