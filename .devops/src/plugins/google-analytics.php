<?php
/*
Plugin Name: Simple Google Analytics Plugin
Plugin URI: http://rachelmccollin.com
Description: Adds a Google analytics tracking code to the <head> of your theme, by hooking to wp_head.
Author: Rachel McCollin
Version: 1.0
 */
function wpmudev_google_analytics() { 
    if ( strpos(home_url(), '//tynt.io') !== false ): ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-82257054-1', 'auto');
  ga('send', 'pageview');

</script>
<?php elseif( strpos(home_url(), '//stage.tynt.io') !== false ): ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-82257054-2', 'auto');
  ga('send', 'pageview');

</script>
<?php endif;
}
add_action( 'wp_head', 'wpmudev_google_analytics', 10 );