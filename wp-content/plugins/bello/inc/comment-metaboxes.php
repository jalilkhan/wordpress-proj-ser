<?php
// Add Attachments for Listing Comments
if (!defined('ABSPATH')) { exit; }

if (!class_exists('BT_Comment_Attachment')){
    class BT_Comment_Attachment
    {
        private $adminPage      = 'discussion';
        private $adminCheckboxes;
        private $adminPrefix    = 'btCommentAttachment';
        private $key            = 'btCommentAttachment';
        private $settings;
        private $postType       = 'listing';
        
        public function __construct()
        {
            error_reporting(0);            
            if(!get_option($this->key)){ $this->initializeSettings(); }
            $this->settings = $this->getSavedSettings();
            $this->defineConstants();
            add_action('plugins_loaded', array($this, 'loaded'));
            add_action('init', array($this, 'init'));
            add_action('admin_init', array($this, 'adminInit'));
        }
        
        public function loaded()
        {
            if(isset($_GET['deleteAtt']) && ($_GET['deleteAtt'] == '1')){
                if((isset($_GET['c'])) && is_numeric($_GET['c'])){
                    BT_Comment_Attachment::deleteAttachment($_GET['c']);
                    delete_comment_meta($_GET['c'], 'bt_attachment_id');
                    add_action('admin_notices', function(){
                        echo "<div class='updated'><p>".__('Comment Attachment deleted.','bt_plugin')."</p></div>";
                    });
                }
            }
        }

        public function init()
        {
            add_filter('preprocess_comment',            array($this, 'checkAttachment'), 10, 1);
            add_action('comment_form_top',              array($this, 'displayBeforeForm'));
            add_action('comment_form_before_fields',    array($this, 'displayFormAttBefore'));
            add_action('comment_form_after_fields',     array($this, 'displayFormAttAfter'));
            add_action('comment_form_logged_in_after',  array($this, 'displayFormAtt'));
            add_filter('comment_text',                  array($this, 'displayAttachment'), 10, 3);
            add_action('comment_post',                  array($this, 'saveAttachment'), 10, 2);
            add_action('delete_comment',                array($this, 'deleteAttachment'));
            add_filter('upload_mimes',                  array($this, 'getAllowedUploadMimes'), 10, 1);
            add_filter('comment_notification_text',     array($this, 'notificationText'), 10, 2);
        }

        public function adminInit()
        {
            $this->setUserNag();
            add_filter('plugin_action_links', array($this, 'displayPluginActionLink'), 10, 2);
            add_filter('comment_row_actions', array($this, 'addCommentActionLinks'), 10, 2);
            register_setting($this->adminPage, $this->key, array($this, 'validateSettings'));
            add_settings_section($this->adminPrefix,           __('Listing Comment Attachments','bt_plugin'), array($this, 'bt_setting_section_callback_function'), $this->adminPage);
            add_settings_section($this->adminPrefix . 'Types', __('Listing Comment Attachments Allowed File Types','bt_plugin'), '', $this->adminPage);
            foreach ($this->getSettings() as $id => $setting){
                $setting['id'] = $id;
                $this->createSetting($setting);
            }
        }
        
        public function bt_setting_section_callback_function( $arg ) {
            if ( $this->getMaximumUploadExecutionTime() < 300 ) {
                echo '<p><strong style="color:red;">Please Note:</strong><br />'
                        . sprintf(__('Your server currently allows Maximum Execution Time of <strong>%s (s).</strong>','bt_plugin'),$this->getMaximumUploadExecutionTime())
                        . '<br />During upload of Comment Attachments, You may receive a message such as “Maximum execution time of NN seconds exceeded”.'
                        . '<br />This means that it is taking to longer for a upload process to complete and it is timing out. '
                        . '<br />To fix this error, You can increase Maximum Execution Time for Your WordPress site (to 300 seconds for example) by</p>'
                        . '<ul>'
                        . '<li>- adding the following to wp-config.php: <strong>set_time_limit(300)</strong> or</li>'
                        . '<li>- adding the following to .htaccess: <strong>php_value max_execution_time 300</strong> or</li>'
                        . '<li>- adding the following to php.ini: <strong>max_execution_time = 300</strong></li>'
                        . '</ul>'
                        . '<p>If You are unable to increase maximum execution time, please consult Your server administrator.</p>';
            }else{
		echo '<p><strong style="color:red;">Please Note:</strong><br />'
                        . sprintf(__('Your server currently allows Maximum Execution Time of <strong>%s (s).</strong>','bt_plugin'),$this->getMaximumUploadExecutionTime())
			. '</p>';
            }
        }
        
        public function getSettings() {
            $setts[$this->adminPrefix . 'Enabled'] = array(
                'section' => $this->adminPrefix,
                'title'   => __(' Listing Comments Attachments','bt_plugin'),
                'desc'    => 'Enable Attachments for Listing Comments',
                'type'    => 'checkbox',
                'std'     => 0
            );
            $setts[$this->adminPrefix . 'MaxNumber'] = array(
                'title'   => __('Maxium number of attachments to upload','bt_plugin'),
                'desc'    => __('Maxium number of Attachments to upload','bt_plugin'),
                'std'     => '16',
                'type'    => 'number',
                'section' => $this->adminPrefix
            );
            $setts[$this->adminPrefix . 'MaxAttachment'] = array(
                'title'   => __('Maxium number of Attachments in comments view','bt_plugin'),
                'desc'    => __('Maxium number of Attachments in comments view, not in gallery','bt_plugin'),
                'std'     => '4',
                'type'    => 'number',
                'section' => $this->adminPrefix
            );
            $setts[$this->adminPrefix . 'MaxSize'] = array(
                'title'   => __('Maxium file size <small>(in megabytes)</small>','bt_plugin'),
                'desc'    => sprintf(__('Your server currently allows us to use maximum of <strong>%s MB(s).</strong>','bt_plugin'),$this->getMaximumUploadFileSize()),
                'std'     => $this->getMaximumUploadFileSize(),
                'type'    => 'number',
                'section' => $this->adminPrefix
            ); 
            $setts[$this->adminPrefix . 'ThumbTitle'] = array(
                'title'   => __('Text before attachment in a commment admin view','bt_plugin'),
                'desc'    => '',
                'std'     => __('Attachments','bt_plugin'),
                'type'    => 'text',
                'section' => $this->adminPrefix
            );
            $setts[$this->adminPrefix . 'Delete'] = array(
                'section' => $this->adminPrefix,
                'title'   => __('Delete attachment upon comment deletition?<br />','bt_plugin'),
                'desc'    => '',
                'type'    => 'checkbox',
                'std'     => 1
            );
            $setts[$this->adminPrefix . 'JPG']  = array('section' => $this->adminPrefix . 'Types', 'title' => 'JPG', 'type' => 'checkbox', 'std' => 1);
            $setts[$this->adminPrefix . 'GIF']  = array('section' => $this->adminPrefix . 'Types', 'title' => 'GIF', 'type' => 'checkbox', 'std' => 1);
            $setts[$this->adminPrefix . 'PNG']  = array('section' => $this->adminPrefix . 'Types', 'title' => 'PNG', 'type' => 'checkbox', 'std' => 1);
            return $setts;
        }

        private function getSavedSettings(){ 
            
            return get_option($this->key);             
        }
        
        public static function getMaximumUploadExecutionTime()
        {
            $executionTime      = (int)(ini_get('max_execution_time'));    
            return $executionTime;
        }

        public static function getMaximumUploadFileSize()
        {
            $maxUpload      = (int)(ini_get('upload_max_filesize'));
            $maxPost        = (int)(ini_get('post_max_size'));
            $memoryLimit    = (int)(ini_get('memory_limit'));           
            return min($maxUpload, $maxPost, $memoryLimit);
        }

        private function defineConstants()
        {
            define('BT_ATT_ENABLED',   ($this->settings[$this->adminPrefix . 'Enabled'] == '1' ? TRUE : FALSE));
            define('BT_ATT_REQ',   ($this->settings[$this->adminPrefix . 'Required'] == '1' ? TRUE : FALSE));
            define('BT_ATT_BIND',  ($this->settings[$this->adminPrefix . 'Bind'] == '1' ? TRUE : FALSE));
            define('BT_ATT_DEL',   ($this->settings[$this->adminPrefix . 'Delete'] == '1' ? TRUE : FALSE));
            define('BT_ATT_LINK',  ($this->settings[$this->adminPrefix . 'Link'] == '1' ? TRUE : FALSE));
            define('BT_ATT_THUMB', ($this->settings[$this->adminPrefix . 'Thumb'] == '1' ? TRUE : FALSE));
            define('BT_ATT_PLAY',  ($this->settings[$this->adminPrefix . 'Player'] == '1' ? TRUE : FALSE));
            define('BT_ATT_POS',   ($this->settings[$this->adminPrefix . 'Position']));
            define('BT_ATT_APOS',  ($this->settings[$this->adminPrefix . 'APosition']));
            define('BT_ATT_TITLE', ($this->settings[$this->adminPrefix . 'Title']));
            define('BT_ATT_TSIZE', ($this->settings[$this->adminPrefix . 'ThumbSize']));
            define('BT_ATT_MAX',   ($this->settings[$this->adminPrefix . 'MaxSize']));            
            define('BT_ATT_MAX_NUMBER',   ($this->settings[$this->adminPrefix . 'MaxNumber']));            
            define('BT_ATT_MAX_IMGS',   ($this->settings[$this->adminPrefix . 'MaxAttachment']));
            define( 'BT_ATT_MAX_EXECUTION_TIME' , ($this->settings[$this->adminPrefix . 'MaxExeTime']) );
        }
        
        private function defineConstants2()
        {
            $settings = get_option( $this->adminPrefix );
            define('BT_ATT_ENABLED',   ($settings[$this->adminPrefix . 'Enabled'] == '1' ? TRUE : FALSE));
            define('BT_ATT_REQ',   ($settings[$this->adminPrefix . 'Required'] == '1' ? TRUE : FALSE));
            define('BT_ATT_BIND',  ($settings[$this->adminPrefix . 'Bind'] == '1' ? TRUE : FALSE));
            define('BT_ATT_DEL',   ($settings[$this->adminPrefix . 'Delete'] == '1' ? TRUE : FALSE));
            define('BT_ATT_LINK',  ($settings[$this->adminPrefix . 'Link'] == '1' ? TRUE : FALSE));
            define('BT_ATT_THUMB', ($settings[$this->adminPrefix . 'Thumb'] == '1' ? TRUE : FALSE));
            define('BT_ATT_PLAY',  ($settings[$this->adminPrefix . 'Player'] == '1' ? TRUE : FALSE));
            define('BT_ATT_POS',   ($settings[$this->adminPrefix . 'Position']));
            define('BT_ATT_APOS',  ($settings[$this->adminPrefix . 'APosition']));
            define('BT_ATT_TITLE', ($settings[$this->adminPrefix . 'Title']));
            define('BT_ATT_TSIZE', ($settings[$this->adminPrefix . 'ThumbSize']));
            define('BT_ATT_MAX',   ($settings[$this->adminPrefix . 'MaxSize']));            
            define('BT_ATT_MAX_NUMBER',         ($settings[$this->adminPrefix . 'MaxNumber']));            
            define('BT_ATT_MAX_IMGS',           ($settings[$this->adminPrefix . 'MaxAttachment']));
            define( 'BT_ATT_MAX_EXECUTION_TIME' , ($settings[$this->adminPrefix . 'MaxExeTime']) );
        }

        private function getRegisteredImageSizes()
        {
            foreach(get_intermediate_image_sizes() as $size){
                $arr[$size] = ucfirst($size);
            };
            return $arr;
        }

        public function getAllowedFileExtensions()
        {
            $return = array();
            $pluginFileTypes = $this->getMimeTypes();
            foreach($this->settings as $key => $value){
                if(array_key_exists($key, $pluginFileTypes)){
                    $return[] = strtolower(str_replace($this->adminPrefix, '', $key));
                }
            }
            return $return;
        }

        public function getAllowedMimeTypes()
        {
            $return = array();
            $pluginFileTypes = $this->getMimeTypes();
            foreach($this->settings as $key => $value){
                if(array_key_exists($key, $pluginFileTypes)){
                    if(!function_exists('finfo_file') || !function_exists('mime_content_type')){
                        if(($key == $this->adminPrefix . 'DOCX') || ($key == $this->adminPrefix . 'DOC') || ($key == $this->adminPrefix . 'PDF') ||
                            ($key == $this->adminPrefix . 'ZIP') || ($key == $this->adminPrefix . 'RAR')){
                            $return[] = 'application/octet-stream';
                        }
                    }
                    if(is_array($pluginFileTypes[$key])){
                        foreach($pluginFileTypes[$key] as $fileType){
                            $return[] = $fileType;
                        }
                    } else {
                        $return[] = $pluginFileTypes[$key];
                    }
                }
            }
            return $return;
        }

        public function getAllowedUploadMimes($existing = array())
        {
            $return = array();
            $pluginFileTypes = $this->getMimeTypes();
            foreach($this->settings as $key => $value){
                if(array_key_exists($key, $pluginFileTypes)){
                    $keyCheck = strtolower(str_replace($this->adminPrefix,'', $key));
                    if(is_array($pluginFileTypes[$key])){
                        foreach($pluginFileTypes[$key] as $fileType){
                            $keyHacked = preg_replace("/[^0-9a-zA-Z ]/", "", $fileType);
                            $return[$keyCheck . '|' . $keyCheck . '_' . $keyHacked] = $fileType;
                        }
                    } else {
                        $return[$keyCheck] = $pluginFileTypes[$key];
                    }
                }
            }
            return array_merge($return, $existing);
        }

        public function displayAllowedFileTypes()
        {
            $fileTypesString = '';
            foreach($this->getAllowedFileExtensions() as $value){
                $fileTypesString .= $value . ', ';
            }
            return substr($fileTypesString, 0, -2);
        }

       
        public function displayBeforeForm()
        {
            $post_type = get_post_type( get_the_ID() );
            if ( $post_type == $this->postType && BT_ATT_ENABLED) {
                echo '</form><form action="'. get_home_url() .'/wp-comments-post.php" method="POST" enctype="multipart/form-data" id="attachmentForm" class="comment-form" novalidate>';
            }
        }

        public function displayFormAttBefore()  {  }
        public function displayFormAttAfter()   { $this->displayFormAtt(); }
        
        public function displayFormAtt()
        {
            $post_type = get_post_type( get_the_ID() );
            if ( $post_type == $this->postType && BT_ATT_ENABLED) {
                echo "<style>";
                echo ".comments-imgs-preview-hidden{display: none;}";
                echo "</style>";
                
                echo '<div id="app">' .
                    '<p class="comment-form-url comment-form-attachment">'.
                    '<span class="comment-upload-files"><label for="comments-attachment">'.__('Add images to your review','bt_plugin').'</label><input data-size="'.BT_ATT_MAX.'" data-number="'.BT_ATT_MAX_NUMBER.'" id="comments-attachment" name="attachment[]" type="file"  multiple  accept="image/*" /></span>'.
                    '<label for="attachment"><small class="attachmentRules">'.__('Allowed file types','bt_plugin').': <strong>'. $this->displayAllowedFileTypes() .'</strong>, '.__('max total size of files','bt_plugin').': <strong>'. BT_ATT_MAX .'MB</strong>, '.__('max number of files:','bt_plugin').': <strong>'. BT_ATT_MAX_NUMBER . '</strong>!</small></label></p>'.
                    '</div>' .
                    '<div class="comments-imgs-preview-container-title"></div><div class="comments-imgs-preview-container"></div>';
            }
        }

        public function checkAttachment( $commentdata )
        { 
           if ( isset($_FILES['attachment']) ){
                if ($_FILES['attachment']) {  
                    $files = $_FILES["attachment"];
                    if ( count($files['name']) > BT_ATT_MAX_NUMBER ){
                           wp_die(sprintf(__('<strong>ERROR:</strong> Max number of File(s) to upload is %1$s!<p><a href="javascript:history.back()">« Back</a></p>','bt_plugin'), BT_ATT_MAX_NUMBER));                                            
                  
                    }
                    $i = 0;
                    $attach_size = 0;
                    foreach ($files['name'] as $key => $value) { 			
                        if ($files['name'][$key]) { 
                            $file = array( 
                                    'name' => $files['name'][$key],
                                    'type' => $files['type'][$key], 
                                    'tmp_name' => $files['tmp_name'][$key], 
                                    'error' => $files['error'][$key],
                                    'size' => $files['size'][$key]
                            ); 
                            $_FILES2 = array ("attachment" => $file); 
                            
                            foreach ($_FILES2 as $file => $attach) {
                               
                                    if($attach['size'] > 0 && $attach['error'] == 0){

                                        $fileInfo = pathinfo($attach['name']);
                                        $fileExtension = strtolower($fileInfo['extension']);

                                        if(function_exists('finfo_file')){
                                            $fileType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $attach['tmp_name']);
                                        } elseif(function_exists('mime_content_type')) {
                                            $fileType = mime_content_type($attach['tmp_name']);
                                        } else {
                                            $fileType = $attach['type'];
                                        }
                                        
                                        $attach_size = intval($attach_size) + intval($attach['size']);
                                        
                                        if (!in_array($fileType, $this->getAllowedMimeTypes()) || !in_array(strtolower($fileExtension), $this->getAllowedFileExtensions())) { 
                                            wp_die(sprintf(__('<strong>ERROR:</strong> File(s) you upload must be valid file type <strong>(%1$s)</strong>!<p><a href="javascript:history.back()">« Back</a></p>','bt_plugin'),$this->displayAllowedFileTypes(),BT_ATT_MAX));
                                            
                                        }
                                        
                                        if ($attach_size > (BT_ATT_MAX * 1048576)) { 
                                            wp_die(sprintf(__('<strong>ERROR:</strong> Total size of File(s) to upload must be less than  %2$sMB!<p><a href="javascript:history.back()">« Back</a></p>','bt_plugin'),$this->displayAllowedFileTypes(),BT_ATT_MAX));                                            
                                        }

                                    } elseif (BT_ATT_REQ && $attach['error'] == 4) {
                                        wp_die(__('<strong>ERROR:</strong> Attachment is a required field!<p><a href="javascript:history.back()">« Back</a></p>','bt_plugin'));
                                    } elseif($attach['error'] == 1) {
                                        wp_die(__('<strong>ERROR:</strong> Attachment exceeds the upload_max_filesize directive in php.ini.<p><a href="javascript:history.back()">« Back</a></p>','bt_plugin'));
                                    } elseif($attach['error'] == 2) {
                                        wp_die(__('<strong>ERROR:</strong> Attachment exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.<p><a href="javascript:history.back()">« Back</a></p>','bt_plugin'));
                                    } elseif($attach['error'] == 3) {
                                        wp_die(__('<strong>ERROR:</strong> Attachment was only partially uploaded. Please try again later.<p><a href="javascript:history.back()">« Back</a></p>','bt_plugin'));
                                    } elseif($attach['error'] == 6) {
                                        wp_die(__('<strong>ERROR:</strong> Missing a temporary folder.<p><a href="javascript:history.back()">« Back</a></p>','bt_plugin'));
                                    } elseif($attach['error'] == 7) {
                                        wp_die(__('<strong>ERROR:</strong> Failed to write file to disk.<p><a href="javascript:history.back()">« Back</a></p>','bt_plugin'));
                                    } elseif($attach['error'] == 7) {
                                        wp_die(__('<strong>ERROR:</strong> A PHP extension stopped the file upload.<p><a href="javascript:history.back()">« Back</a></p>','bt_plugin'));
                                    }
                                    $i++;
                            }
                        } 
                    } 
                                       
                    if ( $attach_size > (BT_ATT_MAX * 1048576)) { 
                        wp_die(sprintf(__('<strong>ERROR:</strong> File(s) you upload must be valid file size under %2$sMB!<p><a href="javascript:history.back()">« Back</a></p>','bt_plugin'),BT_ATT_MAX));
                    }
                    
                }
           }  
            return $commentdata;
        }

        public function notificationText($notify_message,  $comment_id)
        {
            if(BT_Comment_Attachment::hasAttachment($comment_id)){
                $bt_attachment_id = get_comment_meta($comment_id, 'bt_attachment_id', TRUE);
                $attachmentName = basename(get_attached_file($bt_attachment_id));
                $notify_message .= __('Attachment:','bt_plugin') . "\r\n" .  $attachmentName . "\r\n\r\n";
            }
            return $notify_message;
        }

        public function insertAttachment($fileHandler, $postId)
        {
            try {
                 
                        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
                        require_once(ABSPATH . "wp-admin" . '/includes/media.php');
                        return media_handle_upload($fileHandler, $postId);
                    
            } catch ( Exception $e ) {
                    return new WP_Error( 'image_upload_error', $e->getMessage() );
            }  
        }
        
        private function time_sublimit($k = 0.90) {
            $limit = ini_get('max_execution_time'); 
            $sub_limit = round($limit * $k);
            if($sub_limit === 0) {
                $sub_limit = INF;
            }
            return $sub_limit;
        }

        public function saveAttachment($commentId, $commentApproved)
        {
            
            try {
                    if ( isset($_FILES['attachment']) ){
                        $t1 = time(); 
                        //$this->setExecutionTime();
                        if ($_FILES['attachment']) { 
                            $files = $_FILES["attachment"];                     
                            $i = 0;
                            foreach ($files['name'] as $key => $value) { 			
                                if ($files['name'][$key]) { 
                                    $file = array( 
                                            'name' => $files['name'][$key],
                                            'type' => $files['type'][$key], 
                                            'tmp_name' => $files['tmp_name'][$key], 
                                            'error' => $files['error'][$key],
                                            'size' => $files['size'][$key]
                                    ); 
                                    $_FILES = array ("attachment" => $file); 
                                    foreach ($_FILES as $file => $array) {
                                            $time_spent = time() - $t1;
                                            if($time_spent >= $this->time_sublimit()) {
                                                wp_die(sprintf(__('<strong>ERROR:</strong> Max Execution Time Limit is %2$ss!<p><a href="javascript:history.back()">« Back</a></p>','bt_plugin'),ini_get('max_execution_time')));
                                            }
                        
                                            $pid   = BT_ATT_BIND ? $_POST['comment_post_ID'] : 0;
                                            $attachId = $this->insertAttachment( $file, $pid);                                       
                                            if ( is_numeric($attachId) && $attachId > 0  ){
                                                add_comment_meta($commentId, 'bt_attachment_id', $attachId);
                                            }
                                            $i++;
                                    }
                                } 
                            }                     
                            unset($_FILES['attachment']);
                        }
                    } 
                    
            } catch ( Exception $e ) {
                    return new WP_Error( 'image_resize_error', $e->getMessage() );
            }  
        }
        
        public function setExecutionTime() {
            $max_execution_time = ini_get( 'max_execution_time' );
            $new_max_execution_time = BT_ATT_MAX_EXECUTION_TIME;
            
            if ( $max_execution_time < BT_ATT_MAX_EXECUTION_TIME ){
               $this-> bt_set_max_execution_time( BT_ATT_MAX_EXECUTION_TIME );
            }
            $max_execution_time = ini_get( 'max_execution_time' );
        }

        public function bt_set_max_execution_time( $max_execution_time ) {
          if ( ! ini_set('max_execution_time', $max_execution_time ) ) {
            throw new Exception( 'Unable to increase maximum execution time. Please consult your server administrator if this causes issues for you.' );
          }
        }

        
        private function reArrayFiles(&$file_post) {
            $file_ary = array();
            $file_count = count($file_post['name']);
            $file_keys = array_keys($file_post);
            for ($i=0; $i<$file_count; $i++) {
                foreach ($file_keys as $key) {
                    $file_ary[$i][$key] = $file_post[$key][$i];
                }
            }
            return $file_ary;
        }

        /*
         * $comment - comment text
         * $comment_object - comment object, bull for not displayng comment
         * https://developer.wordpress.org/reference/hooks/comment_text/
         */    
        public function displayAttachment($comment, $comment_object = null)
        {
            if (   null === $comment_object ) {//not displayng comment
                    return $comment;
            }
            $bt_attachment_ids = get_comment_meta(get_comment_ID(), 'bt_attachment_id', false);
            if ( !$bt_attachment_ids || !BT_ATT_ENABLED ){
                return $comment;
            }
            
            $contentBefore  = '<div class="attachmentFile"><p>' . $this->settings[$this->adminPrefix . 'ThumbTitle'] . ':</p><p> ';
            $contentInnerFinal = '';
            $contentAfter   = '</p><div class="clear clearfix"></div></div>';
                    
            foreach ( $bt_attachment_ids as $bt_attachment_id){
                if(is_numeric($bt_attachment_id) && !empty($bt_attachment_id)){

                    // atachement info
                    $attachmentLink = wp_get_attachment_url($bt_attachment_id);
                    $attachmentMeta = wp_get_attachment_metadata($bt_attachment_id);
                    $attachmentName = basename(get_attached_file($bt_attachment_id));
                    $attachmentType = get_post_mime_type($bt_attachment_id);
                    $attachmentRel  = '';
                    
                    if(is_admin()){
                        $contentInner = $attachmentName;
                    } else {
                       
                        if(BT_ATT_THUMB && in_array($attachmentType, $this->getImageMimeTypes())){
                            $attachmentRel = 'rel="lightbox"';
                            $contentInner = wp_get_attachment_image($bt_attachment_id, BT_ATT_TSIZE);
                            
                        } elseif (BT_ATT_PLAY && in_array($attachmentType, $this->getAudioMimeTypes())){
                            if(shortcode_exists('audio')){
                                $contentInner = do_shortcode('[audio src="'. $attachmentLink .'"]');
                            } else {
                                $contentInner = $attachmentName;
                            }
                           
                        } elseif (BT_ATT_PLAY && in_array($attachmentType, $this->getVideoMimeTypes())){
                            if(shortcode_exists('video')){
                                $contentInner .= do_shortcode('[video src="'. $attachmentLink .'"]');
                            } else {
                                $contentInner = $attachmentName;
                            }
                           
                        } else {
                            $contentInner = '&nbsp;<strong>' . $attachmentName . '</strong>';
                        }
                    }

                    
                    if(is_admin()){
                        $contentInnerFinal .= '<a '.$attachmentRel.' class="attachmentLink" target="_blank" href="'. $attachmentLink .'" title="Download: '. $attachmentName .'">';
                        $contentInnerFinal .= $contentInner;
                        $contentInnerFinal .= '</a>&nbsp;|&nbsp;';
                    } else {
                        if((BT_ATT_LINK) && !in_array($attachmentType, $this->getAudioMimeTypes()) && !in_array($attachmentType, $this->getVideoMimeTypes())){
                            $contentInnerFinal .= '<a '.$attachmentRel.' class="attachmentLink" target="_blank" href="'. $attachmentLink .'" title="Download: '. $attachmentName .'">';
                            $contentInnerFinal .= $contentInner;
                            $contentInnerFinal .= '</a>&nbsp;|&nbsp;';
                        } else {
                            $contentInnerFinal = $contentInner;
                        }
                    }
                }
            }
            $contentInsert = $contentBefore . $contentInnerFinal . $contentAfter;
            if(BT_ATT_APOS == 'before'){
                $comment = $contentInsert . $comment;
            } else{
                $comment = $comment . $contentInsert;
            }
            return $comment;
        }


        public function deleteAttachment($commentId)
        {
            $bt_attachment_id = get_comment_meta($commentId, 'bt_attachment_id', TRUE);
            if(is_numeric($bt_attachment_id) && !empty($bt_attachment_id) && BT_ATT_DEL){
                wp_delete_attachment($bt_attachment_id, TRUE);
            }
        }

        public static function hasAttachment($commentId)
        {
            $bt_attachment_id = get_comment_meta($commentId, 'bt_attachment_id', TRUE);
            if(is_numeric($bt_attachment_id) && !empty($bt_attachment_id)){
                return true;
            }
            return false;
        }


        public function addCommentActionLinks($actions, $comment)
        {
            
            if(BT_Comment_Attachment::hasAttachment($comment->comment_ID)){
                //$url = $_SERVER["SCRIPT_NAME"] . "?c=$comment->comment_ID&deleteAtt=1";
                $url = get_site_url(null, '/wp-admin/edit-comments.php', 'admin') . "?c=$comment->comment_ID&deleteAtt=1";
                $actions['deleteAtt'] = "<a href='$url' title='".esc_attr__('Delete Attachment','bt_plugin')."'>".__('Delete Attachment','bt_plugin').'</a>';
            }
            return $actions;
        }


        public function displayPluginActionLink($links, $file)
        {
            static $thisPlugin;
            if (!$thisPlugin){ $thisPlugin = plugin_basename(__FILE__); }
            if ($file == $thisPlugin){
                $settingsLink = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/options-discussion.php" title="'.__('Settings > Discussion > Comment Attachment','bt_plugin').'">'.__('Settings','bt_plugin').'</a>';
                array_push($links, $settingsLink);
            }
            return $links;
        }


        public static function validateSettings($input)
        {
            // attachment size check
            if($input['commentAttachmentMaxSize'] > BT_Comment_Attachment::getMaximumUploadFileSize()){
                add_settings_error('commentAttachment', 'commentAttachmentMaxSize', __('I\'m sorry, but we can\'t have attachment bigger than server allows us to. If you wish to change this and you don\'t know how, <a href="https://www.google.com/search?q=how+to+change+php.ini+upload_max_filesize" target="_blank">try this.</a>','bt_plugin'));
                $input['commentAttachmentMaxSize'] = BT_Comment_Attachment::getMaximumUploadFileSize();
            }
            return $input;
        }


        public function initializeSettings()
        {
            $default = array();
            foreach ($this->getSettings() as $id => $setting){
                if ($setting['type'] != 'heading')
                    $default[$id] = $setting['std'];
            }
            update_option($this->key, $default);
        }



        public function displaySetting($args = array())
        {
            extract($args);
            $options = get_option($this->key);
            if (! isset($options[$id]) && $type != 'checkbox')
                $options[$id] = $std;
            elseif (! isset($options[$id]))
                $options[$id] = 0;
            $field_class = '';
            if ($class != '')
                $field_class = ' ' . $class;
            switch ($type){
                case 'heading':
                    break;
                case 'checkbox':
                    echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="' . $this->key . '[' . $id . ']" value="1" ' . checked($options[$id], 1, false) . ' /> <label for="' . $id . '"><span class="description">' . $desc . '</span></label>';
                    break;
                case 'select':
                    echo '<select id="' . $id . '" class="select' . $field_class . '" name="' . $this->key . '[' . $id . ']">';
                    foreach ($choices as $value => $label)
                        echo '<option value="' . esc_attr($value) . '"' . selected($options[$id], $value, false) . '>' . $label . '</option>';
                    echo '</select>';
                    if ($desc != '')
                        echo '<br /><span class="description">' . $desc . '</span>';
                    break;
                case 'radio':
                    $i = 0;
                    foreach ($choices as $value => $label){
                        echo '<input class="radio' . $field_class . '" type="radio" name="' . $this->key . '[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr($value) . '" ' . checked($options[$id], $value, false) . '> <label for="' . $id . $i . '">' . $label . '</label>';
                        if ($i < count($options) - 1)
                            echo '<br />';
                        $i++;
                    }
                    if ($desc != '')
                        echo '<br /><span class="description">' . $desc . '</span>';
                    break;
                case 'textarea':
                    echo '<textarea class="' . $field_class . '" id="' . $id . '" name="' . $this->key . '[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre($options[$id]) . '</textarea>';
                    if ($desc != '')
                        echo '<br /><span class="description">' . $desc . '</span>';
                    break;
                case 'password':
                    echo '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="' . $this->key . '[' . $id . ']" value="' . esc_attr($options[$id]) . '" />';
                    if ($desc != '')
                        echo '<br /><span class="description">' . $desc . '</span>';
                    break;
                case 'text':
                case 'number':
                default:
                    echo '<input class="regular-text' . $field_class . '" type="'. $type .'" id="' . $id . '" name="' . $this->key . '[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr($options[$id]) . '" />';
                    if ($desc != '')
                        echo '<br /><span class="description">' . $desc . '</span>';
                    break;
            }
        }


        public function createSetting($args = array())
        {
            extract($args);
            $field_args = array(
                'type'      => isset($type) ? $type : NULL,
                'id'        => isset($id) ? $id : NULL,
                'desc'      => isset($desc) ? $desc : NULL,
                'std'       => isset($std) ? $std : NULL,
                'choices'   => isset($choices) ? $choices : NULL,
                'label_for' => isset($id) ? $id : NULL,
                'class'     => isset($class) ? $class : NULL
            );
            if ($type == 'checkbox'){ $this->adminCheckboxes[] = $id; }
            add_settings_field($id, $title, array($this, 'displaySetting'), $this->adminPage, $section, $field_args);
        }




        private function checkRequirements()
        {
            if (!function_exists('mime_content_type') && !function_exists('finfo_file')){
                add_action('admin_notices', array($this, 'displayFunctionMissingNotice'));
                return TRUE;
            }
            return TRUE;
        }


        public function displayFunctionMissingNotice()
        {
            $currentUser = wp_get_current_user();
            if (!get_user_meta($currentUser->ID, 'BT_Comment_AttachmentIgnoreNag') && current_user_can('install_plugins')){
                $this->displayAdminError((sprintf(
                    'It seems like your PHP installation is missing "mime_content_type" or "finfo_file" functions which are crucial '.
                    'for detecting file types of uploaded attachments. Please update your PHP installation OR be very careful with allowed file types, so '.
                    'intruders won\'t be able to upload dangerous code to your website! | <a href="%1$s">Hide Notice</a>', '?BT_Comment_AttachmentIgnoreNag=1')), 'updated');
            }
        }

        private function setUserNag()
        {
            $currentUser = wp_get_current_user();
            if (isset($_GET['BT_Comment_AttachmentIgnoreNag']) && '1' == $_GET['BT_Comment_AttachmentIgnoreNag'] && current_user_can('install_plugins')){
                add_user_meta($currentUser->ID, 'BT_Comment_AttachmentIgnoreNag', 'true', true);
            }
        }

        private function displayAdminError($error, $class="error") { echo '<div id="message" class="'. $class .'"><p><strong>' . $error . '</strong></p></div>';  }
        
        private function getMimeTypes()
        {
            return array(
                $this->adminPrefix . 'JPG' => array(
                    'image/jpeg',
                    'image/jpg',
                    'image/jp_',
                    'application/jpg',
                    'application/x-jpg',
                    'image/pjpeg',
                    'image/pipeg',
                    'image/vnd.swiftview-jpeg',
                    'image/x-xbitmap'),
                $this->adminPrefix . 'GIF' => array(
                    'image/gif',
                    'image/x-xbitmap',
                    'image/gi_'),
                $this->adminPrefix . 'PNG' => array(
                    'image/png',
                    'application/png',
                    'application/x-png'),
                $this->adminPrefix . 'DOCX'=> 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                $this->adminPrefix . 'RAR'=> 'application/x-rar-compressed',
                $this->adminPrefix . 'ZIP' => array(
                    'application/zip',
                    'application/x-zip',
                    'application/x-zip-compressed',
                    'application/x-compress',
                    'application/x-compressed',
                    'multipart/x-zip'),
                $this->adminPrefix . 'DOC' => array(
                    'application/msword',
                    'application/doc',
                    'application/text',
                    'application/vnd.msword',
                    'application/vnd.ms-word',
                    'application/winword',
                    'application/word',
                    'application/x-msw6',
                    'application/x-msword'),
                $this->adminPrefix . 'PDF' => array(
                    'application/pdf',
                    'application/x-pdf',
                    'application/acrobat',
                    'applications/vnd.pdf',
                    'text/pdf',
                    'text/x-pdf'),
                $this->adminPrefix . 'PPT' => array(
                    'application/vnd.ms-powerpoint',
                    'application/mspowerpoint',
                    'application/ms-powerpoint',
                    'application/mspowerpnt',
                    'application/vnd-mspowerpoint',
                    'application/powerpoint',
                    'application/x-powerpoint',
                    'application/x-m'),
                $this->adminPrefix . 'PPTX'=> 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                $this->adminPrefix . 'PPS' => 'application/vnd.ms-powerpoint',
                $this->adminPrefix . 'PPSX'=> 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
                $this->adminPrefix . 'ODT' => array(
                    'application/vnd.oasis.opendocument.text',
                    'application/x-vnd.oasis.opendocument.text'),
                $this->adminPrefix . 'XLS' => array(
                    'application/vnd.ms-excel',
                    'application/msexcel',
                    'application/x-msexcel',
                    'application/x-ms-excel',
                    'application/vnd.ms-excel',
                    'application/x-excel',
                    'application/x-dos_ms_excel',
                    'application/xls'),
                $this->adminPrefix . 'XLSX'=> 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                $this->adminPrefix . 'MP3' => array(
                    'audio/mpeg',
                    'audio/x-mpeg',
                    'audio/mp3',
                    'audio/x-mp3',
                    'audio/mpeg3',
                    'audio/x-mpeg3',
                    'audio/mpg',
                    'audio/x-mpg',
                    'audio/x-mpegaudio'),
                $this->adminPrefix . 'M4A' => 'audio/mp4a-latm',
                $this->adminPrefix . 'OGG' => array(
                    'audio/ogg',
                    'application/ogg'),
                $this->adminPrefix . 'WAV' => array(
                    'audio/wav',
                    'audio/x-wav',
                    'audio/wave',
                    'audio/x-pn-wav'),
                $this->adminPrefix . 'WMA' => 'audio/x-ms-wma',
                $this->adminPrefix . 'MP4' => array(
                    'video/mp4v-es',
                    'audio/mp4'),
                $this->adminPrefix . 'M4V' => array(
                    'video/mp4',
                    'video/x-m4v'),
                $this->adminPrefix . 'MOV' => array(
                    'video/quicktime',
                    'video/x-quicktime',
                    'image/mov',
                    'audio/aiff',
                    'audio/x-midi',
                    'audio/x-wav',
                    'video/avi'),
                $this->adminPrefix . 'WMV' => 'video/x-ms-wmv',
                $this->adminPrefix . 'AVI' => array(
                    'video/avi',
                    'video/msvideo',
                    'video/x-msvideo',
                    'image/avi',
                    'video/xmpg2',
                    'application/x-troff-msvideo',
                    'audio/aiff',
                    'audio/avi'),
                $this->adminPrefix . 'MPG' => array(
                    'video/avi',
                    'video/mpeg',
                    'video/mpg',
                    'video/x-mpg',
                    'video/mpeg2',
                    'application/x-pn-mpg',
                    'video/x-mpeg',
                    'video/x-mpeg2a',
                    'audio/mpeg',
                    'audio/x-mpeg',
                    'image/mpg'),
                $this->adminPrefix . 'OGV' => 'video/ogg',
                $this->adminPrefix . '3GP' => array(
                    'audio/3gpp',
                    'video/3gpp'),
                $this->adminPrefix . '3G2' => array(
                    'video/3gpp2',
                    'audio/3gpp2'),
                $this->adminPrefix . 'FLV' => 'video/x-flv',
                $this->adminPrefix . 'WEBM'=> 'video/webm',
                $this->adminPrefix . 'APK' => 'application/vnd.android.package-archive',
            );
        }
        
        public function getImageMimeTypes()
        {
            return array(
                'image/jpeg',
                'image/jpg',
                'image/jp_',
                'application/jpg',
                'application/x-jpg',
                'image/pjpeg',
                'image/pipeg',
                'image/vnd.swiftview-jpeg',
                'image/x-xbitmap',
                'image/gif',
                'image/x-xbitmap',
                'image/gi_',
                'image/png',
                'application/png',
                'application/x-png'
            );
        }

        public function getAudioMimeTypes()
        {
            return array(
                'audio/mpeg',
                'audio/x-mpeg',
                'audio/mp3',
                'audio/x-mp3',
                'audio/mpeg3',
                'audio/x-mpeg3',
                'audio/mpg',
                'audio/x-mpg',
                'audio/x-mpegaudio',
                'audio/mp4a-latm',
                'audio/ogg',
                'application/ogg',
                'audio/wav',
                'audio/x-wav',
                'audio/wave',
                'audio/x-pn-wav',
                'audio/x-ms-wma'
            );
        }

        public function getVideoMimeTypes()
        {
            return array(
                'video/mp4v-es',
                'audio/mp4',
                'video/mp4',
                'video/x-m4v',
                'video/quicktime',
                'video/x-quicktime',
                'image/mov',
                'audio/aiff',
                'audio/x-midi',
                'audio/x-wav',
                'video/avi',
                'video/x-ms-wmv',
                'video/avi',
                'video/msvideo',
                'video/x-msvideo',
                'image/avi',
                'video/xmpg2',
                'application/x-troff-msvideo',
                'audio/aiff',
                'audio/avi',
                'video/avi',
                'video/mpeg',
                'video/mpg',
                'video/x-mpg',
                'video/mpeg2',
                'application/x-pn-mpg',
                'video/x-mpeg',
                'video/x-mpeg2a',
                'audio/mpeg',
                'audio/x-mpeg',
                'image/mpg',
                'video/ogg',
                'audio/3gpp',
                'video/3gpp',
                'video/3gpp2',
                'audio/3gpp2',
                'video/x-flv',
                'video/webm',
            );
        }


        public static function getInstance()
        {
            if (!isset(static::$instance)) { static::$instance = new static; }
            return static::$instance;
        }

        protected function __clone(){}

    }
}

new BT_Comment_Attachment();
