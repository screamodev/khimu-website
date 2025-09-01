<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Kava
 */
?>
<?php
$lang = function_exists('pll_current_language') ? pll_current_language() : 'uk';

if ($lang === 'uk') {
	wp_redirect(home_url('/404-ua'));
} else {
	wp_redirect(home_url('/404-en'));
}
exit;