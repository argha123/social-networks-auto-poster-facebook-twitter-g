<?php
/*
Plugin Name: Next Scripts Social Networks Auto-Poster
Plugin URI: http://www.nextscripts.com/social-networks-auto-poster-for-wordpress
Description: This plugin automatically publishes posts from your blog to your Facebook, Twitter, and Google+ profiles and/or pages.
Author: Next Scripts
Version: 1.5.1
Author URI: http://www.nextscripts.com
Copyright 2012  Next Scripts, Inc
*/
$php_version = (int)phpversion();
if (file_exists("apis/postToGooglePlus.php")) require "apis/postToGooglePlus.php";

define( 'NextScripts_SNAP_Version' , '1.5.1' );
if ( !function_exists('prr') ){ function prr($str) { echo "<pre>"; print_r($str); echo "</pre>\r\n"; }}        

//## Define class
if (!class_exists("NS_SNAutoPoster")) {
    class NS_SNAutoPoster {//## General Functions         
        //## Name for the DB Record for NS SNAP Options
        var $dbOptionsName = "NS_SNAutoPoster";        
        //## Constructor
        function NS_SNAutoPoster() { global $wp_version; $this->wp_version = $wp_version;}
        //## Initialization function
        function init() { $this->getAPOptions();}
        //## Administrative Functions
        //## Options loader function
        function getAPOptions($user_login = "") {
            //## Some Default Values
            $options = array('fbAttch'=>1,'gpAttch'=>1, 'gpMsgFormat'=>'New post has been published on %SITENAME%', 'fbMsgFormat'=>'New post has been published on %SITENAME%', 'twMsgFormat'=>'%TITLE% - %URL%');
            //## User's Options?
            if (empty($user_login))  $optionsAppend = ""; else  $optionsAppend = "_" . $user_login;
            //## Get values from the WP options table in the database, re-assign if found
            $dbOptions = get_option($this->dbOptionsName.$optionsAppend);
            if (!empty($dbOptions))  foreach ($dbOptions as $key => $option) $options[$key] = $option;            
            //## Update the options for the panel
            update_option($this->dbOptionsName . $optionsAppend, $options);
            return $options;
        }
        function showSNAutoPosterUsersOptionsPage($user_login = "") { global $current_user; get_currentuserinfo(); $this->showSNAutoPosterOptionsPage($current_user->user_login); }
        //## Print the admin page for the plugin
        function showSNAutoPosterOptionsPage($user_login = "") { $emptyUser = empty($user_login);            
            //## Get the user options
            $options = $this->getAPOptions($user_login);    
            if (isset($_POST['update_NS_SNAutoPoster_settings'])) { 
                if (isset($_POST['apDoGP']))   $options['doGP'] = $_POST['apDoGP'];
                if (isset($_POST['apDoFB']))   $options['doFB'] = $_POST['apDoFB'];
                if (isset($_POST['apDoTW']))   $options['doTW'] = $_POST['apDoTW'];
                
                
                if (isset($_POST['apGPUName']))   $options['gpUName'] = $_POST['apGPUName'];
                if (isset($_POST['apGPPass']))    $options['gpPass'] = $_POST['apGPPass'];                                
                if (isset($_POST['apGPPage']))    $options['gpPageID'] = $_POST['apGPPage'];                
                if (isset($_POST['apGPAttch']))   $options['gpAttch'] = $_POST['apGPAttch'];                                
                if (isset($_POST['apGPMsgFrmt'])) $options['gpMsgFormat'] = $_POST['apGPMsgFrmt'];                                
                
                if (isset($_POST['apFBURL']))     $options['fbURL'] = $_POST['apFBURL'];
                if (isset($_POST['apFBPgID']))    $options['fbPgID'] = $_POST['apFBPgID'];
                if (isset($_POST['apFBAppID']))   $options['fbAppID'] = $_POST['apFBAppID'];                                
                if (isset($_POST['apFBAppSec']))  $options['fbAppSec'] = $_POST['apFBAppSec'];        
                if (isset($_POST['apFBAttch']))   $options['fbAttch'] = $_POST['apFBAttch'];        
                if (isset($_POST['apFBMsgFrmt'])) $options['fbMsgFormat'] = $_POST['apFBMsgFrmt'];                                
                
                if (isset($_POST['apTWURL']))        $options['twURL'] = $_POST['apTWURL'];
                if (isset($_POST['apTWConsKey']))    $options['twConsKey'] = $_POST['apTWConsKey'];
                if (isset($_POST['apTWConsSec']))    $options['twConsSec'] = $_POST['apTWConsSec'];                                
                if (isset($_POST['apTWAccToken']))   $options['twAccToken'] = $_POST['apTWAccToken'];                
                if (isset($_POST['apTWAccTokenSec']))$options['twAccTokenSec'] = $_POST['apTWAccTokenSec'];                                
                if (isset($_POST['apTWMsgFrmt']))    $options['twMsgFormat'] = $_POST['apTWMsgFrmt'];                                
                
                if (isset($_POST['apCats']))      $options['cats'] = $_POST['apCats'];
                
                if ($emptyUser) { //## then we're dealing with the main Admin options
                    $options[$this->NextScripts_GPAutoPosterAllUsers] = $_POST['NS_SNAutoPosterallusers'];
                    $options[$this->NextScripts_GPAutoPosterNoPublish] = $_POST['NS_SNAutoPosternopublish'];                    
                    $optionsAppend = "";
                } else $optionsAppend = "_" . $user_login;       //  prr($options);       
                update_option($this->dbOptionsName . $optionsAppend, $options);
                //## Update settings notification
                ?>
                <div class="updated"><p><strong><?php _e("Settings Updated.", "NS_SNAutoPoster");?></strong></p></div>
            <?php
            }
            //## Display HTML form for the options below
           
            ?>            
            <script type="text/javascript"> if (typeof jQuery == 'undefined') {var script = document.createElement('script'); script.type = "text/javascript"; 
              script.src = "http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"; document.getElementsByTagName('head')[0].appendChild(script);
            }</script>
            <script type="text/javascript">
            function doShowHideAltFormat(){ if (jQuery('#NS_SNAutoPosterAttachPost').is(':checked')) { 
                    jQuery('#altFormat').css('margin-left', '20px'); jQuery('#altFormatText').html('Post Announce Text:'); } else {jQuery('#altFormat').css('margin-left', '0px'); jQuery('#altFormatText').html('Post Text Format:');}
            }
            function doShowHideBlocks(blID){ if (jQuery('#apDo'+blID).is(':checked')) jQuery('#do'+blID+'Div').show(); else jQuery('#do'+blID+'Div').hide();
                    
            }
            </script>
           <div class=wrap><h2>Next Scripts Social Networks AutoPoster Options for WordPress user <?php if ($emptyUser) { echo 'Admin'; } else { echo $user_login; } ?></h2>
            <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">                
            <!-- G+ -->   
            <h3 style="font-size: 17px;">Google+ Settings</h3>   
            
            <?php if (!file_exists("apis/postToGooglePlus.php")){?> Google+ don't have a built-in API for automated posts yet. You need to get a special <a href="http://www.nextscripts.com/google-plus-automated-posting">library module</a> to be able to publish to Google+. Please place the <b>postToGooglePlus.php</b> file to the <b>/wp-content/plugins/NextScripts_SNAP/apis/</b> folder to activate Google+ publishing functionality.  <?php } else {?>
            
            <p style="margin: 0px;margin-left: 5px;"><input value="1" id="apDoGP" name="apDoGP" onchange="doShowHideBlocks('GP');" type="checkbox" <?php if ((int)$options['doGP'] == 1) echo "checked"; ?> /> 
              <strong>Auto-publish your Posts to your Google+ Page or Profile</strong>                                 
            </p>
            <div id="doGPDiv" style="margin-left: 10px;<?php if ((int)$options['doGP'] != 1) echo "display:none"; ?> ">
                  
            <div style="width:100%;"><strong>Google+ Username:</strong> </div><input name="apGPUName" id="apGPUName" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$options['gpUName']), 'NS_SNAutoPoster') ?>" />                
            <div style="width:100%;"><strong>Google+ Password:</strong> </div><input name="apGPPass" id="apGPPass" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$options['gpPass']), 'NS_SNAutoPoster') ?>" />  <br/>                
            <p><div style="width:100%;"><strong>Google+ Page ID (Optional):</strong> 
            <p style="font-size: 11px; margin: 0px;">If URL for your page is https://plus.google.com/u/0/b/117008619877691455570/ your Page ID is: 117008619877691455570. Leave Empty to publish to your profile.</p>
            </div><input name="apGPPage" id="apGPPage" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$options['gpPageID']), 'NS_SNAutoPoster') ?>" /> 
            <br/><br/>
            <p style="margin: 0px;"><input value="1"  id="apGPAttch" onchange="doShowHideAltFormat();" type="checkbox" name="apGPAttch"  <?php if ((int)$options['gpAttch'] == 1) echo "checked"; ?> /> 
              <strong>Publish Posts to Google+ as an Attachement</strong>                                 
            </p>
            
            <div id="altFormat" style="<?php if ((int)$options['gpAttch'] == 1) echo "margin-left: 20px;"; ?> ">
              <div style="width:100%;"><strong id="altFormatText"><?php if ((int)$options['gpAttch'] == 1) echo "Post Announce Text:"; else echo "Post Text Format:"; ?></strong> 
              <p style="font-size: 11px; margin: 0px;">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. &nbsp; %URL% - Inserts the URL of your post. &nbsp;  %TEXT% - Inserts the body(text) of your post.</p>
              </div><input name="apGPMsgFrmt" id="apGPMsgFrmt" style="width: 50%;" value="<?php _e(apply_filters('format_to_edit',$options['gpMsgFormat']), 'NS_SNAutoPoster') ?>" />
            </div><br/>             
            </div>
            <?php } ?>
            
            <!-- FB -->   <br/><hr/>
            <h3 style="font-size: 17px;">FaceBook Settings</h3>   
            <p style="margin: 0px;margin-left: 5px;"><input value="1" id="apDoFB" name="apDoFB" onchange="doShowHideBlocks('FB');" type="checkbox" <?php if ((int)$options['doFB'] == 1) echo "checked"; ?> /> 
              <strong>Auto-publish your Posts to your Facebook Page or Profile</strong>                                 
            </p>
            <div id="doFBDiv" style="margin-left: 10px;<?php if ((int)$options['doFB'] != 1) echo "display:none"; ?> ">
                           
            <div style="width:100%;"><strong>Your Facebook URL:</strong> </div><input name="apFBURL" id="apFBURL" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$options['fbURL']), 'NS_SNAutoPoster') ?>" />                
            <div style="width:100%;"><strong>Your Facebook App ID:</strong> </div><input name="apFBAppID" id="apFBAppID" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$options['fbAppID']), 'NS_SNAutoPoster') ?>" />  
            <div style="width:100%;"><strong>Your Facebook App Secret:</strong> </div><input name="apFBAppSec" id="apFBAppSec" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$options['fbAppSec']), 'NS_SNAutoPoster') ?>" />
            <div style="width:100%;"><strong>Your Facebook Page ID:</strong> </div><input name="apFBPgID" id="apFBPgID" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$options['fbPgID']), 'NS_SNAutoPoster') ?>" />
            
            <br/><br/>
            <p style="margin: 0px;"><input value="1"  id="apFBAttch" onchange="doShowHideAltFormat();" type="checkbox" name="apFBAttch"  <?php if ((int)$options['fbAttch'] == 1) echo "checked"; ?> /> 
              <strong>Publish Posts to Facebook as an Attachement</strong>                                 
            </p>
            
            <div id="altFormat" style="<?php if ((int)$options['gpAttch'] == 1) echo "margin-left: 20px;"; ?> ">
              <div style="width:100%;"><strong id="altFormatText"><?php if ((int)$options['gpAttch'] == 1) echo "Post Announce Text:"; else echo "Post Text Format:"; ?></strong> 
              <p style="font-size: 11px; margin: 0px;">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. &nbsp; %URL% - Inserts the URL of your post. &nbsp;  %TEXT% - Inserts the body(text) of your post.</p>
              </div><input name="apFBMsgFrmt" id="apFBMsgFrmt" style="width: 50%;" value="<?php _e(apply_filters('format_to_edit',$options['fbMsgFormat']), 'NS_SNAutoPoster') ?>" />
            </div><br/>   
            <?php 
            if($options['fbAppSec']=='') { ?>
            <b>Authorize Your FaceBook Account</b>. Please save your settings and come back here to Authorize your account.
            <?php } else { if($options['fbAppAuthUser']>0) { ?>
            Your FaceBook Account has been authorized. User ID: <?php _e(apply_filters('format_to_edit',$options['fbAppAuthUser']), 'NS_SNAutoPoster') ?>. 
            You can Re- <?php } ?>            
            <a target="_blank" href="https://www.facebook.com/dialog/oauth?client_id=<?=$options['fbAppID']?>&client_secret=<?=$options['fbAppSec']?>&redirect_uri=<?=site_url()?>/wp-admin/options-general.php?page=NextScripts_SNAP.php&scope=publish_stream,offline_access,read_stream,manage_pages">Authorize Your FaceBook Account</a>             
            <?php }
            
            if ($_GET['code']!='' && $_GET['action']!='gPlusAuth'){ $at = $_GET['code'];  echo "Code:".$at;
                $response  = wp_remote_get('https://graph.facebook.com/oauth/access_token?client_id='.$options['fbAppID'].'&redirect_uri='.urlencode(site_url().'/wp-admin/options-general.php?page=NextScripts_SNAP.php').'&client_secret='.$options['fbAppSec'].'&code='.$at); prr($response);
                parse_str($response['body'], $params); $at = $params['access_token'];
                $response  = wp_remote_get('https://graph.facebook.com/oauth/access_token?client_secret='.$options['fbAppSec'].'&client_id='.$options['fbAppID'].'&grant_type=fb_exchange_token&fb_exchange_token='.$at); 
                parse_str($response['body'], $params); $at = $params['access_token']; $options['fbAppAuthToken'] = $at; 
                require_once ('apis/facebook.php'); echo "Using API";
                $facebook = new Facebook(array( 'appId' => $options['fbAppID'], 'secret' => $options['fbAppSec'], 'cookie' => true)); 
                    $facebook -> setAccessToken($options['fbAppAuthToken']); $user = $facebook->getUser(); echo "USER:"; prr($user);
                    if ($user) {
                        try { $page_id = $options['fbPgID']; $page_info = $facebook->api("/$page_id?fields=access_token");
                            if( !empty($page_info['access_token']) ) { $options['fbAppPageAuthToken'] = $page_info['access_token']; }
                        } catch (FacebookApiException $e) { error_log($e); $user = null;}
                    }else echo "Please login to Facebook";                
                                                
                 if ($user>0) $options['fbAppAuthUser'] = $user; update_option($this->dbOptionsName . $optionsAppend, $options);                            
                 ?><script type="text/javascript">window.location = "<?php echo site_url(); ?>/wp-admin/options-general.php?page=NextScripts_SNAP.php"</script><?php            
                 die();
            }
            ?>
            
            </div>         
             <!-- TW -->   <br/><hr/>
            <h3 style="font-size: 17px;">Twitter Settings</h3> 
            <p style="margin: 0px;margin-left: 5px;"><input value="1" id="apDoTW" name="apDoTW" onchange="doShowHideBlocks('TW');" type="checkbox" <?php if ((int)$options['doTW'] == 1) echo "checked"; ?> /> 
              <strong>Auto-publish your Posts to your Twitter</strong>                                 
            </p>
            <div id="doTWDiv" style="margin-left: 10px;<?php if ((int)$options['doTW'] != 1) echo "display:none"; ?> "> 
            
            <div style="width:100%;"><strong>Your Twitter URL:</strong> </div><input name="apTWURL" id="apTWURL" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$options['twURL']), 'NS_SNAutoPoster') ?>" />                
            <div style="width:100%;"><strong>Your Twitter Consumer Key:</strong> </div><input name="apTWConsKey" id="apTWConsKey" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$options['twConsKey']), 'NS_SNAutoPoster') ?>" />  
            <div style="width:100%;"><strong>Your Twitter Consumer Secret:</strong> </div><input name="apTWConsSec" id="apTWConsSec" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$options['twConsSec']), 'NS_SNAutoPoster') ?>" />
            <div style="width:100%;"><strong>Your Access Token:</strong> </div><input name="apTWAccToken" id="apTWAccToken" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$options['twAccToken']), 'NS_SNAutoPoster') ?>" />
            <div style="width:100%;"><strong>Your Access Token Secret:</strong> </div><input name="apTWAccTokenSec" id="apTWAccTokenSec" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$options['twAccTokenSec']), 'NS_SNAutoPoster') ?>" />
            
            <div style="width:100%;"><strong id="altFormatText"><?php if ((int)$options['gpAttch'] == 1) echo "Post Announce Text:"; else echo "Post Text Format:"; ?></strong> 
              <p style="font-size: 11px; margin: 0px;">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. &nbsp; %URL% - Inserts the URL of your post. &nbsp;  %TEXT% - Inserts the body(text) of your post.</p>
              </div><input name="apTWMsgFrmt" id="apTWMsgFrmt" style="width: 50%;" value="<?php _e(apply_filters('format_to_edit',$options['twMsgFormat']), 'NS_SNAutoPoster') ?>" />
              
            
            
            </div>
            <br/><hr/>
            
            <p><div style="width:100%;"><strong>Categories to Include/Exclude:</strong> 
            <p style="font-size: 11px; margin: 0px;">Publish posts only from specific categories. List IDs like: 3,4,5 or exclude some from specific categories from publishing. List IDs like: -3,-4,-5</p>
            
            </div><input name="NS_SNAutoPostercats" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$options[$this->NextScripts_GPAutoPosterCats]), 'NS_SNAutoPoster') ?>" /></p>
             
           
        <div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'NS_SNAutoPoster') ?>" /></div>
        </form>
        </div>
        <?php
        }
        //## END OF showSNAutoPosterOptionsPage()
        
        function NS_SNAP_SavePostMetaTags($id) { $awmp_edit = $_POST["SNAPEdit"]; //prr($_POST);
            if (isset($awmp_edit) && !empty($awmp_edit)) { $format = $_POST["SNAPformat"];  $exclude = $_POST["SNAPexclude"];  $include = $_POST["SNAPinclude"]; $isAttachPost = $_POST["SNAPAttachPost"];
                delete_post_meta($id, 'SNAPformat');  delete_post_meta($id, 'SNAPexclude');  delete_post_meta($id, 'SNAPinclude'); delete_post_meta($id, 'SNAPAttachPost');
                if (isset($format) && !empty($format))   add_post_meta($id, 'SNAPformat', $format);                
                if (isset($exclude) && !empty($exclude)) add_post_meta($id, 'SNAPexclude', $exclude);
                if (isset($include) && !empty($include)) add_post_meta($id, 'SNAPinclude', $include);
                if (isset($isAttachPost) && !empty($isAttachPost)) add_post_meta($id, 'SNAPAttachPost', $isAttachPost);
            }
        }
        function NS_SNAP_AddPostMetaTags() { global $post; $post_id = $post; if (is_object($post_id))  $post_id = $post_id->ID; $options = get_option($this->dbOptionsName.$optionsAppend);    
            $doGP = $options['doGP'];   $doFB = $options['doGP'];   $doTW = $options['doGP'];              
            $t = get_post_meta($post_id, 'SNAP_AttachGP', true);  $isAttachGP = $t!=''?$t:$options['gpAttch'];
            $t = get_post_meta($post_id, 'SNAP_AttachFB', true);  $isAttachFB = $t!=''?$t:$options['fbAttch'];            
            $t = get_post_meta($post_id, 'SNAP_FormatGP', true);  $gpMsgFormat = $t!=''?$t:$options['gpMsgFormat'];
            $t = get_post_meta($post_id, 'SNAP_FormatFB', true);  $fbMsgFormat = $t!=''?$t:$options['fbMsgFormat'];
            $t = get_post_meta($post_id, 'SNAP_FormatTW', true);  $twMsgFormat = $t!=''?$t:$options['twMsgFormat'];
            ?>
              <div id="postftfp" class="postbox"><h3><?php _e('NextScripts: Social Networks Auto Poster - Post Options', 'NS_SPAP') ?></h3>
              <div class="inside"><div id="postftfp">
              <script type="text/javascript"> if (typeof jQuery == 'undefined') {var script = document.createElement('script'); script.type = "text/javascript"; 
                    script.src = "http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"; document.getElementsByTagName('head')[0].appendChild(script);
              }</script>
            <script type="text/javascript">function doShowHideAltFormatX(){if (jQuery('#SNAP').is(':checked')) {jQuery('#altFormat1').hide(); jQuery('#altFormat2').hide();} else { jQuery('#altFormat1').show(); jQuery('#altFormat2').show();}}</script>
        
            
            <input value="SNAPEdit" type="hidden" name="SNAPEdit" />
            <table style="margin-bottom:40px" border="0">
                <!-- G+ -->
                <tr><th style="text-align:left;" colspan="2">Google+ AutoPoster Options</th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish") { ?><input style="float: right;" type="button" class="button" name="rePostToGP_repostButton" id="rePostToGP_button" value="<?php _e('Repost to Google+', 're-post') ?>" />
                    <?php wp_nonce_field( 'rePostToGP', 'rePostToGP_wpnonce' ); } ?>
                </td></tr>
                
                
                <?php if ((int)$isAvailGP == 1) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your Google+ Account to AutoPost to Google+</b>
                <?php } elseif ($post->post_status != "publish") { ?> 
                
                <tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"><input value="1" type="checkbox" name="SNAPincludeGP" <?php if ((int)$doGP == 1) echo "checked"; ?> /></th>
                <td><b><?php _e('Publish this Post to Google+', 'NS_SPAP'); ?></b></td>
               </tr>
                <tr><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 5px; padding-right:10px;">
                <input value="1"  id="SNAPAttachPost" onchange="doShowHideAltFormatX();" type="checkbox" name="SNAPAttachPost"  <?php if ((int)$isAttachGP == 1) echo "checked"; ?> /> </th><td><strong>Publish Post to Google+ as Attachement</strong></td></tr>
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:80px; padding-right:10px;"><?php _e('Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $gpMsgFormat ?>" type="text" name="SNAPformat" size="60px"/></td></tr>
                
                <tr id="altFormat2" style=""><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 5px; padding-right:10px;">Format Options:</th>
                <td style="vertical-align:top; font-size: 9px;" colspan="2">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. <br/> %URL% - Inserts the URL of your post. &nbsp; %TEXT% - Inserts the body(text) of your post.</td></tr>
                <?php } ?>
                <!-- FB -->
                <tr><th style="text-align:left;" colspan="2">FaceBook AutoPoster Options</th><td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish") { ?><input style="float: right;" type="button" class="button" name="rePostToFB_repostButton" id="rePostToFB_button" value="<?php _e('Repost to FaceBook', 're-post') ?>" />
                    <?php wp_nonce_field( 'rePostToFB', 'rePostToFB_wpnonce' ); } ?>
                </td></tr>
                <?php if ((int)$isAvailFB == 1) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup and Authorize your FaceBook Account to AutoPost to FaceBook</b>
                <?php } elseif ($post->post_status != "publish") {?> 
                
                <tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"><input value="1" type="checkbox" name="SNAPincludeFB" <?php if ((int)$doFB == 1) echo "checked"; ?> /></th>
                <td><b><?php _e('Publish this Post to FaceBook', 'NS_SPAP'); ?></b></td>
                </tr>
                <tr><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 5px; padding-right:10px;">
                <input value="1"  id="SNAPAttachPost" onchange="doShowHideAltFormatX();" type="checkbox" name="SNAPAttachPost"  <?php if ((int)$isAttachFB == 1) echo "checked"; ?> /> </th><td><strong>Publish Post to FaceBook as Attachement</strong></td>                </tr>
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:80px; padding-right:10px;"><?php _e('Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $fbMsgFormat ?>" type="text" name="SNAPformat" size="60px"/></td></tr>
                
                <tr id="altFormat2" style=""><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 5px; padding-right:10px;">Format Options:</th>
                <td style="vertical-align:top; font-size: 9px;" colspan="2">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. <br/> %URL% - Inserts the URL of your post. &nbsp; %TEXT% - Inserts the body(text) of your post.</td></tr>
                <?php } ?>
                <!-- TW -->
                <tr><th style="text-align:left;" colspan="2">Twitter AutoPoster Options</th><td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish") { ?><input style="float: right;" type="button" class="button" name="rePostToTW_repostButton" id="rePostToTW_button" value="<?php _e('Repost to Twitter', 're-post') ?>" />
                    <?php wp_nonce_field( 'rePostToTW', 'rePostToTW_wpnonce' ); } ?>
                </td></tr>
                <?php if ((int)$isAvailTW == 1) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your Twitter Account to AutoPost to Twitter</b>
                <?php }elseif ($post->post_status != "publish") { ?> 
                
                <tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"><input value="1" type="checkbox" name="SNAPincludeTW" <?php if ((int)$doTW == 1) echo "checked"; ?> /></th>
                <td><b><?php _e('Publish this Post to FaceBook', 'NS_SPAP'); ?></b></td>
                </tr>                
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:80px; padding-right:10px;"><?php _e('Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $twMsgFormat ?>" type="text" name="SNAPformat" size="60px"/></td></tr>
                
                <tr id="altFormat2" style=""><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 5px; padding-right:10px;">Format Options:</th>
                <td style="vertical-align:top; font-size: 9px;" colspan="2">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. <br/> %URL% - Inserts the URL of your post. &nbsp; %TEXT% - Inserts the body(text) of your post.</td></tr>
                <?php } ?>
            </table>
            </div></div></div>        <?    
        }
    }
}

