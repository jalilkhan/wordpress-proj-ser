<?php
// supported social networks - slugs
// facebook, twitter, linkedin, googleplus, instagram, vkontakte, pinterest, yelp
// youtube, yahoo, wordpress, wikipedia, whatsapp, wechat, vine, vimeo, tumblr, foursquare
// tripadvisor, stumbleupon, soundcloud, skype, snapchat, reddit, paypal, flickr, behance

if ( isset($field) ) {
	if ( isset($field['name']) && isset($field['value']) ) {
                if ($field['value'][0] != '') {
                    ?>
                    <div class="<?php echo $field['slug'] ?> <?php echo $field['group'];?>">
                            <a href="<?php echo $field['value'][0];?>" target="_blank"></a>
                    </div>
                    <?php
                }
	}
}