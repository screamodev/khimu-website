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
				<h2><?php esc_html_e( 'General Plugin Settings', 'accessibility-onetap' ); ?></h2>
				<p><?php esc_html_e( 'Adjust the general settings for optimal performance of the plugin. Customize colors, select your preferred language, and configure other options to tailor the plugin to your needs. These settings ensure a personalized and accessible experience.', 'accessibility-onetap' ); ?></p>

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
				<a class="button" href="#anchorDesign">
					<?php esc_html_e( 'Design', 'accessibility-onetap' ); ?>
				</a>
			</li>
			<li>
				<a class="button" href="#anchorColors">
					<?php esc_html_e( 'Colors', 'accessibility-onetap' ); ?>
				</a>
			</li>
			<li>
				<a class="button" href="#anchorPosition">
					<?php esc_html_e( 'Position', 'accessibility-onetap' ); ?>
				</a>
			</li>
			<li>
				<a class="button" href="#anchorLanguage">
					<?php esc_html_e( 'Language', 'accessibility-onetap' ); ?>
				</a>
			</li>			
		</ul>
	</section>
	<?php $this->settings_api->show_forms(); ?>
</div>