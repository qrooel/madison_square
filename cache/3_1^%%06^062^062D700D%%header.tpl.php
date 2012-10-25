<?php /* Smarty version 2.6.19, created on 2012-10-25 10:05:15
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square/design/_tpl/frontend/core/header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'substr', '/home/qrooel/public_html/ac.vipserv.org/madison_square/design/_tpl/frontend/core/header.tpl', 11, false),array('function', 'css_layout', '/home/qrooel/public_html/ac.vipserv.org/madison_square/design/_tpl/frontend/core/header.tpl', 15, false),array('function', 'css_namespace', '/home/qrooel/public_html/ac.vipserv.org/madison_square/design/_tpl/frontend/core/header.tpl', 16, false),array('function', 'seo', '/home/qrooel/public_html/ac.vipserv.org/madison_square/design/_tpl/frontend/core/header.tpl', 53, false),array('function', 'seo_js', '/home/qrooel/public_html/ac.vipserv.org/madison_square/design/_tpl/frontend/core/header.tpl', 88, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl">
<head>
	<title><?php if ($this->_tpl_vars['metadata']['keyword_title'] != ''): ?><?php echo $this->_tpl_vars['metadata']['keyword_title']; ?>
<?php endif; ?><?php if ($this->_tpl_vars['CURRENT_CONTROLLER'] != 'mainside'): ?> - <?php echo $this->_tpl_vars['SHOP_NAME']; ?>
<?php endif; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta http-equiv="Author" content="Gekosale; http://www.gekosale.pl"/>
	<meta name="description" content="<?php echo $this->_tpl_vars['metadata']['keyword_description']; ?>
"/>
	<meta name="keywords" content="<?php echo $this->_tpl_vars['metadata']['keyword']; ?>
"/>
	<meta name="robots" content="all" />
	<meta name="revisit-after" content="1 Day" />
	<meta http-equiv="content-language" content="<?php echo ((is_array($_tmp=$this->_tpl_vars['languageCode'])) ? $this->_run_mod_handler('substr', true, $_tmp, 0, 2) : substr($_tmp, 0, 2)); ?>
"/>
	<link rel="shortcut icon" href="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/logos/<?php echo $this->_tpl_vars['FAVICON']; ?>
"/>
	<meta http-equiv="X-UA-Compatible" content="IE=9" />
	<!--[if !(lt IE 7)]><!-->
	<link rel="stylesheet" href="<?php echo smarty_function_css_layout(array(), $this);?>
" type="text/css"/>
	<link rel="stylesheet" href="<?php echo smarty_function_css_namespace(array('css_file' => "style.css",'mode' => 'frontend'), $this);?>
" type="text/css"/>
	<style>
	<?php echo $this->_tpl_vars['logoCSS']; ?>

	</style>
	<!--<![endif]-->
	<!--[if lt IE 7]>
	<link rel="stylesheet" href="<?php echo smarty_function_css_namespace(array('css_file' => "ie6style.css",'mode' => 'frontend'), $this);?>
" type="text/css"/>
	<![endif]-->
	<!--[if IE 7]>
	<link rel="stylesheet" href="<?php echo smarty_function_css_namespace(array('css_file' => "ie7style.css",'mode' => 'frontend'), $this);?>
" type="text/css"/>
	<![endif]-->
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_js_libs/gekosale.libs.min.js"></script>
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_js_frontend/core/gekosale.js"></script>
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_js_frontend/core/init.js"></script>
	<?php if ($this->_tpl_vars['gacode'] != ''): ?>
	<script type="text/javascript">
	<?php echo '
	    var _gaq = _gaq || [];
	    _gaq.push([\'_setAccount\', \''; ?>
<?php echo $this->_tpl_vars['gacode']; ?>
<?php echo '\']);
	    _gaq.push([\'_trackPageview\']);
	    _gaq.push([\'_trackPageLoadTime\']);
	'; ?>

	</script>
	<?php endif; ?>
	<script type="text/javascript">
	<?php echo '
	/*<![CDATA[*/
		new GCore({
			iCookieLifetime: 30,
			sDesignPath: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '\',
			sController: \''; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
<?php echo '\',
			sCartRedirect: \''; ?>
<?php echo $this->_tpl_vars['cartredirect']; ?>
<?php echo '\'
		});

		$(document).ready(function(){
			$(\'#product-search\').submit(function(){
				var query = Base64.encode($(\'#product-search-phrase\').val());
				var url = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productsearch'), $this);?>
<?php echo '/\' + query;
				window.location.href = url;
				return false;
			});
		});
	/*]]>*/
	'; ?>

	</script>
	<?php echo $this->_tpl_vars['xajax']; ?>

	<?php if (isset ( $this->_tpl_vars['error'] )): ?>
	<script type="text/javascript">
	<?php echo '
		$(document).ready(function(){
			GError(\''; ?>
<?php echo $this->_tpl_vars['error']; ?>
<?php echo '\');
		});
	'; ?>

	</script>
	<?php endif; ?>
</head>
<?php flush() ?>
<body class="body">
	<div id="message-bar"></div>
	<div id="main-container">
		<div id="header">
			<?php if ($this->_tpl_vars['CURRENT_CONTROLLER'] == 'mainside'): ?>
			<h1><a href="<?php echo $this->_tpl_vars['URL']; ?>
" class="logo" title="<?php echo $this->_tpl_vars['SHOP_NAME']; ?>
"><?php echo $this->_tpl_vars['SHOP_NAME']; ?>
</a></h1>
			<?php else: ?>
			<a href="<?php echo $this->_tpl_vars['URL']; ?>
" class="logo" title="<?php echo $this->_tpl_vars['SHOP_NAME']; ?>
"><?php echo $this->_tpl_vars['SHOP_NAME']; ?>
</a>
			<?php endif; ?>
				
			<?php if ($this->_tpl_vars['catalogmode'] == 0): ?>
			<div id="header-cart-summary">
			<?php if (isset ( $this->_tpl_vars['clientdata'] )): ?>
				<p>Witaj!, <strong><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'clientsettings'), $this);?>
