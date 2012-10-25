<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/atributes-list.png" alt=""/>{trans}TXT_ATTRIBUTE_GROUPS{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_ATTRIBUTE_GROUP{/trans}</span></a></li>
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
			});
		/*]]>*/
	{/literal}
</script>

<div class="block">
	<div class="scrollable-tabs">
		<ul>
			{foreach item=group from=$existingGroups}
				<li><a href="{$URL}{$CURRENT_CONTROLLER}/edit/{$group.id}">{$group.name}</a></li>
			{/foreach}
		</ul>
	</div>
	<p>{trans}TXT_CHOOSE_ATTRIBUTE_GROUP_TO_EDIT{/trans}</p>
</div>