//## Instantiate the class
if (class_exists("NS_SNAutoPoster")) {$plgn_NS_SNAutoPoster = new NS_SNAutoPoster();}
//## Initialize the admin panel if the plugin has been activated
if (!function_exists("NS_SNAutoPoster_ap")) {
  function NS_SNAutoPoster_ap() { global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return;        
    if (function_exists('add_options_page')) {
      add_options_page('Social Networks AutoPoster Options', 'Social Networks AutoPoster Options', 9, basename(__FILE__), array(&$plgn_NS_SNAutoPoster, 'showSNAutoPosterOptionsPage'));
      add_submenu_page('users.php', 'Social Networks AutoPoster Options', 'Social Networks AutoPoster Options', 2, basename(__FILE__), array(&$plgn_NS_SNAutoPoster, 'showSNAutoPosterUsersOptionsPage'));
    }        
    if (function_exists('add_option')) { 
      add_option('SNAPformat',  '', 'Social Networks AutoPoster Meta Tags Format', 'yes');    
      add_option('SNAPexclude', '', 'Social Networks AutoPoster Meta Tags Exclude', 'yes'); 
      add_option('SNAPinclude', '', 'Social Networks AutoPoster Tags Include', 'yes');
    }
  }    
}
//## AJAX to Post to Google+
if (!function_exists("jsPostToSNAP")) {
  function jsPostToSNAP() { ?>
    <script type="text/javascript" >
    jQuery(document).ready(function($) {                
        $('input#rePostToGP_button').click(function() { var data = { action: 'rePostToGP', id: $('input#post_ID').val(), _wpnonce: $('input#rePostToGP_wpnonce').val()}; callAjSNAP(data, 'Google+'); });
        $('input#rePostToFB_button').click(function() { var data = { action: 'rePostToFB', id: $('input#post_ID').val(), _wpnonce: $('input#rePostToFB_wpnonce').val()}; callAjSNAP(data, 'FaceBook');});
        $('input#rePostToTW_button').click(function() { var data = { action: 'rePostToTW', id: $('input#post_ID').val(), _wpnonce: $('input#rePostToTW_wpnonce').val()}; callAjSNAP(data, 'Twitter'); });

       function callAjSNAP(data, label) {
            var style = "position: fixed; display: none; z-index: 1000; top: 50%; left: 50%; background-color: #E8E8E8; border: 1px solid #555; padding: 15px; width: 350px; min-height: 80px; margin-left: -175px; margin-top: -40px; text-align: center; vertical-align: middle;";
            $('body').append("<div id='test_results' style='" + style + "'></div>");
            $('#test_results').html("<p>Sending update to "+label+"</p>" + "<p><img src='/wp-includes/js/thickbox/loadingAnimation.gif' /></p>");
            $('#test_results').show();            
            jQuery.post(ajaxurl, data, function(response) {
                $('#test_results').html('<p>Message Posted ' + response + '</p>' +'<input type="button" class="button" name="results_ok_button" id="results_ok_button" value="OK" />');
                $('#results_ok_button').click(remove_results);
            });
            
        }        
        function remove_results() { jQuery("#results_ok_button").unbind("click");jQuery("#test_results").remove();
            if (typeof document.body.style.maxHeight == "undefined") { jQuery("body","html").css({height: "auto", width: "auto"}); jQuery("html").css("overflow","");}
            document.onkeydown = "";document.onkeyup = "";  return false;
        }
    });
    </script>    
    <?php
  }
}
//## Repost to Google+
if (!function_exists("rePostToGP_ajax")) {
  function rePostToGP_ajax() {   check_ajax_referer('rePostToGP');  $id = $_POST['id'];  $result = nsPublishTo($id, 'GP', true);   
    if ($result == 200) die("Successfully sent your post to Google+."); else die($result);
  }
}                                    
if (!function_exists("rePostToFB_ajax")) {
  function rePostToFB_ajax() {    check_ajax_referer('rePostToFB');  $id = $_POST['id'];  $result = nsPublishTo($id, 'FB', true);   
    if ($result == 200) die("Successfully sent your post to FaceBook."); else die($result);
  }
}                                    
if (!function_exists("rePostToTW_ajax")) {
  function rePostToTW_ajax() {    check_ajax_referer('rePostToTW');  $id = $_POST['id'];  $result = nsPublishTo($id, 'TW', true);   
    if ($result == 200) die("Successfully sent your post to Twitter."); else die($result);
  }
}                                    

