<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/atributes-edit.png" alt=""/>{trans}TXT_EDIT_ATTRIBUTE_GROUP{/trans}: {$currentGroup.name}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/index" class="button return"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-gray.png" title="{trans}TXT_ATTRIBUTE_GROUP_LIST{/trans}" alt="{trans}TXT_ATTRIBUTE_GROUP_LIST{/trans}"/></span></a></li>
 	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_ATTRIBUTE_GROUP{/trans}</span></a></li> 
	<li><a href="{$URL}{$CURRENT_CONTROLLER}" rel="delete" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/delete.png" alt=""/>{trans}TXT_DELETE_ATTRIBUTE_GROUP{/trans}</span></a></li>
	<li><a href="#edit_attributegroup" rel="reset" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/clean.png" alt=""/>{trans}TXT_START_AGAIN{/trans}</span></a></li>
	<li><a href="#edit_attributegroup" rel="submit" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/check.png" alt=""/>{trans}TXT_SAVE{/trans}</span></a></li>
</ul>

<script type="text/javascript">
	{literal}
		/*<![CDATA[*/
			GCore.OnLoad(function() {
				$('a[href="{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/add"]').click(function() {
					GPrompt('{/literal}{trans}TXT_ENTER_NEW_ATTRIBUTE_GROUP_NAME{/trans}{literal}', function(sName) {
						GCore.StartWaiting();
						xajax_AddGroup({
							name: sName
						}, GCallback(function(eEvent) {
							if (eEvent.id == undefined) {
								window.location = '{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/';
							}
							else {
								window.location = '{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/edit/' + eEvent.id;
							}
						}));
					});
					return false;
				});
				$('a[href="{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}"][rel="delete"]').click(function() {
					GWarning('{/literal}{trans}TXT_DO_YOU_REALLY_WANT_TO_DELETE_ATTRIBUTE_GROUP{/trans}{literal}', '{/literal}{trans}TXT_DO_YOU_REALLY_WANT_TO_DELETE_ATTRIBUTE_GROUP_DESCRIPTION{/trans}{literal}', {
						bAutoExpand: true,
						aoPossibilities: [
							{mLink: function() {
								GCore.StartWaiting();
								xajax_DeleteGroup({
									id: '{/literal}{$currentGroup.id}{literal}'
								}, GCallback(function(eEvent) {
									window.location = '{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/';
								}));
							}, sCaption: GForm.Language.tree_ok},
							{mLink: GAlert.DestroyThis, sCaption: GForm.Language.tree_cancel}
						]
					});
					return false;
				});
			});
		/*]]>*/
	{/literal}
</script>

<div class="block">
	<div class="scrollable-tabs">
		<ul>
			{foreach item=group from=$existingGroups}
				<li{if $currentGroup.id==$group.id} class="active"{/if}><a href="{$URL}{$CURRENT_CONTROLLER}/edit/{$group.id}">{$group.name}</a></li>
			{/foreach}
		</ul>
	</div>
	{fe_form form=$form render_mode="JS"}
</div>
  