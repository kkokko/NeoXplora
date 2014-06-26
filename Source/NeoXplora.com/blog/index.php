<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */

/* CUSTOM CHANGE BY DVS START */
include '../includes/blog_config.php';

/* CUSTOM CHANGE BY DVS END */

define('WP_USE_THEMES', true);

/** Loads the WordPress Environment and Template */
require( dirname( __FILE__ ) . '/wp-blog-header.php' );

/* CUSTOM CHANGE BY DVS START */
include_once '../includes/footer.php';

/* CUSTOM CHANGE BY DVS END */