/"><?php if (isset ( $this->_tpl_vars['client']['firstname'] )): ?><?php echo $this->_tpl_vars['client']['firstname']; ?>
 <?php echo $this->_tpl_vars['client']['surname']; ?>
<?php else: ?><?php echo $this->_tpl_vars['clientdata']['firstname']; ?>
 <?php echo $this->_tpl_vars['clientdata']['surname']; ?>
<?php endif; ?></a></strong> (<a href="logout;">Wyloguj</a>)</p>
			<?php else: ?>
				<p><span class="<?php echo smarty_function_seo_js(array('controller' => 'clientlogin'), $this);?>
">Logowanie</span> | <span class="<?php echo smarty_function_seo_js(array('controller' => 'registrationcart'), $this);?>
">Rejestracja</span></p>
			<?php endif; ?>
				<p id="cart-preview"><?php echo $this->_tpl_vars['cartpreview']; ?>
</p>
			</div>
			<?php endif; ?>
		</div>

		<div id="horizontal-navigation">
			<div>
				<ul>
					<li <?php if ($this->_tpl_vars['CURRENT_CONTROLLER'] == 'mainside'): ?>class="active"<?php endif; ?>><a href="<?php echo $this->_tpl_vars['URL']; ?>
">Strona główna</a></li>
					<li <?php if ($this->_tpl_vars['CURRENT_CONTROLLER'] == 'productpromotion'): ?>class="active"<?php endif; ?>><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productpromotion'), $this);?>
/">Promocje</a></li>
					<li <?php if ($this->_tpl_vars['CURRENT_CONTROLLER'] == 'productnews'): ?>class="active"<?php endif; ?>><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productnews'), $this);?>
/">Nowości</a></li>
					<?php if (isset ( $this->_tpl_vars['contentcategory'] )): ?>
					<?php unset($this->_sections['cat']);
$this->_sections['cat']['name'] = 'cat';
$this->_sections['cat']['loop'] = is_array($_loop=$this->_tpl_vars['contentcategory']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['cat']['show'] = true;
$this->_sections['cat']['max'] = $this->_sections['cat']['loop'];
$this->_sections['cat']['step'] = 1;
$this->_sections['cat']['start'] = $this->_sections['cat']['step'] > 0 ? 0 : $this->_sections['cat']['loop']-1;
if ($this->_sections['cat']['show']) {
    $this->_sections['cat']['total'] = $this->_sections['cat']['loop'];
    if ($this->_sections['cat']['total'] == 0)
        $this->_sections['cat']['show'] = false;
} else
    $this->_sections['cat']['total'] = 0;
if ($this->_sections['cat']['show']):

            for ($this->_sections['cat']['index'] = $this->_sections['cat']['start'], $this->_sections['cat']['iteration'] = 1;
                 $this->_sections['cat']['iteration'] <= $this->_sections['cat']['total'];
                 $this->_sections['cat']['index'] += $this->_sections['cat']['step'], $this->_sections['cat']['iteration']++):
$this->_sections['cat']['rownum'] = $this->_sections['cat']['iteration'];
$this->_sections['cat']['index_prev'] = $this->_sections['cat']['index'] - $this->_sections['cat']['step'];
$this->_sections['cat']['index_next'] = $this->_sections['cat']['index'] + $this->_sections['cat']['step'];
$this->_sections['cat']['first']      = ($this->_sections['cat']['iteration'] == 1);
$this->_sections['cat']['last']       = ($this->_sections['cat']['iteration'] == $this->_sections['cat']['total']);
?>
					<?php if ($this->_tpl_vars['contentcategory'][$this->_sections['cat']['index']]['header'] == 1): ?>
					<li <?php if ($this->_tpl_vars['CURRENT_CONTROLLER'] == 'staticcontent' && $this->_tpl_vars['CURRENT_PARAM'] == $this->_tpl_vars['contentcategory'][$this->_sections['cat']['index']]['id']): ?>class="active"<?php endif; ?>><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'staticcontent'), $this);?>
