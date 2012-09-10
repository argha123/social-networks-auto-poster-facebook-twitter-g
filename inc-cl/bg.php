<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'BG', 'lcode'=>'bg', 'name'=>'Blogger');

if (!class_exists("nxs_snapClassBG")) { class nxs_snapClassBG {
  //#### Show Common Settings  
  function showGenNTSettings($ntOpts){ global $nxs_snapThisPageUrl; $code = 'BG'; $lcode = 'bg'; wp_nonce_field( 'ns'.$code, 'ns'.$code.'_wpnonce' ); 
 
    ?>    
    <hr/><div style="font-size: 17px;font-weight: bold; margin-bottom: 15px;">Blogger Settings:   <?php $cfbo = count($ntOpts); $mfbo =  1+max(array_keys($ntOpts)); ?> <?php wp_nonce_field( 'nsFB', 'nsFB_wpnonce' ); ?>
    <div class="nsBigText">You have <?php echo $cfbo=='0'?'No':$cfbo; ?> Blogger account<?php if ($cfbo!=1){ ?>s<?php } ?> <!-- - <a href="#" class="NXSButton" onclick="doShowHideBlocks2('FB<?php echo $mfbo; ?>');return false;">Add new Facebook Account</a> --> </div></div>    
    <?php // if (function_exists('nxs_doSMAS1')) nxs_doSMAS1($this, $mfbo); else nxs_doSMAS('Blogger', 'BG'.$mfbo); ?>
    <?php foreach ($ntOpts as $indx=>$pbo){ ?>
      <p style="margin: 0px;margin-left: 5px;"><input value="1" id="apDoBG" name="bg[<?php echo $indx; ?>][apDoBG]" onchange="doShowHideBlocks('BG');" type="checkbox" <?php if ((int)$pbo['doBG'] == 1) echo "checked"; ?> /> 
      <strong>Auto-publish your Posts to your <?php if(isset($pbo['bgBlogID']) && $pbo['bgBlogID']!='') echo "(".$pbo['bgBlogID'].")"; ?> Blogger Profile</strong>
      <a id="doBG<?php echo $indx; ?>A" href="#" onclick="doShowHideBlocks2('BG<?php echo $indx; ?>');return false;">[Show Settings]</a>&nbsp;&nbsp;
      <a href="#" onclick="doDelAcct('bg', '<?php echo $indx; ?>', '<?php if (isset($pbo['bgBlogID'])) echo $pbo['bgBlogID']; ?>');return false;">[Remove Account]</a>
      </p><?php $this->showNTSettings($indx, $pbo);             
    } //## END TR Settings 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($bo){ $po = array('doBG'=>'1', 'bgUName'=>'', 'bgPass'=>'', 'bgBlogID'=>'', 'bgInclTags'=>'1', 'bgMsgFormat'=>'%FULLTEXT% <br/><a href=\'%URL%\'>%TITLE%</a>', 'bgMsgTFormat'=>'%TITLE%' ); $this->showNTSettings($bo, $po, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  ?>
    <div id="doBG<?php echo $ii; ?>Div" <?php if ($isNew){ ?>class="clNewNTSets"<?php } ?> style="margin-left: 10px; <?php if ((isset($options['bgOK']) && $options['bgOK']!='')||$isNew) { ?>display:none;<?php } ?>">   <input type="hidden" name="apDoSBG<?php echo $ii; ?>" value="0" id="apDoSBG<?php echo $ii; ?>" />                                     
    <?php if ($isNew) { ?> <input type="hidden" name="bg[<?php echo $ii; ?>][apDoBG]" value="1" id="apDoNewBG<?php echo $ii; ?>" /> <?php } ?>
    
            
            <div id="doBG<?php echo $ii; ?>Div" style="margin-left: 10px;"> 
            <br/>
            <div style="width:100%;"><strong>Blogger Username/Email:</strong> </div><input name="bg[<?php echo $ii; ?>][apBGUName]" id="apBGUName" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$options['bgUName']), 'NS_SNAutoPoster') ?>" />                
            <div style="width:100%;"><strong>Blogger Password:</strong> </div><input name="bg[<?php echo $ii; ?>][apBGPass]" id="apBGPass" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', substr($options['bgPass'], 0, 5)=='b4d7s'?nsx_doDecode(substr($options['bgPass'], 5)):$options['bgPass']), 'NS_SNAutoPoster') ?>" />  <br/>                
            <div style="width:100%;"><strong>Blogger Blog ID:</strong> 
            <p style="font-size: 11px; margin: 0px;">Log to your Blogger management panel and look at the URL: http://www.blogger.com/blogger.g?blogID=8959085979163812093#allposts. Your Blog ID will be: 8959085979163812093</p>
            </div><input name="bg[<?php echo $ii; ?>][apBGBlogID]" id="apBGBlogID" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$options['bgBlogID']), 'NS_SNAutoPoster') ?>" /> 
            <br/><br/>            
            
            <strong id="altFormatText">Post Title and Post Text Formats</strong> 
            <p style="font-size: 11px; margin: 0px;">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. &nbsp; %URL% - Inserts the URL of your post. &nbsp; %SURL% - Inserts the <b>Shortened URL</b> of your post. &nbsp;  %IMG% - Inserts the featured image. &nbsp;  %TEXT% - Inserts the excerpt of your post. &nbsp;  %FULLTEXT% - Inserts the body(text) of your post, %AUTHORNAME% - Inserts the author's name. HTML is <?php if(!function_exists('doPostToGooglePlus')) {?> <b>NOT</b> <?php } ?> allowed. <?php if(!function_exists('doPostToGooglePlus')) {?> <i>- Blogger "Free API" limitation. Please get <a href="http://www.nextscripts.com/google-plus-automated-posting/#blogger">NextScripts API</a> to allow HTML</i> <?php } ?> </p>
            
            <div style="width:100%;"><strong id="altFormatText">Post Title Format:</strong> </div>
              
              <input name="bg[<?php echo $ii; ?>][apBGMsgTFrmt]" id="apBGMsgTFrmt" style="width: 50%;" value="<?php if ($options['bgMsgTFormat']!='') _e(apply_filters('format_to_edit', stripcslashes(str_replace('"',"'",$options['bgMsgTFormat']))), 'NS_SNAutoPoster'); else echo "%TITLE%"; ?>" /><br/>
            
            <div id="altFormat" style="">
              <div style="width:100%;"><strong id="altFormatText">Post Text Format:</strong> </div>
              <input name="bg[<?php echo $ii; ?>][apBGMsgFrmt]" id="apBGMsgFrmt" style="width: 50%;" value="<?php if ($options['bgMsgFormat']!='') _e(apply_filters('format_to_edit',stripcslashes(str_replace('"',"'",$options['bgMsgFormat']))), 'NS_SNAutoPoster');  else echo "%FULLTEXT% <br/><a href='%URL%'>%TITLE%</a>"; ?>" />
            </div>
            
             <p style="margin-bottom: 20px;margin-top: 5px;"><input value="1"  id="bgInclTags" type="checkbox" name="bg[<?php echo $ii; ?>][bgInclTags]"  <?php if ((int)$options['bgInclTags'] == 1) echo "checked"; ?> /> 
              <strong>Post with tags</strong>  Tags from the blogpost will be auto posted to Blogger/Blogspot                                                               
            </p> 
            
            <?php if ($options['bgPass']!='') { ?>
            <?php wp_nonce_field( 'rePostToBG', 'rePostToBG_wpnonce' ); ?>
            <b>Test your settings:</b>&nbsp;&nbsp;&nbsp; <?php if (!isset($options['bgOK']) || $options['bgOK']!='1') { ?> <div class="blnkg">=== Submit Test Post to Complete ===&gt;</div> <?php } ?> <a href="#" class="NXSButton" onclick="testPost('BG', '<?php echo $ii; ?>'); return false;">Submit Test Post to Blogger</a>         
            <?php } ?>
            
            <div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'NS_SNAutoPoster') ?>" /></div>
            </div>
        </div>
        <?php
      
      
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; //prr($post); die();
    foreach ($post as $ii => $pval){// prr($pval);
      if (isset($pval['apBGUName']) && $pval['apBGPass']!='') { if (!isset($options[$ii])) $options[$ii] = array();
        
                if (isset($pval['apDoBG']))   $options[$ii]['doBG'] = $pval['apDoBG']; else $options[$ii]['doBG'] = 0;
                
                
                if (isset($pval['apBGUName']))   $options[$ii]['bgUName'] = $pval['apBGUName'];
                if (isset($pval['apBGPass']))    $options[$ii]['bgPass'] = 'b4d7s'.nsx_doEncode($pval['apBGPass']); else $options[$ii]['bgPass'] = '';
                if (isset($pval['apBGBlogID']))   $options[$ii]['bgBlogID'] = $pval['apBGBlogID'];                
                if (isset($pval['apBGMsgFrmt'])) $options[$ii]['bgMsgFormat'] = $pval['apBGMsgFrmt'];                   
                if (isset($pval['apBGMsgTFrmt']))    $options[$ii]['bgMsgTFormat'] = $pval['apBGMsgTFrmt'];         
                if (isset($pval['bgInclTags']))    $options[$ii]['bgInclTags'] = $pval['bgInclTags'];  else $options[$ii]['bgInclTags'] = 0;        
                
      } //prr($options);
    } return $options;
  } 
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ $post_id = $post->ID; 
    foreach($ntOpts as $ii=>$options)  {$doBG = $options['doBG'];     
       $isAvailBG =  $options['bgUName']!='' && $options['bgPass']!='';       
       $t = get_post_meta($post_id, 'SNAP_FormatBG', true);  $bgMsgFormat = $t!=''?$t:$options['bgMsgFormat']; $bgMsgFormat = stripcslashes(str_replace('"',"'",$bgMsgFormat));
       $t = get_post_meta($post_id, 'SNAP_FormatTBG', true); $bgMsgTFormat = $t!=''?$t:$options['bgMsgTFormat'];
      ?>  
      
      <tr><th style="text-align:left;" colspan="2">Blogger AutoPoster Options (<i style="color: #005800;"><?php echo $options['bgBlogID']; ?></i>)</th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailBG) { ?><input style="float: right;" type="button" class="button" name="rePostToBG_repostButton" id="rePostToBG_button" value="<?php _e('Repost to Blogger', 're-post') ?>" />
                    <?php wp_nonce_field( 'rePostToBG', 'rePostToBG_wpnonce' ); } ?>
                </td></tr>
                
                
                <?php if (!$isAvailBG) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your Blogger Account to AutoPost to Blogger</b>
                <?php } elseif ($post->post_status != "publish") { ?> 
                
                <tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"><input class="nxsGrpDoChb" value="1" type="checkbox" name="SNAPincludeBG" <?php if ((int)$doBG == 1) echo "checked"; ?> /></th>
                <td><b><?php _e('Publish this Post to Blogger', 'NS_SPAP'); ?></b></td>
                </tr> 
                
                 <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:80px; padding-right:10px;"><?php _e('Title Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $bgMsgTFormat ?>" type="text" name="SNAP_FormatTBG" size="60px"/></td></tr>
                
                <tr id="altFormat2" style=""><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 5px; padding-right:10px;">Title Format Options:</th>
                <td style="vertical-align:top; font-size: 9px;" colspan="2">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. <br/> %URL% - Inserts the URL of your post. &nbsp; %SURL% - Inserts the <b>Shortened URL</b> of your post. &nbsp; %IMG% - Inserts the featured image. &nbsp;  %TEXT% - Inserts the excerpt of your post. &nbsp;  %FULLTEXT% - Inserts the body(text) of your post, %AUTHORNAME% - Inserts the author's name.</td></tr>
                
                
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:80px; padding-right:10px;"><?php _e('Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $bgMsgFormat ?>" type="text" name="SNAP_FormatBG" size="60px"/></td></tr>
                
                <tr id="altFormat2" style=""><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 5px; padding-right:10px;">Format Options:</th>
                <td style="vertical-align:top; font-size: 9px;" colspan="2">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. <br/> %URL% - Inserts the URL of your post. &nbsp; %IMG% - Inserts the featured image. &nbsp;  %TEXT% - Inserts the excerpt of your post. &nbsp;  %FULLTEXT% - Inserts the body(text) of your post, %AUTHORNAME% - Inserts the author's name.</td></tr>
                <?php }
    }
      
  }
  
  function adjMetaOpt($optMt, $pMeta){
     $optMt['bgMsgFormat'] = $pMeta['SNAPformat']; $optMt['bgMsgTFormat'] = $pMeta['SNAPTformat']; $optMt['doBG'] = $pMeta['SNAPincludeBG'] == 1?1:0; return $optMt;
  }
}}

