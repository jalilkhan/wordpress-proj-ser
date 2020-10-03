<?php
if ( isset($field) ) {   
	if ( isset($field['name']) && isset($field['value']) ) { 
                if ($field['value'][0] != '') {
                        if (  $field['slug'] == 'contact_phone' || $field['slug'] == 'contact_mobile' ){
                            $link = $field['value'][0] ? bt_format_phone_number( $field['value'][0] ) : '';
                            ?>
                                <div class="bt_bb_listing_text <?php echo $field['group'];?> <?php echo $field['slug'];?>">
                                        <span><a href="tel:<?php echo $link;?>"><?php echo $field['value'][0];?></a></span>
                                </div>
                            <?php 
                        } else if ( $field['slug'] == 'contact_whatsapp' ) {
                            $phone_number = preg_replace("/[^0-9]/", "", $field['value'][0]  );
                            $link = $field['value'][0] ? 'https://wa.me/'. $phone_number : '';
                             ?>
                                <div class="bt_bb_listing_text <?php echo $field['group'];?> <?php echo $field['slug'];?>">
                                        <span><a href="<?php echo $link;?>"><?php echo $field['value'][0];?></a></span>
                                </div>
                              <?php 
                        }else{
                            ?>
                                <div class="bt_bb_listing_text <?php echo $field['group'];?> <?php echo $field['slug'];?>">
                                        <span><?php echo $field['value'][0];?></span>
                                </div>
                            <?php
                        }
                }
	}
}