/<?php echo $this->_tpl_vars['contentcategory'][$this->_sections['cat']['index']]['id']; ?>
/<?php echo $this->_tpl_vars['contentcategory'][$this->_sections['cat']['index']]['seo']; ?>
"><?php echo $this->_tpl_vars['contentcategory'][$this->_sections['cat']['index']]['name']; ?>
</a>
					<?php if (count ( $this->_tpl_vars['contentcategory'][$this->_sections['cat']['index']]['children'] ) > 0): ?>
						<ul>
						<?php unset($this->_sections['under']);
$this->_sections['under']['name'] = 'under';
$this->_sections['under']['loop'] = is_array($_loop=$this->_tpl_vars['contentcategory'][$this->_sections['cat']['index']]['children']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['under']['show'] = true;
$this->_sections['under']['max'] = $this->_sections['under']['loop'];
$this->_sections['under']['step'] = 1;
$this->_sections['under']['start'] = $this->_sections['under']['step'] > 0 ? 0 : $this->_sections['under']['loop']-1;
if ($this->_sections['under']['show']) {
    $this->_sections['under']['total'] = $this->_sections['under']['loop'];
    if ($this->_sections['under']['total'] == 0)
        $this->_sections['under']['show'] = false;
} else
    $this->_sections['under']['total'] = 0;
if ($this->_sections['under']['show']):

            for ($this->_sections['under']['index'] = $this->_sections['under']['start'], $this->_sections['under']['iteration'] = 1;
                 $this->_sections['under']['iteration'] <= $this->_sections['under']['total'];
                 $this->_sections['under']['index'] += $this->_sections['under']['step'], $this->_sections['under']['iteration']++):
$this->_sections['under']['rownum'] = $this->_sections['under']['iteration'];
$this->_sections['under']['index_prev'] = $this->_sections['under']['index'] - $this->_sections['under']['step'];
$this->_sections['under']['index_next'] = $this->_sections['under']['index'] + $this->_sections['under']['step'];
$this->_sections['under']['first']      = ($this->_sections['under']['iteration'] == 1);
$this->_sections['under']['last']       = ($this->_sections['under']['iteration'] == $this->_sections['under']['total']);
?>
						<?php if ($this->_tpl_vars['contentcategory'][$this->_sections['cat']['index']]['children'][$this->_sections['under']['index']]['header'] == 1): ?>
							<li><a class="active<?php if ($this->_tpl_vars['CURRENT_CONTROLLER'] == 'staticcontent' && $this->_tpl_vars['CURRENT_PARAM'] == $this->_tpl_vars['contentcategory'][$this->_sections['cat']['index']]['children'][$this->_sections['under']['index']]['id']): ?> current<?php endif; ?>" href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'staticcontent'), $this);?>
/<?php echo $this->_tpl_vars['contentcategory'][$this->_sections['cat']['index']]['children'][$this->_sections['under']['index']]['id']; ?>
/<?php echo $this->_tpl_vars['contentcategory'][$this->_sections['cat']['index']]['children'][$this->_sections['under']['index']]['seo']; ?>
"><?php echo $this->_tpl_vars['contentcategory'][$this->_sections['cat']['index']]['children'][$this->_sections['under']['index']]['name']; ?>
</a></li>
						<?php endif; ?>
						<?php endfor; endif; ?>
						</ul>
					<?php endif; ?>
					</li>
					<?php endif; ?>
					<?php endfor; endif; ?>
					<?php endif; ?>
					<li <?php if ($this->_tpl_vars['CURRENT_CONTROLLER'] == 'contact'): ?>class="active"<?php endif; ?>><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'contact'), $this);?>
/">Kontakt</a></li>
				</ul>
			</div>
			<form id="product-search" action="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productsearch'), $this);?>
