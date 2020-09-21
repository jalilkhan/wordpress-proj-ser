<?php
if( ! class_exists( 'BT_Taxonomy_Images' ) ) {
        class BT_Taxonomy_Images {

          public function __construct() {
              $taxonomy_name = 'listing-category';
          }

          public function init() {
              add_action( 'listing-category_add_form_fields', array( $this, 'add_category_image' ), 10, 2 );
              add_action( 'created_listing-category', array( $this, 'save_category_image' ), 10, 2 );
              add_action( 'listing-category_edit_form_fields', array( $this, 'update_category_image' ), 10, 2 );
              add_action( 'edited_listing-category', array( $this, 'updated_category_image' ), 10, 2 );
              add_action( 'admin_enqueue_scripts', array( $this, 'load_media' ) );
              add_action( 'admin_footer', array( $this, 'add_script' ) );
         }

         public function load_media() {
           if( ! isset( $_GET['taxonomy'] ) || $_GET['taxonomy'] != 'listing-category' ) {
             return;
           }
           wp_enqueue_media();
         }

         /**
          * Add a form field in the new category page
          */  
         public function add_category_image( $taxonomy ) { ?>
           <div class="form-field term-group">
             <label for="showcase-taxonomy-image-id"><?php _e( 'Normal Image', 'bt_plugin' ); ?></label>
             <input type="hidden" id="showcase-taxonomy-image-id" name="showcase-taxonomy-image-id" class="custom_media_url" value="">
             <div id="category-image-wrapper"></div>
             <p>
               <input type="button" class="button button-secondary showcase_tax_media_button" id="showcase_tax_media_button" name="showcase_tax_media_button" value="<?php _e( 'Add Image', 'bt_plugin' ); ?>" />
               <input type="button" class="button button-secondary showcase_tax_media_remove" id="showcase_tax_media_remove" name="showcase_tax_media_remove" value="<?php _e( 'Remove Image', 'bt_plugin' ); ?>" />
             </p>
				<p class="description"><?php _e( 'Set a custom map pin that will be used on Listing Search for this Category only.','bt_plugin' ); ?></p>
			</p>

           </div>

            <div class="form-field term-group">
             <label for="showcase-taxonomy-selected-image-id"><?php _e( 'Selected Image', 'bt_plugin' ); ?></label>
             <input type="hidden" id="showcase-taxonomy-selected-image-id" name="showcase-taxonomy-selected-image-id" class="custom_media_url" value="">
             <div id="category-image-wrapper-selected"></div>
             <p>
               <input type="button" class="button button-secondary showcase_tax_media_button_selected" id="showcase_tax_media_button_selected" name="showcase_tax_media_button_selected" value="<?php _e( 'Add Image', 'bt_plugin' ); ?>" />
               <input type="button" class="button button-secondary showcase_tax_media_remove_selected" id="showcase_tax_media_remove_selected" name="showcase_tax_media_remove_selected" value="<?php _e( 'Remove Image', 'bt_plugin' ); ?>" />
				<p class="description"><?php _e( 'Set a custom image for a map pin that will be used for a Listing when it\'s clicked on a map, and on Single Listing Page, for this Category only.','bt_plugin' ); ?></p>
             </p>

           </div>
         <?php }

         /**
          * Save the form field
          */
         public function save_category_image( $term_id, $tt_id ) {
           if( isset( $_POST['showcase-taxonomy-image-id'] ) && '' !== $_POST['showcase-taxonomy-image-id'] ){
             add_term_meta( $term_id, 'showcase-taxonomy-image-id', absint( $_POST['showcase-taxonomy-image-id'] ), true );
           }
           if( isset( $_POST['showcase-taxonomy-selected-image-id'] ) && '' !== $_POST['showcase-taxonomy-selected-image-id'] ){
             add_term_meta( $term_id, 'showcase-taxonomy-selected-image-id', absint( $_POST['showcase-taxonomy-selected-image-id'] ), true );
           }
          }

          /**
           * Edit the form field
           */
          public function update_category_image( $term, $taxonomy ) { ?>
            <tr class="form-field term-group-wrap">
              <th scope="row">
                <label for="showcase-taxonomy-image-id"><?php _e( 'Normal Image', 'bt_plugin' ); ?></label>
              </th>
              <td>
                <?php $image_id = get_term_meta( $term->term_id, 'showcase-taxonomy-image-id', true ); ?>
                <input type="hidden" id="showcase-taxonomy-image-id" name="showcase-taxonomy-image-id" value="<?php echo esc_attr( $image_id ); ?>">
                <div id="category-image-wrapper">
                  <?php if( $image_id ) { ?>
                    <?php echo wp_get_attachment_image( $image_id, 'thumbnail' ); ?>
                  <?php } ?>
                </div>
                <p>
                  <input type="button" class="button button-secondary showcase_tax_media_button" id="showcase_tax_media_button" name="showcase_tax_media_button" value="<?php _e( 'Add Image', 'bt_plugin' ); ?>" />
                  <input type="button" class="button button-secondary showcase_tax_media_remove" id="showcase_tax_media_remove" name="showcase_tax_media_remove" value="<?php _e( 'Remove Image', 'bt_plugin' ); ?>" />
                </p>
				<p class="description"><?php _e( 'Set a custom map pin that will be used on Listing Search for this Category only.','bt_plugin' ); ?></p>
              </td>
            </tr>
            
             <tr class="form-field term-group-wrap">
              <th scope="row">
                <label for="showcase-taxonomy-selected-image-id"><?php _e( 'Selected Image', 'bt_plugin' ); ?></label>
              </th>
              <td>
                <?php $image_id = get_term_meta( $term->term_id, 'showcase-taxonomy-selected-image-id', true ); ?>
                <input type="hidden" id="showcase-taxonomy-selected-image-id" name="showcase-taxonomy-selected-image-id" value="<?php echo esc_attr( $image_id ); ?>">
                <div id="category-image-wrapper-selected">
                  <?php if( $image_id ) { ?>
                    <?php echo wp_get_attachment_image( $image_id, 'thumbnail' ); ?>
                  <?php } ?>
                </div>
                <p>
                  <input type="button" class="button button-secondary showcase_tax_media_button_selected" id="showcase_tax_media_button_selected" name="showcase_tax_media_button_selected" value="<?php _e( 'Add Image', 'bt_plugin' ); ?>" />
                  <input type="button" class="button button-secondary showcase_tax_media_remove_selected" id="showcase_tax_media_remove_selected" name="showcase_tax_media_remove_selected" value="<?php _e( 'Remove Image', 'bt_plugin' ); ?>" />
                </p>
				<p class="description"><?php _e( 'Set a custom image for a map pin that will be used for a Listing when it\'s clicked on a map, and on Single Listing Page, for this Category only.','bt_plugin' ); ?></p>
              </td>
            </tr>
         <?php }

         /**
          * Update the form field value
          */
         public function updated_category_image( $term_id, $tt_id ) {
           if( isset( $_POST['showcase-taxonomy-image-id'] ) && '' !== $_POST['showcase-taxonomy-image-id'] ){
             update_term_meta( $term_id, 'showcase-taxonomy-image-id', absint( $_POST['showcase-taxonomy-image-id'] ) );
           } else {
             update_term_meta( $term_id, 'showcase-taxonomy-image-id', '' );
           }
           
           if( isset( $_POST['showcase-taxonomy-selected-image-id'] ) && '' !== $_POST['showcase-taxonomy-selected-image-id'] ){
             update_term_meta( $term_id, 'showcase-taxonomy-selected-image-id', absint( $_POST['showcase-taxonomy-selected-image-id'] ) );
           } else {
             update_term_meta( $term_id, 'showcase-taxonomy-selected-image-id', '' );
           }
         }

         /**
          * Enqueue styles and scripts
          */
         public function add_script() {
           if( ! isset( $_GET['taxonomy'] ) || $_GET['taxonomy'] != 'listing-category' ) {
             return;
           } ?>
            
           <script> jQuery(document).ready( function($) {
             _wpMediaViewsL10n.insertIntoPost = '<?php _e( "Insert", "bt_plugin" ); ?>';
             
            function ct_media_upload(button_class) {
               var _custom_media = true, _orig_send_attachment = wp.media.editor.send.attachment;
               $('body').on('click', button_class, function(e) {                   
                 var button_id = '#'+$(this).attr('id');
                 console.log(button_id);
                 var send_attachment_bkp = wp.media.editor.send.attachment;
                 var button = $(button_id);
                 _custom_media = true;
                 wp.media.editor.send.attachment = function(props, attachment){
                   if( _custom_media ) {
                     $('#showcase-taxonomy-image-id').val(attachment.id);
                     $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                     $( '#category-image-wrapper .custom_media_image' ).attr( 'src',attachment.url ).css( 'display','block' );
                   } else {
                     return _orig_send_attachment.apply( button_id, [props, attachment] );
                   }
                 }
                 wp.media.editor.open(button); return false;
               });
             }
             
             function ct_media_upload_selected(button_class) {
               var _custom_media = true, _orig_send_attachment = wp.media.editor.send.attachment;
               $('body').on('click', button_class, function(e) {
                 var button_id = '#'+$(this).attr('id');
                 console.log(button_id);
                 var send_attachment_bkp = wp.media.editor.send.attachment;
                 var button = $(button_id);
                 _custom_media = true;
                 wp.media.editor.send.attachment = function(props, attachment){
                   if( _custom_media ) {
                     $('#showcase-taxonomy-selected-image-id').val(attachment.id);
                     $('#category-image-wrapper-selected').html('<img class="custom_media_image_selected" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                     $('#category-image-wrapper-selected .custom_media_image_selected' ).attr( 'src',attachment.url ).css( 'display','block' );
                   } else {
                     return _orig_send_attachment.apply( button_id, [props, attachment] );
                   }
                 }
                 wp.media.editor.open(button); return false;
               });
             }
             
             
             ct_media_upload('.showcase_tax_media_button.button');
             ct_media_upload_selected('.showcase_tax_media_button_selected.button');
             
             $('body').on('click','.showcase_tax_media_remove',function(){
               $('#showcase-taxonomy-image-id').val('');
               $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
             });
             
             $('body').on('click','.showcase_tax_media_remove_selected',function(){
               $('#showcase-taxonomy-selected-image-id').val('');
               $('#category-image-wrapper-selected').html('<img class="custom_media_image_selected" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
             });
             
             $(document).ajaxComplete(function(event, xhr, settings) {
               var queryStringArr = settings.data.split('&');
               if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
                 var xml = xhr.responseXML;
                 $response = $(xml).find('term_id').text();
                 if($response!=""){
                   // Clear the thumb image
                   $('#category-image-wrapper').html('');
                   $('#category-image-wrapper-selected').html('');
                 }
                }
              });
            });
          </script>
         <?php }
        }
      $BT_Taxonomy_Images = new BT_Taxonomy_Images();
      $BT_Taxonomy_Images->init(); 

}

