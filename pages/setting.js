function wpcytxt_setting_redirect()
{
	window.location = "options-general.php?page=wp-cycle-text-announcement&ac=showcycle";
}

function wpcytxt_help()
{
	window.open("http://www.gopiplus.com/work/2012/04/07/wp-cycle-text-announcement-wordpress-plugin/");
}

function wpcytxt_setting_submit()
{
	if(document.wpcytxt_setting_form.wpcytxt_sname.value=="")
	{
		alert("Please select the setting name.")
		document.wpcytxt_setting_form.wpcytxt_sname.focus();
		return false;
	}
	else if(document.wpcytxt_setting_form.wpcytxt_slink.value=="")
	{
		alert("Please select the link option.")
		document.wpcytxt_setting_form.wpcytxt_slink.focus();
		return false;
	}
	else if(document.wpcytxt_setting_form.wpcytxt_sspeed.value=="" || isNaN(document.wpcytxt_setting_form.wpcytxt_sspeed.value))
	{
		alert("Please enter the slider speed, only number.")
		document.wpcytxt_setting_form.wpcytxt_sspeed.focus();
		return false;
	}
	else if(document.wpcytxt_setting_form.wpcytxt_stimeout.value=="" || isNaN(document.wpcytxt_setting_form.wpcytxt_stimeout.value))
	{
		alert("Please enter the slider timeout, only number.")
		document.wpcytxt_setting_form.wpcytxt_stimeout.focus();
		return false;
	}
	else if(document.wpcytxt_setting_form.wpcytxt_sdirection.value=="")
	{
		alert("Please select the slider direction")
		document.wpcytxt_setting_form.wpcytxt_sdirection.focus();
		return false;
	}
}

function wpcytxt_content_delete(id)
{
	if(confirm("Do you want to delete this record?"))
	{
		document.frm_wpcytxt_display.action="options-general.php?page=wp-cycle-text-announcement&ac=del&did="+id;
		document.frm_wpcytxt_display.submit();
	}
}	

function wpcytxt_content_redirect()
{
	window.location = "options-general.php?page=wp-cycle-text-announcement";
}

function wpcytxt_content_submit()
{
	if(document.wpcytxt_content_form.wpcytxt_ctitle.value=="")
	{
		alert("Please enter the announcement.")
		document.wpcytxt_content_form.wpcytxt_ctitle.focus();
		return false;
	}
	else if(document.wpcytxt_content_form.wpcytxt_clink.value=="")
	{
		alert("Please enter the link, if no link just enter #.")
		document.wpcytxt_content_form.wpcytxt_clink.focus();
		return false;
	}
	else if(document.wpcytxt_content_form.wpcytxt_csetting.value=="")
	{
		alert("Please select the setting.")
		document.wpcytxt_content_form.wpcytxt_csetting.focus();
		return false;
	}
	else if(document.wpcytxt_content_form.wpcytxt_cstartdate.value=="")
	{
		alert("Please enter the start date, YYYY-MM-DD.")
		document.wpcytxt_content_form.wpcytxt_cstartdate.focus();
		return false;
	}
	else if(document.wpcytxt_content_form.wpcytxt_cenddate.value=="")
	{
		alert("Please enter the end date, YYYY-MM-DD.")
		document.wpcytxt_content_form.wpcytxt_cenddate.focus();
		return false;
	}
}