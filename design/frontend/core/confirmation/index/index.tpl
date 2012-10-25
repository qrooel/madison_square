<div class="layout-box">
	<div class="layout-box-header">
		<h3 style="cursor: pointer;">{trans}TXT_CONFIRMATION_ORDER{/trans}</h3>
	</div>
	<div class="layout-box-content">
			{if (isset($upateOrder) && $upateOrder == 1)}
				{if ($upateOrder) && $upateOrder == 1}
					{trans}TXT_CONFIRMED_ORDER{/trans}
				{else}
						{trans}TXT_ERROR_CONFIRMATION_ORDER{/trans}<br/>
						{trans}TXT_ERROR_CONFIRMATION_ORDER_INFO{/trans}
				{/if}
			{else}
				{trans}TXT_INVALID_LINK{/trans}
			{/if}
	
		<div class="buttons">
			<a href="{$URL}{seo controller=mainside}/" class="button"><span>{trans}TXT_BACK_TO_SHOPPING{/trans}</span></a>
		</div>	
	</div>
</div>