if (!function_exists("nsFormatMessage")) { //## Format Message
  function nsFormatMessage($msg, $postID){  $post = get_post($postID); $msg = htmlspecialchars(stripcslashes($msg));
      if (preg_match('%URL%', $msg)) { $url = get_permalink($postID); $msg = str_ireplace("%URL%", $url, $msg);}                    
      if (preg_match('%TITLE%', $msg)) { $title = $post->post_title; $msg = str_ireplace("%TITLE%", $title, $msg); }                    
      if (preg_match('%TEXT%', $msg)) { $postExcerpt = $post->post_excerpt; $msg = str_ireplace("%TEXT%", $postExcerpt, $msg);}                    
      if (preg_match('%SITENAME%', $msg)) { $siteTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); $msg = str_ireplace("%SITENAME%", $siteTitle, $msg);}      
      return $msg;      
  }
}

if (!function_exists("nsPublishTo")) { //## Main Function to Post 
  function nsPublishTo($postID, $type='', $aj=false) { $post = get_post($postID);  $maxLen = 1000; // prr($post); 
    if ($post->post_type == 'post') { $options = get_option('NS_SNAutoPoster'); //prr($options);
      //## Check if need to publish it
      if (!$aj && $type!='' && (int)$options['do'.$type]!=1) return; $chCats = trim($options['cats']); $continue = true;
      if ($chCats!=''){ $cats = split(",", $options['cats']);  $continue = false;
        foreach ($cats as $cat) { if (preg_match('/^-\d+/', $cat)) { $cat = preg_replace('/^-/', '', $cat);
            //## if in the exluded category, return.
            if (in_category( (int)$cat, $post )) return; else  $continue = true; 
          } else if (preg_match('/\d+/', $cat)) { if (in_category( (int)$cat, $post )) $continue = true; }
        }
      }
      if ($type==''){  
        $t = get_post_meta($postID, 'SNAPincludeGP', true);  $doGP = $t!=''?$t:$options['doGP'];
        $t = get_post_meta($postID, 'SNAPincludeFB', true);  $doFB = $t!=''?$t:$options['doFB'];
        $t = get_post_meta($postID, 'SNAPincludeTW', true);  $doTW = $t!=''?$t:$options['doTW'];     
      } //var_dump($doTW); var_dump($doGP); var_dump($doFB);
      if (!$continue) return; else {
          if ($type=='TW' || ($type=='' && (int)$doTW==1)) doPublishToTW($postID, $options);
          if ($type=='GP' || ($type=='' && (int)$doGP==1)) doPublishToGP($postID, $options); 
          if ($type=='FB' || ($type=='' && (int)$doFB==1)) doPublishToFB($postID, $options);
      }
    } //die();
  }
}
// Add function to pubslih to Google +
if (!function_exists("doPublishToGP")) { //## Second Function to Post to G+
  function doPublishToGP($postID, $options){ 
      $t = get_post_meta($postID, 'SNAP_FormatGP', true);  $gpMsgFormat = $t!=''?$t:$options['gpMsgFormat'];
      $t = get_post_meta($postID, 'SNAP_AttachGP', true);  $isAttachGP = $t!=''?$t:$options['gpAttch'];
      $msg = nsFormatMessage($gpMsgFormat, $postID);
      if ($isAttachGP=='1'){ $src = wp_get_attachment_image_src(get_post_thumbnail_id($postID), 'full'); $src = $src[0];}      
      $email = $options['gpUName'];  $pass = $options['gpPass'];                
      $connectID = getUqID();  $loginError = doConnectToGooglePlus($connectID, $email, $pass);  if ($loginError!==false) return "BAD USER/PASS";    
      $url =  get_permalink($postID);  if ($isAttachGP=='1') $lnk = doGetGoogleUrlInfo($connectID, $url); if ($src!='') $lnk['img'] = $src;                                     
      if (!empty($options['gpPageID'])) {  $to = $options['gpPageID']; $ret = doPostToGooglePlus($connectID, $msg, $lnk, $to);} else $ret = doPostToGooglePlus($connectID, $msg, $lnk);
      echo $ret;
  }
}
// Add function to pubslih to FaceBook
if (!function_exists("doPublishToFB")) { //## Second Function to Post to FB
  function doPublishToFB($postID, $options){ $post = get_post($postID); 
      $t = get_post_meta($postID, 'SNAP_FormatFB', true);  $fbMsgFormat = $t!=''?$t:$options['fbMsgFormat'];
      $t = get_post_meta($postID, 'SNAP_AttachFB', true);  $isAttachFB = $t!=''?$t:$options['fbAttch'];
      $msg = nsFormatMessage($fbMsgFormat, $postID);
      if ($isAttachFB=='1'){ $src = wp_get_attachment_image_src(get_post_thumbnail_id($postID), 'full'); $src = $src[0];}   // prr($post);              
      require_once ('apis/facebook.php'); $page_id = $options['fbPgID']; $dsc = trim($post->post_excerpt); if ($dsc=='') $dsc = $post->post_content; 
      $postSubtitle = site_url();
      $facebook = new Facebook(array( 'appId' => $options['fbAppID'], 'secret' => $options['fbAppSec'], 'cookie' => true ));  
      $mssg = array('access_token'  => $options['fbAppPageAuthToken'], 'message' => $msg, 'name' => $post->post_title, 'caption' => $postSubtitle, 'link' => get_permalink($postID),
       'description' => $dsc, 'actions' => array(array('name' => htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES), 'link' => site_url())) );  
      if (trim($src)!='') $mssg['picture'] = $src;
       
      $ret = $facebook->api("/$page_id/feed","post", $mssg); 
      //die();  
  }
}
// Add function to pubslih to Twitter
if (!function_exists("doPublishToTW")) { //## Second Function to Post to TW 
  function doPublishToTW($postID, $options){ $post = get_post($postID); //prr($post); die();
      $t = get_post_meta($postID, 'SNAP_FormatTW', true);  $twMsgFormat = $t!=''?$t:$options['twMsgFormat'];      
      $msg = nsFormatMessage($twMsgFormat, $postID);
      require_once ('apis/tmhOAuth.php'); require_once ('apis/tmhUtilities.php'); 
      $tmhOAuth = new tmhOAuth(array( 'consumer_key' => $options['twConsKey'], 'consumer_secret' => $options['twConsSec'], 'user_token' => $options['twAccToken'], 'user_secret' => $options['twAccTokenSec']));
      $code = $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array('status' =>$msg));
      if ($code == 200) { echo "Twitter - OK";/*tmhUtilities::pr(json_decode($tmhOAuth->response['response']));*/} else { tmhUtilities::pr($tmhOAuth->response['response']);}      
  }
}