if (!function_exists("nxs_rePostToBG_ajax")) { function nxs_rePostToBG_ajax() {  check_ajax_referer('rePostToBG');  $postID = $_POST['id']; // $result = nsPublishTo($id, 'FB', true);   
    $options = get_option('NS_SNAutoPoster');  foreach ($options['bg'] as $ii=>$po) if ($ii==$_POST['nid']) {  
      $mpo =  get_post_meta($postID, 'snapBG', true); $mpo =  maybe_unserialize($mpo); 
      if (is_array($mpo) && isset($mpo[$ii]) && is_array($mpo[$ii]) ){ $po['bgMsgFormat'] = $mpo[$ii]['SNAPformat']; $po['bgMsgTFormat'] = $mpo[$ii]['SNAPTformat']; $po['bgAttch'] = $mpo[$ii]['AttachPost'] == 1?1:0; } 
      $result = nxs_doPublishToBG($postID, $po);  if ($result == 201) {$options['bg'][$ii]['bgOK']=1;  update_option('NS_SNAutoPoster', $options); }
      
      if ($result == 200) die("Successfully sent your post to Blooger."); else die($result);
    }    
  }
}
if (!function_exists('nsBloggerGetAuth')){ function nsBloggerGetAuth($email, $pass) {
    $ch = curl_init("https://www.google.com/accounts/ClientLogin?Email=$email&Passwd=$pass&service=blogger&accountType=GOOGLE");    
    $headers = array(); $headers[] = 'Accept: text/html, application/xhtml+xml, */*'; 
    $headers[] = 'Connection: Keep-Alive'; $headers[] = 'Accept-Language: en-us'; 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0)");
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,10); curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HEADER,0); curl_setopt($ch, CURLOPT_RETURNTRANSFER ,1);
    $result = curl_exec($ch); $resultArray = curl_getinfo($ch); 
    curl_close($ch); $arr = explode("=",$result); $token = $arr[3]; if (trim($token)=='') die('Incorrect Username/Password'); return $token;
}}
if (!function_exists('nsBloggerNewPost')){ function nsBloggerNewPost($auth, $blogID, $title, $text) {    
    $text = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $text);    $text = preg_replace('/<!--(.*)-->/Uis', "", $text);  $text = str_ireplace('allowfullscreen','', $text); 
    if (class_exists('DOMDocument')) {$doc = new DOMDocument();  @$doc->loadHTML($text);  $text = $doc->saveHTML(); $text = CutFromTo($text, '<body>', '</body>');
      $text = preg_replace('/<br(.*?)\/?>/','<br$1/>',$text);   $text = preg_replace('/<img(.*?)\/?>/','<img$1/>',$text);
      require_once ('apis/htmlNumTable.php');  $text = strtr($text, $HTML401NamedToNumeric);  $title = strtr($title, $HTML401NamedToNumeric);
    }  //  prr($text); 
    $postText = '<entry xmlns="http://www.w3.org/2005/Atom"><title type="text">'.$title.'</title><content type="xhtml">'.$text.'</content></entry>'; //prr($postText);
    $len = strlen($entry); $ch = curl_init("https://www.blogger.com/feeds/$blogID/posts/default"); 
    $headers = array("Content-type: application/atom+xml", "Content-Length: ".strlen($postText), "Authorization: GoogleLogin auth=".$auth, $postText);
    curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, 1);  curl_setopt($ch, CURLOPT_POST, true);  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0)");
    curl_setopt($ch, CURLOPT_HEADER,0); curl_setopt($ch, CURLOPT_RETURNTRANSFER ,1); curl_setopt($ch, CURLINFO_HEADER_OUT, true); 
    $result = curl_exec($ch); curl_close($ch); if (stripos($result,'tag:blogger.com')!==false) return 'OK'; else { prr($result); return false;}
}}
if (!function_exists("nxs_doPublishToBG")) { //## Second Function to Post to BG
  function nxs_doPublishToBG($postID, $options){ $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url(); $isPost = isset($_POST["SNAPEdit"]); 
    if ($postID=='0') { echo "Testing ... <br/><br/>"; $msgT = 'Test Post from '.$blogTitle;  $link = home_url(); $msg = 'Test Post from '.$blogTitle. " ".$link; }
    else{        
      if ($isPost) $bgMsgFormat = $_POST['SNAP_FormatBG']; else { $t = get_post_meta($postID, 'SNAP_FormatBG', true); $bgMsgFormat = $t!=''?$t:$options['bgMsgFormat']; } 
      $msg = nsFormatMessage($bgMsgFormat, $postID); $link = get_permalink($postID);      
      if ($isPost) $bgMsgTFormat = $_POST['SNAP_FormatTBG']; else { $t = get_post_meta($postID, 'SNAP_FormatTBG', true); $bgMsgTFormat = $t!=''?$t:$options['bgMsgTFormat']; } 
      $msgT = nsFormatMessage($bgMsgTFormat, $postID);              
    }
    $email = $options['bgUName'];  $pass = substr($options['bgPass'], 0, 5)=='b4d7s'?nsx_doDecode(substr($options['bgPass'], 5)):$options['bgPass']; $blogID = $options['bgBlogID'];
    //echo "###".$auth."|".$blogID."|".$msgT."|".$msg;
    if ($options['bgInclTags']=='1'){$t = wp_get_post_tags($postID); $tggs = array(); foreach ($t as $tagA) {$tggs[] = $tagA->name;} $tags = implode('","',$tggs);}
    if (function_exists("doConnectToBlogger")) {$auth = doConnectToBlogger($email, $pass); if ($auth!==false) die($auth);  $ret = doPostToBlogger($blogID, $msgT, $msg, $tags);} 
      else {$auth = nsBloggerGetAuth($email, $pass); $ret = nsBloggerNewPost($auth, $blogID, $msgT, $msg);}
    if ($ret!='OK') echo $ret; else { if ($postID=='0') { echo 'OK - Message Posted, please see your Blooger/Blogpost Page';  return 201;} return 200; }
  }
}

?>