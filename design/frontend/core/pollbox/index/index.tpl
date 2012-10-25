{if isset($poll.idpoll)}
	<p>{$poll.questions}</p>
	{if ($check) == 0}
		{if isset($poll.questions)}
			<form action="#" method="post" id="poll-{$poll.idpoll}">
				<ul>
					{section name=i loop=$poll.answers}
						<li>
							<label>
								<input type="radio" name="poll" id="poll[{$poll.answers[i].votes}]" value="1"/>
								<span>{$poll.answers[i].name}</span>
							</label>
						</li>
					{/section}
				</ul>
				{if isset($clientdata)}
					<span class="button"><span><input type="submit" value="{trans}TXT_SEND{/trans}"/></span></span>
					<p id="pool-span"></p>
				{else}
					<p>{trans}TXT_LOG_IN_TO_VOTE{/trans}</p>
				{/if}
			</form>
			
			<script type="text/javascript">
				{literal}
					/*<![CDATA[*/
						
						var submitPollAnswer = GEventHandler(function(eEvent) {
							var jSelected = $(this).find('input[type=radio]:checked');
							if (!jSelected.length) {
								alert('Najpierw wybierz odpowiedź!');
								eEvent.stopImmediatePropagation();
								return false;
							}
							var aMatches = jSelected.attr('id').match(/poll\[([^\]]+)\]/);
							xajax_setAnswersChecked(aMatches[1], {/literal}'{$poll.idpoll}'{literal});
							$(this).find('[type=submit], ul').fadeOut(150);
							$(this).find('.answers').fadeIn(150);
							eEvent.stopImmediatePropagation();
							return false;
						});
						
						GCore.OnLoad(function() {
							$('#poll-{/literal}{$poll.idpoll}{literal}').unbind('submit', submitPollAnswer).bind('submit', submitPollAnswer);
						});
						
					/*]]>*/
				{/literal}
			</script>
		{/if}
	{else}
		<dl>
			{section name=i loop=$answers}    
				<dt>{$answers[i].name}</dt><dd><span class="votes">{$answers[i].qty.qty}</span><span class="indicator" style="width: {$answers[i].qty.percentage}%"></span></dd>
			{/section}
		</dl>
	{/if}
{else}
	<p>{trans}TXT_POLL_DOES_NOT_EXIST{/trans}</p>
{/if}	