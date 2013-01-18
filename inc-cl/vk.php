<?php    
//## NextScripts vKontakte(VK) Connection Class
$nxs_snapAvNts[] = array('code'=>'VK', 'lcode'=>'vk', 'name'=>'vKontakte(VK)');

if (!class_exists("nxs_snapClassVK")) { class nxs_snapClassVK {
  //#### Show Common Settings  
  function showGenNTSettings($ntOpts){ global $nxs_snapThisPageUrl, $nxs_plurl; $code = 'VK'; $lcode = 'vk'; wp_nonce_field( 'ns'.$code, 'ns'.$code.'_wpnonce' ); ?>
    <hr/><div class="nsx_iconedTitle" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $lcode; ?>16.png);">vKontakte(VK) Settings:   <?php $cNTo = count($ntOpts); ?> <?php wp_nonce_field( 'nsVK', 'nsVK_wpnonce' ); ?>
    <div class="nsBigText">You have <?php echo $cNTo=='0'?'No':$cNTo; ?> vKontakte(VK) account<?php if ($cNTo!=1){ ?>s<?php } ?> </div></div>

    <?php foreach ($ntOpts as $indx=>$nto){ $nto['ii'] = $indx; if (trim($nto['nName']=='')) $nto['nName'] = str_ireplace('https://vk.com','', str_ireplace('http://vk.com','', $nto['url'])); ?>
      <p style="margin: 0px;margin-left: 5px;"><input value="1" id="apDoVK" name="vk[<?php echo $indx; ?>][apDoVK]" type="checkbox" <?php if ((int)$nto['doVK'] == 1) echo "checked"; ?> /> 
      <strong>Auto-publish your Posts to your vKontakte(VK) Page or Profile <i style="color: #005800;"><?php if($nto['nName']!='') echo "(".$nto['nName'].")"; ?></i> </strong>
      &nbsp;&nbsp;<a id="doVK<?php echo $indx; ?>A" href="#" onclick="doShowHideBlocks2('VK<?php echo $indx; ?>');return false;">[Show Settings]</a>&nbsp;&nbsp;
      <a href="#" onclick="doDelAcct('vk', '<?php echo $indx; ?>', '<?php echo $nto['url']; ?>');return false;">[Remove Account]</a>
      </p><?php $this->showNTSettings($indx, $nto);             
    } //## END VK Settings 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($mNTo){ $nto = array('nName'=>'', 'doVK'=>'1', 'url'=>'', 'vkAppID'=>'', 'imgUpl'=>'1', 'addBackLink'=>'1', 'vkPostType'=>'A', 'msgAFormat'=>'', 'attch'=>'1', 'vkPgID'=>'', 'vkAppAuthUser'=>'', 'msgFrmt'=>'New post has been published on %SITENAME%' ); $this->showNTSettings($mNTo, $nto, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl, $nxs_snapThisPageUrl; if ((int)$options['attch']==0 && (!isset($options['trPostType']) || $options['trPostType']=='')) $options['trPostType'] = 'T';  
    if (!isset($options['nHrs'])) $options['nHrs'] = 0; if (!isset($options['nMin'])) $options['nMin'] = 0;  if (!isset($options['catSel'])) $options['catSel'] = 0;  if (!isset($options['catSelEd'])) $options['catSelEd'] = ''; ?> 
    <div id="doVK<?php echo $ii; ?>Div" <?php if ($isNew){ ?>class="clNewNTSets"<?php } ?> style="max-width: 1000px; background-color: #EBF4FB; background-image: url(<?php echo $nxs_plurl; ?>img/vk-bg.png);  background-position:90% 10%; background-repeat: no-repeat; margin: 10px; border: 1px solid #808080; padding: 10px; <?php if ((isset($options['vkAppAuthUser']) && $options['vkAppAuthUser']>1)||$isNew) { ?>display:none;<?php } ?>">   <input type="hidden" name="apDoSVK<?php echo $ii; ?>" value="0" id="apDoSVK<?php echo $ii; ?>" />                                
    <?php if ($isNew) { ?>    <input type="hidden" name="vk[<?php echo $ii; ?>][apDoVK]" value="1" id="apDoNewVK<?php echo $ii; ?>" /> <?php } ?>
    
     <div class="nsx_iconedTitle" style="float: right; max-width: 320px; text-align: right; background-image: url(<?php echo $nxs_plurl; ?>img/vk16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-vkontakte-social-networks-auto-poster-wordpress/">Detailed vKontakte Installation/Configuration Instructions</a><br/>
     <span style="font-size: 10px;">Please use URL <em style="font-size: 10px; color:#CB4B16;">http://<?php echo $_SERVER["SERVER_NAME"] ?></em> and domain <em style="font-size: 10px; color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"] ?></em> in your vKontakte(VK) App</span>
     
     </div>
    
    <div style="width:100%;"><strong>Account Nickname:</strong> <i>Just so you can easely identify it</i> </div><input name="vk[<?php echo $ii; ?>][nName]" id="vknName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" /><br/>
    <?php echo nxs_addQTranslSel('vk', $ii, $options['qTLng']); ?>
    <?php echo nxs_addPostingDelaySel('vk', $ii, $options['nHrs'], $options['nMin']); ?>
    
    <?php if (!$isNew) { ?>
    <div style="width:100%;"><strong>Auto-Post Categories:</strong>
       <input value="0" id="catSelA<?php echo $ii; ?>" type="radio" name="vk[<?php echo $ii; ?>][catSel]" <?php if ((int)$options['catSel'] != 1) echo "checked"; ?> /> All                                  
       <input value="1" id="catSelSVK<?php echo $ii; ?>" type="radio" name="vk[<?php echo $ii; ?>][catSel]" <?php if ((int)$options['catSel'] == 1) echo "checked"; ?> /> <a href="#" style="text-decoration: none;" class="showCats" id="nxs_SCA_VK<?php echo $ii; ?>" onclick="jQuery('#catSelSVK<?php echo $ii; ?>').attr('checked', true); jQuery('#tmpCatSelNT').val('VK<?php echo $ii; ?>'); nxs_markCats( jQuery('#nxs_SC_VK<?php echo $ii; ?>').val() ); jQuery('#showCatSel').bPopup({ modalClose: false, appendTo: '#nsStForm', opacity: 0.6, follow: [false, false], position: [75, 'auto']}); return false;">Selected<?php if ($options['catSelEd']!='') echo "[".(substr_count($options['catSelEd'], ",")+1)."]"; ?></a>       
       <input type="hidden" name="vk[<?php echo $ii; ?>][catSelEd]" id="nxs_SC_VK<?php echo $ii; ?>" value="<?php echo $options['catSelEd']; ?>" />
    </div> 
    <br/>
    <?php } ?>
    
    <div style="width:100%;"><strong>Your vKontakte(VK) URL:</strong> </div>
    <p style="font-size: 11px; margin: 0px;">Could be your vKontakte(VK) Profile or vKontakte(VK) Group Page</p>
    <input name="vk[<?php echo $ii; ?>][url]" id="apurl" style="width: 50%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['url'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />                
    
    <div style="width:100%; margin-top: 15px; margin-bottom: 5px;"><b style="font-size: 14px;" >VK API</b> (It could be used for "Text" and "Image" posts)</div>
    
    <div style="width:100%; margin-left: 15px;">
    
    <div style="width:100%;"><strong>vKontakte(VK) Application ID:</strong> <a href="http://vk.com/editapp?act=create" target="_blank">[Create VK App]</a> <a href="http://vk.com/apps?act=settings" target="_blank">[Manage VK Apps]</a> </div> 
    <input name="vk[<?php echo $ii; ?>][apVKAppID]" id="apVKAppID" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['vkAppID'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />  
    <br/>
    <?php  if($options['vkAppID']=='') { ?>
            <b>Authorize Your vKontakte(VK) Account</b>. Please click "Update Settings" to be able to Authorize your account.
            <?php } else { if(isset($options['vkAppAuthUser']) && $options['vkAppAuthUser']>0) { ?>
            Your vKontakte(VK) Account has been authorized. User ID: <?php _e(apply_filters('format_to_edit', htmlentities($options['vkAppAuthUser'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>.
            You can Re- <?php } ?>      
            <a target="_blank" href="http://api.vkontakte.ru/oauth/authorize?client_id=<?php echo $options['vkAppID'];?>&scope=offline,wall,photos,pages&redirect_uri=http://api.vkontakte.ru/blank.html&display=page&response_type=token<?php '&auth=vk&acc='.$options['ii'];?>">Authorize Your vKontakte(VK) Account</a>      
            <!-- <a href="http://api.vkontakte.ru/oauth/authorize?client_id=<?php echo $options['vkAppID'];?>&scope=offline,wall,photos,pages&redirect_uri=<?php echo urlencode($nxs_snapThisPageUrl.'&auth=vk&acc='.$options['ii']);?>">Authorize Your vKontakte(VK) Account</a>  -->
            <?php if (!isset($options['vkAppAuthUser']) || $options['vkAppAuthUser']<1) { ?> <div class="blnkg">&lt;=== Authorize your account ===</div> <?php } ?>
            
            <div style="width:100%;"><strong>vKontakte(VK) Auth Responce:</strong> </div><input name="vk[<?php echo $ii; ?>][apVKAuthResp]" style="width: 50%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['apVKAuthResp'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" /><br/><br/>
            
            <?php } ?>
            
    </div>      
    
    <div style="width:100%; margin-bottom: 5px;"><b style="font-size: 14px;" >NextScripts VK API</b> (It could be used for "Text with attached link" posts)</div>
    
    <div style="width:100%; margin-left: 15px;">
      <?php if( function_exists("nxs_doPostToVK")) { ?>    
         <div style="width:100%;"><strong>vKontakte(VK) Username:</strong> </div><input name="vk[<?php echo $ii; ?>][uName]" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['uName'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />  
         <div style="width:100%;"><strong>vKontakte(VK) Password:</strong> </div><input name="vk[<?php echo $ii; ?>][uPass]" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities(substr($options['uPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['uPass'], 5)):$options['uPass'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />    
      <?php } else { ?> **** Please upgrade the plugin to "PRO" get NextScripts VK API <?php } ?>
    </div>
    <br/>      
    <div id="altFormat">
      <div style="width:100%;"><strong id="altFormatText">Message text Format:</strong> (<a href="#" id="msgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('msgFrmt<?php echo $ii; ?>'); return false;">Show format info</a>)</div>        
        <input name="vk[<?php echo $ii; ?>][msgFrmt]" id="vkmsgFrmt<?php echo $ii; ?>" style="width: 50%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['msgFrmt'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" onfocus="mxs_showFrmtInfo('msgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("msgFrmt".$ii); ?><br/>
    </div>
    <div >
    <input value="1" type="checkbox" name="vk[<?php echo $ii; ?>][addBackLink]"  <?php if (isset($options['addBackLink']) && (int)$options['addBackLink'] == 1) echo "checked"; ?> /> Add backlink to the post
    </div>
       <br/>
      <div style="width:100%;"><strong id="altFormatText">Post Type:</strong> </div>                      
<div style="margin-left: 10px;">
        
        <input type="radio" name="vk[<?php echo $ii; ?>][postType]" value="T" <?php if ($options['postType'] == 'T') echo 'checked="checked"'; ?> /> Text Post - <i>just text message</i><br/>                    
        <input type="radio" name="vk[<?php echo $ii; ?>][postType]" value="I" <?php if ($options['postType'] == 'I') echo 'checked="checked"'; ?> /> Image Post - <i>big image with text message</i><br/>
        <input type="radio"  <?php if( !function_exists("nxs_doPostToVK")) { ?> disabled="disabled" <?php } ?> name="vk[<?php echo $ii; ?>][postType]" value="A" <?php if ( !isset($options['postType']) || $options['postType'] == '' || $options['postType'] == 'A') echo 'checked="checked"'; ?> /> <span <?php if( !function_exists("nxs_doPostToVK")) { ?>style="color:#C0C0C0;"<?php } ?> >Text Post with "attached" link</span><br/>
   <?php if( function_exists("nxs_doPostToVK")) { ?>
<div style="width:100%; margin-left: 15px;"><strong>Link attachment type:&nbsp;</strong> 
    <div style="margin-bottom: 5px; margin-left: 10px; "><input value="1"  id="apattchAsVid" type="checkbox" name="vk[<?php echo $ii; ?>][attchAsVid]"  <?php if (isset($options['attchAsVid']) && (int)$options['attchAsVid'] == 1) echo "checked"; ?> /> 
      <strong>If post has video use it as an attachment thumbnail.</strong> <i>Video will be used for an attachment thumbnail instead of featured image. Only Youtube is supported at this time.</i><br/>
     
    </div>
     <strong>Attachment Text Format:</strong><br/> 
      <input value="1"  id="apVKMsgAFrmtA<?php echo $ii; ?>" <?php if (trim($options['msgAFormat'])=='') echo "checked"; ?> onchange="if (jQuery(this).is(':checked')) { jQuery('#apVKMsgAFrmtDiv<?php echo $ii; ?>').hide(); jQuery('#apVKMsgAFrmt<?php echo $ii; ?>').val(''); }else jQuery('#apVKMsgAFrmtDiv<?php echo $ii; ?>').show();" type="checkbox" name="vk[<?php echo $ii; ?>][msgAFormat]"/> <strong>Auto</strong>
      <i> - Recommended. Info from SEO Plugins will be used, then post excerpt, then post text </i><br/>
      <div id="apVKMsgAFrmtDiv<?php echo $ii; ?>" style="<?php if ($options['msgAFormat']=='') echo "display:none;"; ?>" >&nbsp;&nbsp;&nbsp; Set your own format:<input name="vk[<?php echo $ii; ?>][msgAFormat]" id="apVKMsgAFrmt<?php echo $ii; ?>" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['msgAFormat'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" /><br/></div>
</div><br/>

<?php } ?>

   </div><br/>
  
<div class="popShAtt" style="z-index: 9999" id="popShAtt<?php echo $ii; ?>X"><h3>vKontakte(VK) Post Types</h3><img src="<?php echo $nxs_plurl; ?>img/fbPostTypesDiff6.png" width="600" height="398" alt="vKontakte(VK) Post Types"/></div>

              
            <?php if ($options['vkPgID']!='') {?><div style="width:100%;"><strong>Your vKontakte(VK) Page ID:</strong> <?php _e(apply_filters('format_to_edit', htmlentities($options['vkPgID'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?> </div><?php } ?>
            
            
            
            <?php  if(isset($options['vkAppAuthUser']) && $options['vkAppAuthUser']>0) { ?>
            <?php wp_nonce_field( 'rePostToVK', 'rePostToVK_wpnonce' ); ?>
            <br/><br/><b>Test your settings:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('VK','<?php echo $ii; ?>'); return false;">Submit Test Post to vKontakte(VK)</a>         
            <?php }?>
            <div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'NS_SNAutoPoster') ?>" /></div>
            
          </div>        
        <?php
      
  } 
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; $code = 'VK'; $lcode = 'vk'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apVKAppID']) && $pval['apVKAppID']!='') { if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apDoVK']))         $options[$ii]['doVK'] = $pval['apDoVK']; else $options[$ii]['doVK'] = 0;
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['apVKAppID']))      $options[$ii]['vkAppID'] = trim($pval['apVKAppID']);                                
        
        
        if (isset($pval['apVKAuthResp']))  {   $options[$ii]['apVKAuthResp'] = trim($pval['apVKAuthResp']); 
          $options[$ii]['vkAppAuthToken'] = trim( CutFromTo($pval['apVKAuthResp'].'&', 'access_token=','&')); 
          $options[$ii]['vkAppAuthUser'] = trim( CutFromTo($pval['apVKAuthResp']."&", 'user_id=','&')); 
          $hdrsArr = nxs_getVKHeaders($pval['url']);
          $response = wp_remote_get($pval['url'], array( 'method' => 'GET', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr)); $contents = $response['body'];     
          if (stripos($contents, '"public_id":')!==false) { $options[$ii]['pgIntID'] =  '-'.CutFromTo($contents, '"public_id":', ','); $type='all'; }  
          if (stripos($contents, '"user_id":')!==false) {   $options[$ii]['pgIntID'] =  CutFromTo($contents, '"user_id":', ','); $type='own'; }  
        }
        
        
        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']);
        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
        
        if (isset($pval['postType']))     $options[$ii]['postType'] = trim($pval['postType']);
        if (isset($pval['attch']))      $options[$ii]['attch'] = $pval['attch']; else $options[$ii]['attch'] = 0;
        if (isset($pval['attchAsVid'])) $options[$ii]['attchAsVid'] = $pval['attchAsVid']; else $options[$ii]['attchAsVid'] = 0;
        
        if (isset($pval['apVKImgUpl']))     $options[$ii]['imgUpl'] = $pval['apVKImgUpl']; else $options[$ii]['imgUpl'] = 0;
        if (isset($pval['addBackLink']))     $options[$ii]['addBackLink'] = $pval['addBackLink']; else $options[$ii]['addBackLink'] = 0;
        
        if (isset($pval['msgFrmt']))    $options[$ii]['msgFrmt'] = trim($pval['msgFrmt']); 
        if (isset($pval['msgAFormat']))    $options[$ii]['msgAFormat'] = trim($pval['msgAFormat']); 
        
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
                
        if (isset($pval['url']))  {  $options[$ii]['url'] = trim($pval['url']);   if ( substr($options[$ii]['url'], 0, 4)!='http' )  $options[$ii]['url'] = 'http://'.$options[$ii]['url'];
          $vkPgID = $options[$ii]['url']; if (substr($vkPgID, -1)=='/') $vkPgID = substr($vkPgID, 0, -1);  $vkPgID = substr(strrchr($vkPgID, "/"), 1); 
          if (strpos($vkPgID, '?')!==false) $vkPgID = substr($vkPgID, 0, strpos($vkPgID, '?')); 
          $options[$ii]['vkPgID'] = $vkPgID; //echo $vkPgID;
          if (strpos($options[$ii]['url'], '?')!==false) $options[$ii]['url'] = substr($options[$ii]['url'], 0, strpos($options[$ii]['url'], '?'));// prr($pval); prr($options[$ii]); // die();
        }                  
      }
    } return $options;
  } 
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID; 
    foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapVK', true));  if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); $doVK = $ntOpt['doVK'] && $ntOpt['catSel']!='1';
        $isAvailVK =  $ntOpt['url']!='' && $ntOpt['vkAppID']!='' || $ntOpt['uPass']!=''; $isAttachVK = $ntOpt['attch']; $msgFrmt = htmlentities($ntOpt['msgFrmt'], ENT_COMPAT, "UTF-8"); $postType = $ntOpt['postType']; 
      ?>
      <tr><th style="text-align:left;" colspan="2"> <?php if ( $ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='' )  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_VK<?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>      
        <?php if ($isAvailVK) { ?><input class="nxsGrpDoChb" value="1" id="doVK<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="vk[<?php echo $ii; ?>][SNAPincludeVK]" <?php if (($post->post_status == "publish" && $ntOpt['isPosted'] == '1') || ($post->post_status != "publish" && ((int)$doVK == 1)) ) echo 'checked="checked" title="def"';  ?> /> <?php } ?>      
        <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/vk16.png);">vKontakte(VK) - <?php _e('publish to ', 'NS_SPAP'); ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th>
        <td><?php //## Only show RePost button if the post is "published"
        if ($post->post_status == "publish" && $isAvailVK) { ?>
          <input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToVK_repostButton" id="rePostToVK_button" value="<?php _e('Repost to vKontakte(VK)', 're-post') ?>" />
        <?php wp_nonce_field( 'rePostToVK', 'rePostToVK_wpnonce' ); } ?>
        <?php  if (is_array($pMeta) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID'])) { ?> <span id="pstdVK<?php echo $ii; ?>" style="float: right;padding-top: 4px; padding-right: 10px;">
             <a style="font-size: 10px;" href="http://vk.com/wall<?php echo $pMeta[$ii]['pgID']; ?>" target="_blank">Posted on vKontakte(VK) <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
           </span>
        <?php } ?>
        </td></tr>
          <?php if (!$isAvailVK) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup and Authorize your vKontakte(VK) Account to AutoPost to vKontakte(VK)</b>
          <?php } elseif ($post->post_status != "puZblish") {?> 
        <tr id="altFormat1" style=""><th scope="row" valign="top" style="text-align:right; width:60px; padding-right:10px;"><?php _e('Message Format:', 'NS_SPAP') ?></th>
          <td><input value="<?php echo $msgFrmt ?>" type="text" name="vk[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apVKTMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apVKTMsgFrmt".$ii); ?>
            <br/><div ><input value="0" type="hidden" name="vk[<?php echo $ii; ?>][addBackLink]" />
              <input value="1" type="checkbox" name="vk[<?php echo $ii; ?>][addBackLink]"  <?php if (isset($ntOpt['addBackLink']) && (int)$ntOpt['addBackLink'] == 1) echo "checked"; ?> /> Add backlink to the post
            </div>
        </td></tr>
        <tr><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 0px; padding-right:10px;"> Post Type: <br/>
          (<a id="showShAtt" style="font-weight: normal" onmouseout="hidePopShAtt('<?php echo $ii; ?>X');" onmouseover="showPopShAtt('<?php echo $ii; ?>X', event);" onclick="return false;" class="underdash" href="http://www.nextscripts.com/blog/">What's the difference?</a>)</th><td>     
          <input type="radio" name="vk[<?php echo $ii; ?>][PostType]" value="T" <?php if ($postType == 'T') echo 'checked="checked"'; ?> /> Text Post  - <i>just text message</i><br/>       
          <input type="radio" name="vk[<?php echo $ii; ?>][PostType]" value="I" <?php if ($postType == 'I') echo 'checked="checked"'; ?> /> Image Post - <i>big image with text message</i>       
          <?php if( function_exists("nxs_doPostToVK")) { ?> <br/> 
            <input type="radio" name="vk[<?php echo $ii; ?>][PostType]" value="A" <?php if ( !isset($postType) || $postType == '' || $postType == 'A') echo 'checked="checked"'; ?> /> Text Post with "attached" blogpost
          <?php } ?><br/><div class="popShAtt" id="popShAtt<?php echo $ii; ?>X"><h3>vKontakte(VK) Post Types</h3><img src="<?php echo $nxs_plurl; ?>img/fbPostTypesDiff6.png" width="600" height="398" alt="vKontakte(VK) Post Types"/></div>
        </td></tr><?php } 
    }
      
  }
  
  function adjMetaOpt($optMt, $pMeta){ if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = '';
     if (isset($pMeta['SNAPformat'])) $optMt['msgFrmt'] = $pMeta['SNAPformat'];    
     if (isset($pMeta['AttachPost'])) $optMt['attch'] = ($pMeta['AttachPost'] != '')?$pMeta['AttachPost']:0; else { if (isset($pMeta['SNAPformat'])) $optMt['attch'] = 0; } 
     if (isset($pMeta['addBackLink'])) $optMt['addBackLink'] = ($pMeta['addBackLink'] != '')?$pMeta['addBackLink']:0; else { if (isset($pMeta['SNAPformat'])) $optMt['addBackLink'] = 0; } 
     if (isset($pMeta['PostType'])) $optMt['postType'] = ($pMeta['PostType'] != '')?$pMeta['PostType']:0; else { if (isset($pMeta['SNAPformat'])) $optMt['postType'] = 'T'; } 
     if (isset($pMeta['SNAPincludeVK'])) $optMt['doVK'] = $pMeta['SNAPincludeVK'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doVK'] = 0; } 
     return $optMt;
  }
}}

if (!function_exists("nxs_rePostToVK_ajax")) { function nxs_rePostToVK_ajax() { check_ajax_referer('rePostToVK');  $postID = $_POST['id']; // $result = nsPublishTo($id, 'VK', true);   
    $options = get_option('NS_SNAutoPoster');  foreach ($options['vk'] as $ii=>$nto) if ($ii==$_POST['nid']) {  $nto['ii'] = $ii; $nto['pType'] = 'aj';
      $ntpo =  get_post_meta($postID, 'snapVK', true); /* echo $postID."|"; echo $fbpo; */ $ntpo =  maybe_unserialize($ntpo); // prr($ntpo); 
      if (is_array($ntpo) && isset($ntpo[$ii]) && is_array($ntpo[$ii]) ){ $ntClInst = new nxs_snapClassVK(); $nto = $ntClInst->adjMetaOpt($nto, $ntpo[$ii]); } //prr($nto);
      $result = nxs_doPublishToVK($postID, $nto); if ($result == 200) die("Successfully sent your post to vKontakte(VK)."); else die($result);
    }    
  }
}

if (!function_exists("nxs_getVKHeaders")) {  function nxs_getVKHeaders($ref, $post=false, $aj=false){ $hdrsArr = array(); 
 $hdrsArr['Cache-Control']='no-cache'; $hdrsArr['Connection']='keep-alive'; $hdrsArr['Referer']=$ref;
 $hdrsArr['User-Agent']='Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.45 Safari/537.17';
 if($post===true) $hdrsArr['Content-Type']='application/x-www-form-urlencoded'; 
 if($aj===true) $hdrsArr['X-Requested-With']='XMLHttpRequest'; 
 $hdrsArr['Accept']='text/html, application/xhtml+xml, */*'; $hdrsArr['DNT']='1';
 $hdrsArr['Accept-Encoding']='gzip,deflate'; $hdrsArr['Accept-Language']='en-US,en;q=0.8'; $hdrsArr['Accept-Charset']='ISO-8859-1,utf-8;q=0.7,*;q=0.3'; return $hdrsArr;
}}

if (!function_exists("nxs_uplImgtoVK")) {  function nxs_uplImgtoVK($imgURL, $options){
    $postUrl = 'https://api.vkontakte.ru/method/photos.getWallUploadServer?gid='.$options['pgIntID'].'&access_token='.$options['vkAppAuthToken'];
    $response = wp_remote_get($postUrl); $thumbUploadUrl = $response['body'];    
    if (!empty($thumbUploadUrl)) { $thumbUploadUrlObj = json_decode($thumbUploadUrl); $VKuploadUrl = $thumbUploadUrlObj->response->upload_url; }    
    if (!empty($VKuploadUrl)) {                               
      $remImgURL = urldecode($imgURL); $urlParced = pathinfo($remImgURL); $remImgURLFilename = $urlParced['basename']; $imgData = wp_remote_get($remImgURL); $imgData = $imgData['body'];        
      $tmp=array_search('uri', @array_flip(stream_get_meta_data($GLOBALS[mt_rand()]=tmpfile())));  
      rename($tmp, $tmp.='.png'); register_shutdown_function(create_function('', "unlink('{$tmp}');"));       
      file_put_contents($tmp, $imgData); 
      
      $ch = curl_init(); curl_setopt($ch, CURLOPT_URL, $VKuploadUrl); curl_setopt($ch, CURLOPT_POST, 1); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, array('photo' => '@' . $tmp)); $response = curl_exec($ch); $errmsg = curl_error($ch); curl_close($ch); 
        
      $uploadResultObj = json_decode($response);
      
      if (!empty($uploadResultObj->server) && !empty($uploadResultObj->photo) && !empty($uploadResultObj->hash)) {
        $postUrl = 'https://api.vkontakte.ru/method/photos.saveWallPhoto?server='.$uploadResultObj->server.'&photo='.$uploadResultObj->photo.'&hash='.$uploadResultObj->hash.'&gid='.$options['pgIntID'].'&access_token='.$options['vkAppAuthToken'];
        $response = wp_remote_get($postUrl);            
        $resultObject = json_decode($response['body']);// prr($resultObject);
        if (isset($resultObject) && isset($resultObject->response[0]->id)) { return $resultObject->response[0]; } else { return false; }
      }
   }
}}

if (!function_exists("nxs_doPublishToVK")) { //## Second Function to Post to VK
  function nxs_doPublishToVK($postID, $options){ global $ShownAds, $nxs_vkCkArray; $ntCd = 'VK'; $ntCdL = 'vk'; $ntNm = 'vKontakte(VK)';
      $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
      if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        nxs_addToLog($ntCd.' - '.$options['nName'], 'E', '-=Duplicate=- Post ID:'.$postID, 'Not posted. No reason for posting duplicate'); return;
      }
      $email = $options['uName'];  $pass = (substr($options['uPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['uPass'], 5)):$options['uPass']);      
      if ($postID=='0') { echo "Testing ... <br/><br/>"; $link = home_url(); $msg = 'Test Link from '.$link; } else { $post = get_post($postID); if(!$post) return;
        $msgFormat = $options['msgFrmt'];  $msg = nsFormatMessage($msgFormat, $postID); $link = get_permalink($postID); nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1'));
      } 
      $dusername = $options['uName'];  $postType = $options['postType'];  //$link = urlencode($link); $desc = urlencode(substr($msg, 0, 500));      
      $extInfo = ' | PostID: '.$postID." - ".$post->post_title; $logNT = '<span style="color:#000080">StumbleUpon</span> - '.$options['nName'];      
  
      $msgOpts = array(); $msgOpts['type'] = $postType; $msgOpts['uid'] =  $options['vkPgID']; $imgURL = nxs_getPostImage($postID);// if ($link!='') $msgOpts['link'] = $link;
      if ($postType=='I' && trim($imgURL)=='') $postType='T';
      if ($postType=='A' && $link!='') {  
        //## Login
        if (isset($options['vkSvC'])) $nxs_vkCkArray = maybe_unserialize( $options['vkSvC']); $loginError = true;
        if (is_array($nxs_vkCkArray)) $loginError = nxs_doCheckVK(); if ($loginError!=false) $loginError = nxs_doConnectToVK($email, $pass); 
        if (serialize($nxs_vkCkArray)!=$options['vkSvC']) { global $plgn_NS_SNAutoPoster;  $gOptions = $plgn_NS_SNAutoPoster->nxs_options;
          if (isset($options['ii']) && $options['ii']!=='')  { $gOptions['vk'][$options['ii']]['vkSvC'] = serialize($nxs_vkCkArray); update_option('NS_SNAutoPoster', $gOptions);  }
          else foreach ($gOptions['vk'] as $ii=>$gpn) { $result = array_diff($options, $gpn); 
            if (!is_array($result) || count($result)<1) { $gOptions['vk'][$ii]['vkSvC'] = serialize($nxs_vkCkArray); update_option('NS_SNAutoPoster', $gOptions); break; }
          }        
        }  
        if ($loginError!==false) {if ($postID=='0') prr($loginError); nxs_addToLog($logNT, 'E', '-=ERROR=- '.print_r($loginError, true)." - BAD USER/PASS", $extInfo); return " -= BAD USER/PASS =- ";}      
        //## Post
        if (trim($fbMsgAFormat)!='') {$dsc = nsFormatMessage($fbMsgAFormat, $postID);} else { if (function_exists('aioseop_mrt_fix_meta') && $dsc=='')  $dsc = trim(get_post_meta($postID, '_aioseop_description', true)); 
          if (function_exists('wpseo_admin_init') && $dsc=='') $dsc = trim(get_post_meta($postID, '_yoast_wpseo_opengraph-description', true));  
          if (function_exists('wpseo_admin_init') && $dsc=='') $dsc = trim(get_post_meta($postID, '_yoast_wpseo_metadesc', true));      
          if ($dsc=='') $dsc = trim(apply_filters('the_content', nxs_doQTrans($post->post_excerpt, $lng)));  if ($dsc=='') $dsc = trim(nxs_doQTrans($post->post_excerpt, $lng)); 
          if ($dsc=='') $dsc = trim(apply_filters('the_content', nxs_doQTrans($post->post_content, $lng)));  if ($dsc=='') $dsc = trim(nxs_doQTrans($post->post_content, $lng));  
          if ($dsc=='') $dsc = get_bloginfo('description'); 
        }  $dsc = strip_tags($dsc); $dsc = nxs_decodeEntitiesFull($dsc); $dsc = nsTrnc($dsc, 900, ' ');
          $msgOpts['url'] = $link; $msgOpts['urlTitle'] = nxs_doQTrans($post->post_title, $lng); $msgOpts['urlDesc'] = $dsc; $msgOpts['imgURL'] = $imgURL;   
        $ret = nxs_doPostToVK($msg, $options['url'], $msgOpts); //  prr($ret);
      }
      if ($postType=='I') { $imgUpld = nxs_uplImgtoVK($imgURL, $options); if (is_object($imgUpld)) { $imgID = $imgUpld->id; $atts[] = $imgID; }}
      if ($postType!='A') { if( $options['addBackLink']=='1') $atts[] = $link; 
      
        $atts = implode(',', $atts);
        $postUrl = 'https://api.vkontakte.ru/method/wall.post?owner_id='.$options['pgIntID'].'&access_token='.$options['vkAppAuthToken'].'&from_group=1&message='.urlencode($msg).'&attachment='.urlencode($atts); 
        $response = wp_remote_get($postUrl);
        if ( is_wp_error($response) || (is_object($response) && (isset($response->errors))) || (is_array($response) && stripos($response['body'],'"error":')!==false )) { prr($response); die(); }
         else { $respJ = json_decode($response['body'], true);  $ret = array("code"=>"OK", "post_id"=>$options['pgIntID'].'_'.$respJ['response']['post_id']);   }
          
      }                                           prr($ret);
      if (is_array($ret) && $ret['code']=='OK') {  if ($postID=='0')  { nxs_addToLog($logNT, 'M', 'OK - TEST Message Posted '); echo ' OK - Message Posted, please see your VK Page '; } else 
          { nxs_metaMarkAsPosted($postID, 'VK', $options['ii'], array('isPosted'=>'1', 'pgID'=>$ret['post_id'], 'pDate'=>date('Y-m-d H:i:s'))); nxs_addToLog($logNT, 'M', 'OK - Message Posted ', $extInfo);  return 200; }          
      } else {if ($postID=='0') prr($ret); nxs_addToLog($logNT, 'E', '-=ERROR=- '.print_r($ret, true), $extInfo);}       
  }
}
     /*
if (!function_exists("nxs_doPublishToVK")) { //## Second Function to Post to VK
  function nxs_doPublishToVK($postID, $options){ global $ShownAds; $ntCd = 'VK'; $ntCdL = 'vk'; $ntNm = 'vKontakte(VK)'; $dsc = ''; require_once ('apis/facebook.php'); 
    $vkWhere = 'feed'; $page_id = $options['vkPgID']; if (isset($ShownAds)) $ShownAdsL = $ShownAds;  
     
    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
    if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        nxs_addToLog($ntCd.' - '.$options['nName'], 'E', '-=Duplicate=- Post ID:'.$postID, 'Not posted. No reason for posting duplicate'); return;
    }     
  
    if (isset($options['qTLng'])) $lng = $options['qTLng']; else $lng = '';
    $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url();    
    
    $msg = 'Test To Post'; $link = home_url();        $imgURL = nxs_getPostImage($postID);
    $atts = array(); 
    
    if ($postType=='I' && trim($imgURL)=='') $postType='T';
    
    if ($postType=='I') { $imgUpld = nxs_uplImgtoVK($imgURL, $options); if (is_object($imgUpld)) { $imgID = $imgUpld->id; $atts[] = $imgID; }
    
    $atts[] = $link;
    $atts = implode(',',$atts);

    $postUrl = 'https://api.vkontakte.ru/method/wall.post?owner_id='.$options['pgIntID'].'&access_token='.$options['vkAppAuthToken'].'&from_group=1&message='.urlencode($msg).'&attachment='.urlencode($atts); prr($postUrl);
    $response = wp_remote_get($postUrl);
    if ( is_wp_error($response) || (is_object($response) && (isset($response->errors))) || (is_array($response) && stripos($response['body'],'"error":')!==false )) { prr($response); die(); }
      else $respJ = json_decode($response['body'], true); 
      
    prr($respJ);   die();
      
    
    
    if ($postID=='0') { echo "Testing ... <br/><br/>"; 
    $mssg = array('access_token'  => $options['fbAppPageAuthToken'], 'message' => 'Test Post', 'name' => 'Test Post', 'caption' => 'Test Post', 'link' => home_url(),
       'description' => 'test Post', 'actions' => array(array('name' => $blogTitle, 'link' => home_url())) ); 
    } else { $post = get_post($postID); if(!$post) return; $msgFrmt = $options['msgFrmt']; $msg = nsFormatMessage($msgFrmt, $postID); $msgAFormat = $options['msgAFormat'];
      $isAttachFB = $options['attch']; $postType = $options['postType']; $isAttachVidFB = $options['attchAsVid'];
      nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1')); 
      if (($isAttachFB=='1' || $isAttachFB=='2' || $postType=='A')) $imgURL = nxs_getPostImage($postID); // prr($options); echo "PP - ".$postID; prr($src);      
      if ($postType=='I') $imgURL = nxs_getPostImage($postID, 'full'); // prr($options); echo "PP - ".$postID; prr($src);            
      
      if (trim($msgAFormat)!='') {$dsc = nsFormatMessage($msgAFormat, $postID);} else { if (function_exists('aioseop_mrt_fix_meta') && $dsc=='')  $dsc = trim(get_post_meta($postID, '_aioseop_description', true)); 
        if (function_exists('wpseo_admin_init') && $dsc=='') $dsc = trim(get_post_meta($postID, '_yoast_wpseo_opengraph-description', true));  
        if (function_exists('wpseo_admin_init') && $dsc=='') $dsc = trim(get_post_meta($postID, '_yoast_wpseo_metadesc', true));      
        if ($dsc=='') $dsc = trim(apply_filters('the_content', nxs_doQTrans($post->post_excerpt, $lng)));  if ($dsc=='') $dsc = trim(nxs_doQTrans($post->post_excerpt, $lng)); 
        if ($dsc=='') $dsc = trim(apply_filters('the_content', nxs_doQTrans($post->post_content, $lng)));  if ($dsc=='') $dsc = trim(nxs_doQTrans($post->post_content, $lng));  
        if ($dsc=='') $dsc = get_bloginfo('description'); 
      }
      
      $dsc = strip_tags($dsc); $dsc = nxs_decodeEntitiesFull($dsc); $dsc = nsTrnc($dsc, 900, ' ');
      $postSubtitle = home_url();  $msg = str_replace('<br>', "\n", $msg); $msg = str_replace('<br/>', "\n", $msg); $msg = str_replace('<br />', "\n", $msg);  
      $msg = strip_tags($msg);  $msg = nxs_decodeEntitiesFull($msg);  $mssg = array('access_token'  => $options['fbAppPageAuthToken'], 'message' => $msg); //prr($imgURL);
      if ($postType=='I' && trim($imgURL)=='') $postType='T';
      if ($postType=='A' || $postType=='') {
        if (($isAttachFB=='1' || $isAttachFB=='2')) { $attArr = array('name' => nxs_doQTrans($post->post_title, $lng), 'caption' => $postSubtitle, 'link' => get_permalink($postID), 'description' => $dsc); $mssg = array_merge($mssg, $attArr); }      
        if ($isAttachFB=='1') $mssg['actions'] = array(array('name' => $blogTitle, 'link' => home_url()));        
        if (trim($imgURL)!='') $mssg['picture'] = $imgURL;
        if ($isAttachVidFB=='1') {$vids = nsFindVidsInPost($post); if (count($vids)>0) { 
          if (strlen($vids[0])==11) { $mssg['source'] = 'http://www.youtube.com/v/'.$vids[0]; $mssg['picture'] = 'http://img.youtube.com/vi/'.$vids[0].'/0.jpg'; }
          if (strlen($vids[0])==8) { $mssg['source'] = 'https://secure.vimeo.com/moogaloop.swf?clip_id='.$vids[0].'&autoplay=1';
            //$mssg['source'] = 'http://player.vimeo.com/video/'.$vids[0]; 
            $apiURL = "http://vimeo.com/api/v2/video/".$vids[0].".json?callback=showThumb"; $json = wp_remote_get($apiURL);
            if (!is_wp_error($json)) { $json = $json['body']; $json = str_replace('showThumb(','',$json); $json = str_replace('])',']',$json);  $json = json_decode($json, true); $mssg['picture'] = $json[0]['thumbnail_large']; }           
          }
        }}
      } elseif ($postType=='I') { $facebook->setFileUploadSupport(true); $fbWhere = 'photos'; $mssg['url'] = $imgURL; 
        if ($options['imgUpl']!='2') {
          $aacct = array('access_token'  => $options['fbAppPageAuthToken']);  $albums = $facebook->api("/$page_id/albums", "get", $aacct);
          foreach ($albums["data"] as $album) { if ($album["type"] == "wall") { $chosen_album = $album; break;}}
          if (isset($chosen_album) && isset($chosen_album["id"])) $page_id = $chosen_album["id"];
        }
        
      }
    } //  prr($mssg); // prr($options);  //   prr($facebook); echo "/$page_id/feed";
    if (isset($ShownAds)) $ShownAds = $ShownAdsL; // FIX for the quick-adsense plugin
    $extInfo = ' | PostID: '.$postID." - ".nxs_doQTrans($post->post_title, $lng); $logNT = '<span style="color:#0000FF">vKontakte(VK)</span> - '.$options['nName']; // prr($mssg);

    try { $ret = $facebook->api("/$page_id/".$fbWhere, "post", $mssg);} catch (NXS_FacebookApiException $e) { nxs_addToLog($logNT, 'E', '-=ERROR=- '.$e->getMessage(), $extInfo);
      if (stripos($e->getMessage(),'This API call requires a valid app_id')!==false) { $page_id = $options['fbPgID'];
        if ( !is_numeric($page_id) && stripos($options['url'], '/groups/')!=false) { $fbPgIDR = wp_remote_get('http://www.nextscripts.com/nxs.php?g='.$options['url']); 
          $fbPgIDR = trim($fbPgIDR['body']); $page_id = $fbPgIDR!=''?$fbPgIDR:$page_id;
        } try {  $page_info = $facebook->api("/$page_id?fields=access_token"); } catch (NXS_FacebookApiException $er2) { }
        if( !empty($page_info['access_token']) ) { $options['fbAppPageAuthToken'] = $page_info['access_token']; 
          nxs_addToLog($logNT, 'M', 'Personal Auth used instead of Page. Please re-authorize vKontakte(VK).');  
          try { $ret = $facebook->api("/$page_id/".$fbWhere,"post", $mssg); } catch (NXS_FacebookApiException $e) { nxs_addToLog($logNT, 'E', '-=ERROR 2=- '.$e->getMessage(), $extInfo);}
        } else { $rMsg = '-= ERROR =- (invalid app_id) Authorization Error. Your app is not authorized. Please go to the Plugin Settings - vKontakte(VK) and authorize it.<br/>'; 
          nxs_addToLog($logNT, 'E', $rMsg, $extInfo); return $rMsg.$e->getMessage();
        }
      }        
      if ($postID=='0') echo 'Error:',  $e->getMessage(), "\n";  return "Valid app_id ERROR:".$e->getMessage();      
    }  // prr($ret);
    if ($postID=='0') { prr($ret); if (isset($ret['id']) && $ret['id']!='') { echo 'OK - Message Posted, please see your vKontakte(VK) Page '; nxs_addToLog($logNT, 'M', 'Test Message Posted, please see your vKontakte(VK) Page'); }}
      else { if (isset($ret['id']) && $ret['id']!='') { 
          nxs_metaMarkAsPosted($postID, 'FB', $options['ii'],  array('isPosted'=>'1', 'pgID'=>$ret['id'], 'pDate'=>date('Y-m-d H:i:s')) ); nxs_addToLog($logNT, 'M', 'OK - Message Posted'.print_r($ret, true), $extInfo); 
        } else nxs_addToLog($logNT, 'E', '-=ERROR=- '.print_r($ret, true), $extInfo); 
      }
  }
}
    */
?>