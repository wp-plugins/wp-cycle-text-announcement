<div class="wrap">
  <?php
  	global $wpdb;
    @$mainurl = get_option('siteurl')."/wp-admin/options-general.php?page=wp-cycle-text-announcement/wp-cycle-text-announcement.php";
    @$DID=@$_GET["DID"];
    @$AC=@$_GET["AC"];
    @$submittext = "Insert Message";
	if($AC <> "DEL" and trim(@$_POST['wpcytxt_ctitle']) <>"")
    {
		if($_POST['wpcytxt_cid'] == "" )
		{
			$sql = "insert into ".WP_WPCYTXT_CONTENT.""
			. " set `wpcytxt_ctitle` = '" . mysql_real_escape_string(trim($_POST['wpcytxt_ctitle']))
			. "', `wpcytxt_clink` = '" . $_POST['wpcytxt_clink']
			. "', `wpcytxt_cstartdate` = '" . $_POST['wpcytxt_cstartdate']
			. "', `wpcytxt_cenddate` = '" . $_POST['wpcytxt_cenddate']
			. "', `wpcytxt_csetting` = '" . $_POST['wpcytxt_csetting']
			. "'";	
		}
		else
		{
			$sql = "update ".WP_WPCYTXT_CONTENT.""
			. " set `wpcytxt_ctitle` = '" . mysql_real_escape_string(trim($_POST['wpcytxt_ctitle']))
			. "', `wpcytxt_clink` = '" . $_POST['wpcytxt_clink']
			. "', `wpcytxt_cstartdate` = '" . $_POST['wpcytxt_cstartdate']
			. "', `wpcytxt_cenddate` = '" . $_POST['wpcytxt_cenddate']
			. "', `wpcytxt_csetting` = '" . $_POST['wpcytxt_csetting']
			. "' where `wpcytxt_cid` = '" . $_POST['wpcytxt_cid'] 
			. "'";	
		}
		$wpdb->get_results($sql);
    }
    
    if($AC=="DEL" && $DID > 0)
    {
        $wpdb->get_results("delete from ".WP_WPCYTXT_CONTENT." where wpcytxt_cid=".$DID);
    }
    
    if($DID<>"" and $AC <> "DEL")
    {
        $data = $wpdb->get_results("select * from ".WP_WPCYTXT_CONTENT." where wpcytxt_cid=$DID limit 1");
        if ( empty($data) ) 
        {
           echo "<div id='message' class='error'><p>No data available! use below form to create!</p></div>";
           return;
        }
        $data = $data[0];
        if ( !empty($data) ) $wpcytxt_cid_x = $data->wpcytxt_cid; 
        if ( !empty($data) ) $wpcytxt_ctitle_x = htmlspecialchars(stripslashes($data->wpcytxt_ctitle));
		if ( !empty($data) ) $wpcytxt_clink_x = htmlspecialchars(stripslashes($data->wpcytxt_clink));
        if ( !empty($data) ) $wpcytxt_cenddate_x = substr($data->wpcytxt_cenddate, 0, 10);
		if ( !empty($data) ) $wpcytxt_cstartdate_x = substr($data->wpcytxt_cstartdate, 0, 10);
		if ( !empty($data) ) $wpcytxt_csetting_x = $data->wpcytxt_csetting;
        $submittext = "Update Message";
    }
    ?>
  <h2>Wp cycle text announcement</h2>
  <script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/wp-cycle-text-announcement/setting.js"></script>
  <form name="wpcytxt_content_form" method="post" action="<?php echo $mainurl; ?>" onsubmit="return wpcytxt_content_submit()"  >
    <table width="100%">
      <tr>
        <td colspan="3" align="left" valign="middle">Enter the announcement:</td>
      </tr>
      <tr>
        <td colspan="3" align="left" valign="middle">
        <textarea name="wpcytxt_ctitle" id="wpcytxt_ctitle" cols="120" rows="3"><?php echo @$wpcytxt_ctitle_x; ?></textarea></td>
      </tr>
      <tr>
        <td colspan="3" align="left" valign="middle">Enter the link:</td>
      </tr>
      <tr>
        <td colspan="3" align="left" valign="middle"><input name="wpcytxt_clink" type="text" id="wpcytxt_clink" value="<?php echo @$wpcytxt_clink_x; ?>" size="130" /></td>
      </tr>
      <tr>
        <td align="left" valign="middle">Setting name:</td>
        <td align="left" valign="middle">Start date:</td>
        <td align="left" valign="middle">End date :</td>
      </tr>
      <tr>
        <td width="11%" align="left" valign="middle">
			<select name="wpcytxt_csetting" id="wpcytxt_csetting">
			<option value="">Select</option>
            <?php
            for($i=1; $i<=10; $i++)
			{
				if(@$wpcytxt_csetting_x == 'SETTING'.$i) 
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
        <td width="21%" align="left" valign="middle"><input name="wpcytxt_cstartdate" type="text" id="wpcytxt_cstartdate" value="<?php echo @$wpcytxt_cstartdate_x; ?>"  size="15" maxlength="10" /> (YYYY-MM-DD) </td>
        <td width="68%" align="left" valign="middle"><input name="wpcytxt_cenddate" type="text" id="wpcytxt_cenddate" value="<?php echo @$wpcytxt_cenddate_x; ?>" size="15" maxlength="10" />  (YYYY-MM-DD)</td>
      </tr>
      <tr>
        <td height="35" colspan="3" align="left" valign="bottom"><table width="100%">
            <tr>
              <td width="50%" align="left"><input name="publish" lang="publish" class="button-primary" value="<?php echo @$submittext?>" type="submit" />
                <input name="publish" lang="publish" class="button-primary" onclick="wpcytxt_content_redirect()" value="Cancel" type="button" />
              </td>
              <td width="50%" align="right">
			  <input name="text_management1" lang="text_management" class="button-primary" onClick="location.href='options-general.php?page=wp-cycle-text-announcement/wp-cycle-text-announcement.php'" value="Go to - Text Management" type="button" />
        	  <input name="setting_management1" lang="setting_management" class="button-primary" onClick="location.href='options-general.php?page=wp-cycle-text-announcement/cycle-setting.php'" value="Go to - Setting Management" type="button" />
			  <input name="Help1" lang="publish" class="button-primary" onclick="wpcytxt_help()" value="Help" type="button" />
			  </td>
            </tr>
          </table></td>
      </tr>
      <input name="wpcytxt_cid" id="wpcytxt_cid" type="hidden" value="<?php echo @$wpcytxt_cid_x; ?>">
    </table>
  </form>
  <div class="tool-box">
    <?php
	$data = $wpdb->get_results("select * from ".WP_WPCYTXT_CONTENT." order by wpcytxt_cstartdate");
	if ( empty($data) ) 
	{ 
		echo "<div id='message' class='error'>No data available! use below form to create!</div>";
		return;
	}
	?>
    <form name="wpcytxt_content_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th align="left" scope="col">No</th>
            <th align="left" scope="col">Announcement</th>
            <th align="left" scope="col">Setting</th>          
            <th align="left" scope="col">Start date</th>
            <th align="left" scope="col">End date</th>
            <th align="left" scope="col">Action</th>
          </tr>
        </thead>
        <?php 
        $i = 0;
        foreach ( $data as $data ) { 
		//echo date("Y-m-d")."<br>";
        ?>
        <tbody>
          <tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
            <td align="left" valign="middle"><?php echo(stripslashes($data->wpcytxt_cid)); ?></td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->wpcytxt_ctitle)); ?></td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->wpcytxt_csetting)); ?></td>
            <td align="left" valign="middle">
			<?php
			if($data->wpcytxt_cstartdate > date("Y-m-d"))
			{
				echo("<font color='#009900'>" . substr($data->wpcytxt_cstartdate, 0, 10 ) . "</font>");
			}
			else
			{
				echo(substr($data->wpcytxt_cstartdate, 0, 10));
			}
			?>
			</td>
            <td align="left" valign="middle">
			<?php
			if($data->wpcytxt_cenddate < date("Y-m-d"))
			{
				echo("<font color='#FF0000'>" . substr($data->wpcytxt_cenddate, 0, 10 ) . "</font>");
			}
			else
			{
				echo(substr($data->wpcytxt_cenddate, 0, 10));
			}
			?>
			</td>
            <td align="left" valign="middle"><a href="options-general.php?page=wp-cycle-text-announcement/wp-cycle-text-announcement.php&DID=<?php echo($data->wpcytxt_cid); ?>">Edit</a> &nbsp; <a onClick="javascript:wpcytxt_content_delete('<?php echo($data->wpcytxt_cid); ?>')" href="javascript:void(0);">Delete</a> </td>
          </tr>
        </tbody>
        <?php $i = $i+1; } ?>
      </table>
    </form>
  </div>
  <table width="100%">
    <tr>
      <td align="right">
	  <span style="float:left;">
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
