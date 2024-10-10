<?php
/**
 * Title: 404 pattern
 * Slug: greenleaf-blog/404-pattern
 * Categories: content
 */
?>

<!-- wp:group {"tagName":"main","style":{"spacing":{"padding":{"top":"var:preset|spacing|superbspacing-xxlarge","bottom":"var:preset|spacing|superbspacing-xxlarge","left":"var:preset|spacing|superbspacing-medium","right":"var:preset|spacing|superbspacing-medium"},"margin":{"bottom":"var:preset|spacing|superbspacing-medium"}}},"layout":{"type":"constrained"}} -->
<main class="wp-block-group" style="margin-bottom:var(--wp--preset--spacing--superbspacing-medium);padding-top:var(--wp--preset--spacing--superbspacing-xxlarge);padding-right:var(--wp--preset--spacing--superbspacing-medium);padding-bottom:var(--wp--preset--spacing--superbspacing-xxlarge);padding-left:var(--wp--preset--spacing--superbspacing-medium)"><!-- wp:spacer {"height":"var(\u002d\u002dwp\u002d\u002dpreset\u002d\u002dspacing\u002d\u002d30)"} -->
<div style="height:var(--wp--preset--spacing--30)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:heading {"textAlign":"center","level":1,"fontSize":"superbfont-xlarge"} -->
<h1 class="wp-block-heading has-text-align-center has-superbfont-xlarge-font-size"><?php esc_html_e('404 - Page Not Found','greenleaf-blog'); ?></h1>
<!-- /wp:heading -->

<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"5px"}}},"layout":{"type":"constrained","contentSize":"700px"}} -->
<div class="wp-block-group alignwide" style="margin-top:5px"><!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"top":"var:preset|spacing|superbspacing-xsmall","bottom":"var:preset|spacing|superbspacing-small"}},"elements":{"link":{"color":{"text":"var:preset|color|mono-2"}}}},"textColor":"mono-2"} -->
<p class="has-text-align-center has-mono-2-color has-text-color has-link-color" style="margin-top:var(--wp--preset--spacing--superbspacing-xsmall);margin-bottom:var(--wp--preset--spacing--superbspacing-small)"><?php esc_html_e('Search below to find what you are looking for.','greenleaf-blog'); ?></p>
<!-- /wp:paragraph -->

<!-- wp:template-part {"slug":"search-field-large","theme":"greenleaf-blog","area":"uncategorized"} /--></div>
<!-- /wp:group -->

<!-- wp:spacer {"height":"var(\u002d\u002dwp\u002d\u002dpreset\u002d\u002dspacing\u002d\u002d70)"} -->
<div style="height:var(--wp--preset--spacing--70)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></main>
<!-- /wp:group -->