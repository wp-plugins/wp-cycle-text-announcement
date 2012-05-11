<div class="wrap">
  <?php
  	global $wpdb;
    @$mainurl = get_option('siteurl')."/wp-admin/options-general.php?page=wp-cycle-text-announcement/cycle-setting.php";
    @$DID=@$_GET["DID"];
    @$AC=@$_GET["AC"];
	if(trim(@$_POST['wpcytxt_sname']) <> "")
    {
		if(!$_POST['wpcytxt_sid'] == "" )
		{
			$sql = "update ".WP_WPCYTXT_SETTINGS.""
			. " set `wpcytxt_slink` = '" . trim($_POST['wpcytxt_slink'])
			. "', `wpcytxt_sdirection` = '" . trim($_POST['wpcytxt_sdirection'])
			. "', `wpcytxt_sspeed` = '" . trim($_POST['wpcytxt_sspeed'])
			. "', `wpcytxt_stimeout` = '" . trim($_POST['wpcytxt_stimeout'])
			. "' where `wpcytxt_sid` = '" . trim($_POST['wpcytxt_sid'] )
			. "'";	
			$wpdb->get_results($sql);
		}
    }
    
    if($DID <> "")
    {
        $data = $wpdb->get_results("select * from ".WP_WPCYTXT_SETTINGS." where wpcytxt_sid=$DID limit 1");
        if ( empty($data) ) 
        {
           echo "<div id='message' class='error'><p>No data available!</p></div>";
           return;
        }
        $data = $data[0];
        if ( !empty($data) ) $wpcytxt_sid_x = htmlspecialchars(stripslashes($data->wpcytxt_sid)); 
        if ( !empty($data) ) $wpcytxt_sname_x = htmlspecialchars(stripslashes($data->wpcytxt_sname));
		if ( !empty($data) ) $wpcytxt_slink_x = htmlspecialchars(stripslashes($data->wpcytxt_slink));
        if ( !empty($data) ) $wpcytxt_sdirection_x = htmlspecialchars(stripslashes($data->wpcytxt_sdirection));
		if ( !empty($data) ) $wpcytxt_sspeed_x = htmlspecialchars(stripslashes($data->wpcytxt_sspeed));
		if ( !empty($data) ) $wpcytxt_stimeout_x = htmlspecialchars(stripslashes($data->wpcytxt_stimeout));
    }
    ?>
  <h2>Wp cycle text announcement setting</h2>
  <script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/wp-cycle-text-announcement/setting.js"></script>
  <form name="wpcytxt_setting_form" method="post" action="<?php echo $mainurl; ?>" onsubmit="return wpcytxt_setting_submit()"  >
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td align="left">Setting name</td>
      </tr>
      <tr>
        <td align="left">
		<select name="wpcytxt_sname" id="wpcytxt_sname">
			<option value=""></option>
            <?php
            for($i=1; $i<=10; $i++)
			{
				if(@$wpcytxt_sname_x == 'SETTING'.$i) 
				{ 
					$selected = 'selected' ; 
				}
				else
				{
					$selected = '' ; 
				}
				echo "<option value='SETTING".$i."' $selected>SETTING".$i."</option>";
			}
			?>
          </select>
		</td>
      </tr>
      <tr>
        <td align="left">Link</td>
      </tr>
      <tr>
        <td align="left">
		<select name="wpcytxt_slink" id="wpcytxt_slink">
            <option value=""></option>
            <option value='_blank' <?php if(@$wpcytxt_slink_x=='_blank') { echo 'selected' ; } ?>>Open in new window</option>
            <option value='_self' <?php if(@$wpcytxt_slink_x=='_self') { echo 'selected' ; } ?>>Open in same window</option>
          </select>
		</td>
      </tr>
      <tr>
        <td align="left">Speed</td>
      </tr>
      <tr>
        <td align="left"><input name="wpcytxt_sspeed" type="text" id="wpcytxt_sspeed" value="<?php echo @$wpcytxt_sspeed_x; ?>" maxlength="5" /> (Ex: 700)</td>
      </tr>
      <tr>
        <td align="left">Timeout</td>
      </tr>
      <tr>
        <td align="left"><input name="wpcytxt_stimeout" type="text" id="wpcytxt_stimeout" value="<?php echo @$wpcytxt_stimeout_x; ?>" maxlength="5" /> (Ex: 5000)</td>
      </tr>
      <tr>
        <td align="left">Direction</td>
      </tr>
      <tr>
        <td align="left"><select name="wpcytxt_sdirection" id="wpcytxt_sdirection">
            <option value=""></option>
            <option value='scrollLeft' <?php if(@$wpcytxt_sdirection_x=='scrollLeft') { echo 'selected' ; } ?>>scrollLeft</option>
            <option value='scrollRight' <?php if(@$wpcytxt_sdirection_x=='scrollRight') { echo 'selected' ; } ?>>scrollRight</option>
            <option value='scrollUp' <?php if(@$wpcytxt_sdirection_x=='scrollUp') { echo 'selected' ; } ?>>scrollUp</option>
            <option value='scrollDown' <?php if(@$wpcytxt_sdirection_x=='scrollDown') { echo 'selected' ; } ?>>scrollDown</option>
          </select>
        </td>
      </tr>
      <tr>
        <td align="left">
			<?php  if($DID <> "") { ?>
			<input name="publish" lang="publish" class="button-primary" value="Update Setting" type="submit" />
			<input name="publish" lang="publish" class="button-primary" onclick="wpcytxt_setting_redirect()" value="Cancel" type="button" />
			<?php } ?>
			<span style="float:right;">
			<input name="text_management" lang="text_management" class="button-primary" onClick="location.href='options-general.php?page=wp-cycle-text-announcement/wp-cycle-text-announcement.php'" value="Go to - Text Management" type="button" />
			<input name="setting_management" lang="setting_management" class="button-primary" onClick="location.href='options-general.php?page=wp-cycle-text-announcement/cycle-setting.php'" value="Go to - Setting Management" type="button" />
			<input name="Help1" lang="publish" class="button-primary" onclick="wpcytxt_help()" value="Help" type="button" />
			</span>
		</td>
      </tr>  
    </table>
    <input name="wpcytxt_sid" id="wpcytxt_sid" type="hidden" value="<?php echo @$wpcytxt_sid_x; ?>">
  </form>
  <div class="tool-box">
    <?php
	$data = $wpdb->get_results("select * from ".WP_WPCYTXT_SETTINGS." order by wpcytxt_sid");
	if ( empty($data) ) 
	{ 
		echo "<div id='message' class='error'>No data available</div>";
		return;
	}
	?>
    <form name="wpcytxt_Display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th align="left" scope="col">No</th>
            <th align="left" scope="col">Setting name</th>
			<th align="left" scope="col">Short code</th>
			<th align="left" scope="col">Link</th>
            <th align="left" scope="col">Speed</th>
			<th align="left" scope="col">Timeout</th>
            <th align="left" scope="col">Direction</th>
			<th align="left" scope="col">Action</th>
          </tr>
        </thead>
        <?php 
        $i = 0;
        foreach ( $data as $data ) { 
        ?>
        <tbody>
          <tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
            <td align="left" valign="middle"><?php echo(stripslashes($data->wpcytxt_sid)); ?></td>
			<td align="left" valign="middle"><?php echo(stripslashes($data->wpcytxt_sname)); ?></td>
            <td align="left" valign="middle">[CYCLE-TEXT=<?php echo(stripslashes($data->wpcytxt_sname)); ?>]</td>
			<td align="left" valign="middle"><?php if(($data->wpcytxt_slink) == "_blank") { echo "Open in new window"; } else { echo "Open in same window"; } ?></td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->wpcytxt_sspeed)); ?></td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->wpcytxt_stimeout)); ?></td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->wpcytxt_sdirection)); ?></td>
            <td align="left" valign="middle"><a href="options-general.php?page=wp-cycle-text-announcement/cycle-setting.php&DID=<?php echo($data->wpcytxt_sid); ?>">Click to edit</a></td>
          </tr>
        </tbody>
        <?php $i = $i+1; } ?>
      </table>
    </form>
  </div>
  <table width="100%">
    <tr>
      <td align="right">
	  <span style="float:left;vertical-align:top;">
	  <ul>
	  <li>Check official website for live demo and more help <a href="http://www.gopiplus.com/work/2012/04/07/wp-cycle-text-announcement-wordpress-plugin/" target="_blank">click here</a></li>
	  </ul>
	  </span>
	  <input name="text_management" lang="text_management" class="button-primary" onClick="location.href='options-general.php?page=wp-cycle-text-announcement/wp-cycle-text-announcement.php'" value="Go to - Text Management" type="button" />
      <input name="setting_management" lang="setting_management" class="button-primary" onClick="location.href='options-general.php?page=wp-cycle-text-announcement/cycle-setting.php'" value="Go to - Setting Management" type="button" />
	  <input name="Help" lang="publish" class="button-primary" onclick="wpcytxt_help()" value="Help" type="button" />
      </td>
    </tr>
  </table>
</div>