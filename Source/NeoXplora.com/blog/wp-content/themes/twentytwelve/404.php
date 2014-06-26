<?php
/**
 * The template for displaying 404 pages (Not Found)
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

                <article id="post-0" class="post error404 no-results not-found">
                    <header class="entry-header">
                        <h1 class="entry-title"><?php _e('This is somewhat embarrassing, isn&rsquo;t it?', 'twentytwelve'); ?></h1>
                    </header>

                    <div class="entry-content">
                        <p><?php _e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'twentytwelve'); ?></p>
                        <?php get_search_form(); ?>
                    </div><!-- .entry-content -->
                </article><!-- #post-0 -->

            </div><!-- #content -->
        </div><!-- #primary -->

        <?php /* CUSTOM CHANGE BY DVS START */ ?>
    </div>
</div>
<?php
//get_footer(); 
/* CUSTOM CHANGE BY DVS END */
?>