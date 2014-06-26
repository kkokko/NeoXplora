<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
//get_header(); 
?>
<link href="<?php echo get_template_directory_uri(); ?>/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_template_directory_uri(); ?>/editor-style.css" rel="stylesheet" type="text/css" />
<!--[if lt IE 9]>
<link href="<?php echo get_template_directory_uri(); ?>/css/ie.css" rel="stylesheet" type="text/css" />
<![endif]-->

<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

<div id="content">
    <div class="container relative blog" style="background-color: #fff;">
        <?php /* CUSTOM CHANGE BY DVS END */ ?>
        <div id="primary" class="site-content">
            <div id="content" role="main">

                <?php while (have_posts()) : the_post(); ?>

                    <?php get_template_part('content', get_post_format()); ?>

                    <nav class="nav-single">
                        <h3 class="assistive-text"><?php _e('Post navigation', 'twentytwelve'); ?></h3>
                        <span class="nav-previous"><?php previous_post_link('%link', '<span class="meta-nav">' . _x('&larr;', 'Previous post link', 'twentytwelve') . '</span> %title'); ?></span>
                        <span class="nav-next"><?php next_post_link('%link', '%title <span class="meta-nav">' . _x('&rarr;', 'Next post link', 'twentytwelve') . '</span>'); ?></span>
                    </nav><!-- .nav-single -->

                    <?php comments_template('', true); ?>

                <?php endwhile; // end of the loop. ?>

            </div><!-- #content -->
        </div><!-- #primary -->

        <?php get_sidebar(); ?>
        <?php //get_footer(); ?>
        <?php /* CUSTOM CHANGE BY DVS START */ ?>
    </div>
</div>
<?php
/* CUSTOM CHANGE BY DVS END */
?>