//## Actions and filters    
if (isset($plgn_NS_SNAutoPoster)) { //## Actions
    //## Add the admin menu
    add_action('admin_menu', 'NS_SNAutoPoster_ap');
    //## Initialize options on plugin activation
    add_action("activate_NextScripts_GPAutoPoster/NextScripts_SNAP.php",  array(&$plgn_NS_SNAutoPoster, 'init'));    
    
    add_action('edit_form_advanced', array($plgn_NS_SNAutoPoster, 'NS_SNAP_AddPostMetaTags'));
    add_action('edit_page_form', array($plgn_NS_SNAutoPoster, 'NS_SNAP_AddPostMetaTags'));
    
    add_action('edit_post', array($plgn_NS_SNAutoPoster, 'NS_SNAP_SavePostMetaTags'));
    add_action('publish_post', array($plgn_NS_SNAutoPoster, 'NS_SNAP_SavePostMetaTags'));
    add_action('save_post', array($plgn_NS_SNAutoPoster, 'NS_SNAP_SavePostMetaTags'));
    add_action('edit_page_form', array($plgn_NS_SNAutoPoster, 'NS_SNAP_SavePostMetaTags'));    
    //## Whenever you publish a post, post to Google Plus
    add_action('future_to_publish', 'nsPublishTo');
    add_action('new_to_publish', 'nsPublishTo');
    add_action('draft_to_publish', 'nsPublishTo');
    
    add_action('admin_head', 'jsPostToSNAP');    
    add_action('wp_ajax_rePostToGP', 'rePostToGP_ajax');
    add_action('wp_ajax_rePostToFB', 'rePostToFB_ajax');
    add_action('wp_ajax_rePostToTW', 'rePostToTW_ajax');
}
?>