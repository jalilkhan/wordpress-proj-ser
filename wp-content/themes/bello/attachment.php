<?php
get_header();

the_post();

$image_size = apply_filters( 'wporg_attachment_size', 'large' ); 
BoldThemesFrameworkTemplate::$content_html = apply_filters( 'the_content', wp_get_attachment_image( get_the_ID(), $image_size ) . get_the_content() );
BoldThemesFrameworkTemplate::$content_html = str_replace( ']]>', ']]&gt;', BoldThemesFrameworkTemplate::$content_html );

echo '<article class="btPostSingleItemStandard gutter">';
        echo '<div class="port">';
                echo '<div class="btArticleContentHolder">';
                        echo '<div class="btArticleMedia">';
                                echo BoldThemesFrameworkTemplate::$content_html;
                        echo '</div><!-- /btArticleMedia -->';
                echo '</div><!-- /btContent -->' ;
        echo '</div><!-- /port -->';	
echo '</article>';


get_footer(); 