" method="post">
				<input id="product-search-phrase" name="q" type="text" value="Szukaj produktu..." />
			</form>
		</div>
		<div class="subheader">
			<div id="breadcrumbs">
			    <strong>Tu jesteś:</strong>
			    <ul>
			    <?php unset($this->_sections['b']);
$this->_sections['b']['name'] = 'b';
$this->_sections['b']['loop'] = is_array($_loop=$this->_tpl_vars['breadcrumb']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['b']['show'] = true;
$this->_sections['b']['max'] = $this->_sections['b']['loop'];
$this->_sections['b']['step'] = 1;
$this->_sections['b']['start'] = $this->_sections['b']['step'] > 0 ? 0 : $this->_sections['b']['loop']-1;
if ($this->_sections['b']['show']) {
    $this->_sections['b']['total'] = $this->_sections['b']['loop'];
    if ($this->_sections['b']['total'] == 0)
        $this->_sections['b']['show'] = false;
} else
    $this->_sections['b']['total'] = 0;
if ($this->_sections['b']['show']):

            for ($this->_sections['b']['index'] = $this->_sections['b']['start'], $this->_sections['b']['iteration'] = 1;
                 $this->_sections['b']['iteration'] <= $this->_sections['b']['total'];
                 $this->_sections['b']['index'] += $this->_sections['b']['step'], $this->_sections['b']['iteration']++):
$this->_sections['b']['rownum'] = $this->_sections['b']['iteration'];
$this->_sections['b']['index_prev'] = $this->_sections['b']['index'] - $this->_sections['b']['step'];
$this->_sections['b']['index_next'] = $this->_sections['b']['index'] + $this->_sections['b']['step'];
$this->_sections['b']['first']      = ($this->_sections['b']['iteration'] == 1);
$this->_sections['b']['last']       = ($this->_sections['b']['iteration'] == $this->_sections['b']['total']);
?>
					<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['breadcrumb'][$this->_sections['b']['index']]['link']; ?>
" title="<?php echo $this->_tpl_vars['breadcrumb'][$this->_sections['b']['index']]['title']; ?>
"><?php echo $this->_tpl_vars['breadcrumb'][$this->_sections['b']['index']]['title']; ?>
</a></li>
				<?php endfor; endif; ?>
			    </ul>
			</div>
			<?php if (isset ( $this->_tpl_vars['currencies'] ) && ! empty ( $this->_tpl_vars['currencies'] ) && count ( $this->_tpl_vars['currencies'] ) > 1): ?>
			<div id="currencies-selector">
				<div class="field-select"> 
					<label for="currencies">Waluta:</label>
					<span class="field"> 
						<select id="currencies" onchange="xajax_changeCurrency(this.value);">
					 	<?php $_from = $this->_tpl_vars['currencies']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['currency']):
?>
							<?php if (isset ( $this->_tpl_vars['currency']['selected'] ) && $this->_tpl_vars['currency']['selected'] == 1): ?>
					 		<option value="<?php echo $this->_tpl_vars['currency']['id']; ?>
" selected="selected"><?php echo $this->_tpl_vars['currency']['name']; ?>
</option>
							<?php else: ?>
							<option value="<?php echo $this->_tpl_vars['currency']['id']; ?>
"><?php echo $this->_tpl_vars['currency']['name']; ?>
</option>
							<?php endif; ?>
						<?php endforeach; endif; unset($_from); ?>
						</select>
					</span> 
                </div>
			</div>
			<?php endif; ?>
			<?php if (isset ( $this->_tpl_vars['languageFlag'] ) && ! empty ( $this->_tpl_vars['languageFlag'] ) && count ( $this->_tpl_vars['languageFlag'] ) > 1): ?>
			<div id="languages-selector">
           		<div class="field-select"> 
					<label for="languages">Język:</label>
					<span class="field"> 
						<select id="languages" onchange="xajax_changeLanguage(this.value);" >
					 	<?php $_from = $this->_tpl_vars['languageFlag']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['languages']):
?>
					 		<?php if (isset ( $this->_tpl_vars['language'] ) && $this->_tpl_vars['languages']['id'] == $this->_tpl_vars['language']): ?>
				 			<option value="<?php echo $this->_tpl_vars['languages']['id']; ?>
" selected="selected"><?php echo $this->_tpl_vars['languages']['name']; ?>
</option>
					 		<?php else: ?>
				 			<option value="<?php echo $this->_tpl_vars['languages']['id']; ?>
"><?php echo $this->_tpl_vars['languages']['name']; ?>
</option>
					 		<?php endif; ?>
						<?php endforeach; endif; unset($_from); ?>
						</select>
					</span> 
           		</div> 
			</div>
			<?php endif; ?>
		</div>