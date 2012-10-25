<?php /* Smarty version 2.6.19, created on 2012-10-08 09:35:24
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/pollbox/index/index.tpl */ ?>
<?php if (isset ( $this->_tpl_vars['poll']['idpoll'] )): ?>
	<p><?php echo $this->_tpl_vars['poll']['questions']; ?>
</p>
	<?php if (( $this->_tpl_vars['check'] ) == 0): ?>
		<?php if (isset ( $this->_tpl_vars['poll']['questions'] )): ?>
			<form action="#" method="post" id="poll-<?php echo $this->_tpl_vars['poll']['idpoll']; ?>
">
				<ul>
					<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['poll']['answers']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
						<li>
							<label>
								<input type="radio" name="poll" id="poll[<?php echo $this->_tpl_vars['poll']['answers'][$this->_sections['i']['index']]['votes']; ?>
]" value="1"/>
								<span><?php echo $this->_tpl_vars['poll']['answers'][$this->_sections['i']['index']]['name']; ?>
</span>
							</label>
						</li>
					<?php endfor; endif; ?>
				</ul>
				<?php if (isset ( $this->_tpl_vars['clientdata'] )): ?>
					<span class="button"><span><input type="submit" value="Wyślij"/></span></span>
					<p id="pool-span"></p>
				<?php else: ?>
					<p>Zaloguj się, aby głosować</p>
				<?php endif; ?>
			</form>
			
			<script type="text/javascript">
				<?php echo '
					/*<![CDATA[*/
						
						var submitPollAnswer = GEventHandler(function(eEvent) {
							var jSelected = $(this).find(\'input[type=radio]:checked\');
							if (!jSelected.length) {
								alert(\'Najpierw wybierz odpowiedź!\');
								eEvent.stopImmediatePropagation();
								return false;
							}
							var aMatches = jSelected.attr(\'id\').match(/poll\\[([^\\]]+)\\]/);
							xajax_setAnswersChecked(aMatches[1], '; ?>
'<?php echo $this->_tpl_vars['poll']['idpoll']; ?>
'<?php echo ');
							$(this).find(\'[type=submit], ul\').fadeOut(150);
							$(this).find(\'.answers\').fadeIn(150);
							eEvent.stopImmediatePropagation();
							return false;
						});
						
						GCore.OnLoad(function() {
							$(\'#poll-'; ?>
<?php echo $this->_tpl_vars['poll']['idpoll']; ?>
<?php echo '\').unbind(\'submit\', submitPollAnswer).bind(\'submit\', submitPollAnswer);
						});
						
					/*]]>*/
				'; ?>

			</script>
		<?php endif; ?>
	<?php else: ?>
		<dl>
			<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['answers']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>    
				<dt><?php echo $this->_tpl_vars['answers'][$this->_sections['i']['index']]['name']; ?>
</dt><dd><span class="votes"><?php echo $this->_tpl_vars['answers'][$this->_sections['i']['index']]['qty']['qty']; ?>
</span><span class="indicator" style="width: <?php echo $this->_tpl_vars['answers'][$this->_sections['i']['index']]['qty']['percentage']; ?>
%"></span></dd>
			<?php endfor; endif; ?>
		</dl>
	<?php endif; ?>
<?php else: ?>
	<p>Brak ankiet</p>
<?php endif; ?>	