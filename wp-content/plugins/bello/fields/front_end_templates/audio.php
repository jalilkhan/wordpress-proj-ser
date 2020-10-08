<?php
$_html = '';
if ( isset($field) ) {
    if ( isset($field['name']) && isset($field['value']) ) {
        $audio     = $field['value'][0];
        if ($audio != '') {   
            $hw = 9 / 16;
            $_html .= '<div class="btArticleMedia"><div class="btMediaBox audio" data-hw="' . esc_attr( $hw ) . '">';
            
                if ( strpos( $audio, '</ifra' . 'me>' ) > 0 ) {
                    $_html .= '<div class="bt-audio-container">';
                    $_html .= $audio;
                    $_html .= '</div>';
                }else{ 
                   
                    if ( strpos( $audio, 'soundcloud' ) > 0 ) {
                        // Soundcloud url format: https://api.soundcloud.com/tracks/number
                        // Ex. https://api.soundcloud.com/tracks/88078067                        
                        $aa = 'https://w.soundcloud.com/player/?url='.$audio.'&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&visual=true';
                        $_html .= '<div class="bt-audio-container soundcloud">';
                            $_html .= '<iframe width="100%" height="166px" scrolling="no" frameborder="no" src="'.$aa.'"></iframe>';
                        $_html .= '</div>';
                    } else if( strpos( $audio, 'mixcloud' ) > 0 ){
                        // Mixcloud url format: https://www.mixcloud.com/artist/title
                        //ex. https://www.mixcloud.com/beatfusion/easy-to-dance-made-with-love-by-beatfusion/
                        $_html .= '<div class="bt-audio-container mixcloud">';
                            $_html .= '<iframe width="100%" height="120px" src="https://www.mixcloud.com/widget/iframe/?hide_cover=0&light=0&feed='.$audio.'"  scrolling="no" frameborder="no"></iframe>';
                        $_html .= '</div>';
                    }else{
                        $_html .= '<div class="bt-audio-container">';
                            $_html .= '<embed width="100%" height="50px" src="'.$audio.'" >';
                        $_html .= '</div>';
                    }
                }
            
            
            $_html .= '</div></div>';

        }
    }
}
return $_html;

// media_audio_1;Audio Mixcloud link;audio;MediaAudio

/*
Audio Soundcloud iframe
<iframe width="100%" height="450" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/88078067&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&visual=true"></iframe>

Audio Mixcloud iframe
<iframe width="100%" height="120" src="https://www.mixcloud.com/widget/iframe/?hide_cover=1&feed=%2Fchriscoco%2Fmelodica-25-june-2018%2F" frameborder="0" ></iframe>
 
Audio Soundcloud link
https://api.soundcloud.com/tracks/88078067

Audio Mixcloud link
https://www.mixcloud.com/beatfusion/easy-to-dance-made-with-love-by-beatfusion/

*/


