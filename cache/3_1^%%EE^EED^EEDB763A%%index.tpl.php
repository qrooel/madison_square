<?php /* Smarty version 2.6.19, created on 2012-10-09 06:20:59
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/attributegroup/index/index.tpl */ ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/atributes-list.png" alt=""/>Grupy cech</h2>

<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/add" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/add.png" alt=""/>Dodaj grupę cech</span></a></li>
</ul>

<script type="text/javascript">
	<?php echo '
		/*<![CDATA[*/
			GCore.OnLoad(function() {
				$(\'a[href="'; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
<?php echo '/add"]\').click(function() {
					GPrompt(\''; ?>
Wprowadź nazwę dla nowej grupy cech<?php echo '\', function(sName) {
						GCore.StartWaiting();
						xajax_AddGroup({
							name: sName
						}, GCallback(function(eEvent) {
							if (eEvent.id == undefined) {
								window.location = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
<?php echo '/\';
							}
							else {
								window.location = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
<?php echo '/edit/\' + eEvent.id;
							}
						}));
					});
					return false;
				});
			});
		/*]]>*/
	'; ?>

</script>

<div class="block">
	<div class="scrollable-tabs">
		<ul>
			<?php $_from = $this->_tpl_vars['existingGroups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['group']):
?>
				<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/edit/<?php echo $this->_tpl_vars['group']['id']; ?>
"><?php echo $this->_tpl_vars['group']['name']; ?>
</a></li>
			<?php endforeach; endif; unset($_from); ?>
		</ul>
	</div>
	<p>Wybierz grupę cech do edycji</p>
</div>