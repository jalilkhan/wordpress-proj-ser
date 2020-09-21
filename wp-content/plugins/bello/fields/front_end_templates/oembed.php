<?php
$_html = '';
if ( isset($field) ) {
	if ( isset($field['name']) && isset($field['value']) ) {		
		$video		= $field['value'][0];
		$showinfo	= isset($field['showinfo']) ? $field['showinfo'] : 0;		
		
		$hw = 9 / 16;
					
		$_html .= '<div class="btArticleMedia"><div class="btMediaBox video" data-hw="' . esc_attr( $hw ) . '"><div class="bt-video-container">';
			if ( strpos( $video, 'vimeo.com/' ) > 0 ) {
				$video_id = substr( $video, strpos( $video, 'vimeo.com/' ) + 10 );
				$_html .= '<ifra' . 'me src="' . esc_url_raw( 'https://player.vimeo.com/video/' . $video_id ) . '" allowfullscreen></ifra' . 'me>';
			} else {
				$yt_id_pattern = '~(?:http|https|)(?::\/\/|)(?:www.|)(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=|\/ytscreeningroom\?v=|\/feeds\/api\/videos\/|\/user\S*[^\w\-\s]|\S*[^\w\-\s]))([\w\-]{11})[a-z0-9;:@#?&%=+\/\$_.-]*~i';
				$youtube_id = ( preg_replace( $yt_id_pattern, '$1', $video ) );
                                
				if ( strlen( $youtube_id ) == 11 ) {
					$_html .= '<ifra' . 'me width="560" height="315" src="' . esc_url_raw( 'https://www.youtube.com/embed/' . $youtube_id ) . '?showinfo=' . $showinfo . '" allowfullscreen ></ifra' . 'me>';
				} else {
					$_html .= '<div class="btMediaBox video" data-hw="' . esc_attr( $hw ) . '">';				
					$_html .= do_shortcode( $video );
				}
			}
		$_html .= '</div></div></div>';	
	}
}

return $_html;