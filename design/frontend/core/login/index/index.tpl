<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl">
	<head>

		<!-- begin: Meta information -->
			<title>{$SHOP_NAME} Admin</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<meta http-equiv="Author" content="Verison; http://verison.pl"/>
			<meta http-equiv="Description" content="Panel administracyjny systemu sklepowego Gekosale."/>
			<meta name="robots" content="noindex, nofollow"/>
			<link rel="shortcut icon" href="favicon.ico"/>
		<!-- end: Meta information -->

		<!-- begin: Stylesheet -->
			<link rel="stylesheet" href="{css_namespace css_file="style.css" mode="adminside"}" type="text/css"/>
		<!-- end: Stylesheet -->

		<!-- begin: Alternate stylesheets for IE -->
			<!--[if IE 7]>
			 <link rel="stylesheet" href="{css_namespace css_file="ie7style.css" mode="adminside"}" type="text/css"/>
			<![endif]-->
		<!-- end: Alternate stylesheets for IE -->

		<!-- begin: JS libraries and scripts inclusion -->
			<script type="text/javascript" src="{$DESIGNPATH}_js_libs/jquery-1.4.2.min.js"></script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_libs/xajax/xajax_core.js"></script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_libs/jquery-ui-1.7.2.custom.min.js"></script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_libs/jquery.easing.1.3.js"></script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_libs/jquery.scrollTo.min.js"></script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_libs/jquery.cookie.js"></script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_libs/jquery.dimensions.js"></script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_libs/jquery.gradient.js"></script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_libs/jquery.checkboxes.pack.js"></script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_libs/jquery.resize.js"></script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_libs/swfobject.js"></script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_libs/jquery.swfobject.js"></script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_libs/tiny_mce/jquery.tinymce.js"></script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_libs/colorpicker.js"></script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_libs/swfupload.js"></script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_libs/swfupload.queue.js"></script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_libs/jquery.swfupload.js"></script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_libs/json2.js"></script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_libs/base64.js"></script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_libs/jquery.rightClick.js"></script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_libs/daterangepicker/js/daterangepicker.jQuery.js"></script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_panel/core/gekosale.js"></script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_panel/core/gekosale.js"></script>
			<script type="text/javascript">
				{literal}
					/*<![CDATA[*/
						new GCore({
							iCookieLifetime: 30,
							sDesignPath: '{/literal}{$DESIGNPATH}{literal}',
							iActiveLanguage: '{/literal}{$language}{literal}',
							iActiveView: '0',
							aoViews: {/literal}{$views}{literal},
							aoLanguages: {/literal}{$languages}{literal},
							sUrl: '{/literal}{$URL}{literal}',
							sCurrentController: '{/literal}{$CURRENT_CONTROLLER}{literal}',
							sCurrentAction: '{/literal}{$CURRENT_ACTION}{literal}',
						});
					/*]]>*/
				{/literal}
			</script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_panel/core/init.js"></script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_panel/core/gf.js"></script>
			<script type="text/javascript" src="{$DESIGNPATH}_js_panel/core/pl_PL.js"></script>
			<script type="text/javascript">
				GF_Debug.s_iLevel = GF_Debug.LEVEL_ALL;
			</script>
		<!-- end: GexoFramework -->
		
		{$xajax}
		
		{if isset($error)}
			<script type="text/javascript">
				{literal}
				$(document).ready(function(){
					GError('{/literal}{trans}TXT_ERROR_OCCURED{/trans}{literal}', '{/literal}{$error}{literal}');
				});
				{/literal}
			</script>
		{/if}

		<script type="text/javascript">
		{literal}
			function gtKy(ev) {
			   var k = -1;
			   if (ev.which) {
			       k = ev.which;
			   } else if (ev.keyCode) {
			       k = ev.keyCode;
			   }
			   return k;
			}

			$(window).keypress(function(e) {
			    ev = e || window.event;
			    if (ev) {
			        var key=gtKy(ev);
			        var shift=false;
			        if (ev.shiftKey) {
			            shift=ev.shiftKey;
			        }else if (ev.modifiers) {
			            shift=!!(ev.modifiers&4);
			        }
			        if ((key >= 65 && key <= 90) ||    (key >= 96 && key <= 122)) {
			            if (((key >= 65 && key <= 90) && !shift) || ((key >= 96 && key <= 122) && shift)) {
			                $('#capslock').show();
			            } else {
			                $('#capslock').hide();
			            }
			        }
			    }
			return true;
			});

			$(window).keydown(function(e) {
			    ev = e || window.event;
			    if (ev) {
			        var key = gtKy(ev);
			        if (key == 20) {
			            $('#capslock').hide()
			        }
			    }
			    return true;
			});
			$(document).ready(function(){
				$("input[name='login']").focus();
			});
		{/literal}
		</script>
		
		<style>
		{literal}
		#capslock {
		    display:none;
		    background-color:#FAFFBD;
		    border:1px solid #333;
		    padding:.5em;
		    font-size:90%;
		    font-weight:bold;
		    width:20em;
		    border-radius:5px;
		    -moz-border-radius:5px;
		    -webkit-border-radius:5px;
		    text-align:center;
		    margin:0 auto;
		}
		{/literal}
		</style>
	</head>
	<body class="welcome-screen">
		
		<!-- begin: Header -->
			<div id="header">
				
				<div class="layout-container">

					<h1><a href="{$URL}" accesskey="0" title="{trans}TXT_RETURN_TO_DESKTOP{/trans}"><img src="data:image/png;base64,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" /></a></h1>

					<!-- begin: Shop name -->
						<div id="top-message">
							<p><a href="{$URL}">{$SHOP_NAME}</a></p>
						</div>
					<!-- end: Shop name -->

				</div>

			</div>
		<!-- end: Header -->

		<!-- begin: Message bar -->
			<div id="message-bar">

				<h2 class="aural">Wiadomości</h2>

			</div>
		<!-- end: Message bar -->

		<!-- begin: Content -->
			<div id="content" class="layout-container">
				
				<div id="capslock">Klawisz Caps Lock jest włączony</div>
				
				{fe_form form=$form render_mode="JS"}

			</div>
		<!-- end: Content -->

	</body>
</html>
