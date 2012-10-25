<?php /* Smarty version 2.6.19, created on 2012-10-08 09:35:13
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/mainside/index/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/mainside/index/index.tpl', 131, false),array('modifier', 'truncate', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/mainside/index/index.tpl', 132, false),)), $this); ?>
					<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/desktop.png" alt=""/>Pulpit</h2>

					<!-- begin: Remote ad -->
						<div id="remote-ad" class="ad-panel"></div>
					<!-- end: Remote ad -->
					<div id="debug"></div>
					<div class="block" id="desktop">

						<!-- begin: Simple stats -->
							<div class="simple-stats layout-two-columns">

								<div class="column narrow">

									<dl class="stats-summary">
										<dt>Sprzedaż (Dzisiaj / Bieżący miesiąc)</dt><dd><?php echo $this->_tpl_vars['summaryStats']['todaysales']['total']; ?>
 zł / <?php echo $this->_tpl_vars['summaryStats']['summarysales']['total']; ?>
 zł</dd>
										<dt>Zamówienia (Dzisiaj / Bieżący miesiąc)</dt><dd><?php echo $this->_tpl_vars['summaryStats']['todaysales']['orders']; ?>
 / <?php echo $this->_tpl_vars['summaryStats']['summarysales']['orders']; ?>
</dd>
									    <dt>Klienci (Dzisiaj / Bieżący miesiąc)</dt><dd><?php echo $this->_tpl_vars['summaryStats']['todayclients']['totalclients']; ?>
 / <?php echo $this->_tpl_vars['summaryStats']['summaryclients']['totalclients']; ?>
 </dd>
									</dl>

								</div>

								<div class="column wide">

									<div class="box">

										<h3 class="aural">Wykres</h3>

										<div class="tabs">
											<ul>
												<li><a href="#desktop-simple-stats-sales">Sprzedaż</a></li>
												<li><a href="#desktop-simple-stats-orders">Zamówienia</a></li>
												<li><a href="#desktop-simple-stats-customers">Klienci</a></li>
											</ul>
										</div>

										<div class="field-text" >
											<label for="desktop-simple-stats-orders-range" style="float: left;margin-top: 3px;margin-right: 5px;">Zakres czasu:</label>
											<span class="field" style="width: 150px;">
												<input type="text" id="period" class="period" style="width:142px" value="<?php echo $this->_tpl_vars['from']; ?>
 - <?php echo $this->_tpl_vars['to']; ?>
" />
											</span>
										</div>
										
										<div id="desktop-simple-stats-sales">
											<div class="chart" id="desktop-simple-stats-sales-chart"></div>
										</div>
										
										<div id="desktop-simple-stats-orders">
											<div class="chart" id="desktop-simple-stats-orders-chart"></div>
										</div>
										
										<div id="desktop-simple-stats-customers">
											<div class="chart" id="desktop-simple-stats-customers-chart"></div>
										</div>

											<script type="text/javascript">
											<?php echo '
												GCore.OnLoad(function() {

													var params = {
											        	bgcolor:"#FFFFFF",
											        	wmode: \'opaque\'
											        };

													var period = Base64.encode($(\'#period\').val());
													
												    var salesVars = {
												        path: "'; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '_js_libs/amcharts/flash/",
												        settings_file: "'; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '_data_panel/saleschart.xml",
												        data_file: "'; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
<?php echo '/view/sales," + period,
													};
													
												    var ordersVars = {
												        path: "'; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '_js_libs/amcharts/flash/",
												        settings_file: "'; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '_data_panel/orderschart.xml",
												        data_file: "'; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
<?php echo '/view/orders," + period,
													};
													
												    var clientsVars = {
												        path: "'; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '_js_libs/amcharts/flash/",
												        settings_file: "'; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '_data_panel/clientschart.xml",
												        data_file: "'; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
<?php echo '/view/clients," + period,
													};

												    swfobject.embedSWF("'; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '_js_libs/amcharts/flash/amline.swf", "desktop-simple-stats-sales-chart", "850", "400", "8.0.0", "'; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '_js_libs/amcharts/flash/expressInstall.swf", salesVars, params);
												    swfobject.embedSWF("'; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '_js_libs/amcharts/flash/amline.swf", "desktop-simple-stats-orders-chart", "850", "400", "8.0.0", "'; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '_js_libs/amcharts/flash/expressInstall.swf", ordersVars, params);
												    swfobject.embedSWF("'; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '_js_libs/amcharts/flash/amline.swf", "desktop-simple-stats-customers-chart", "850", "400", "8.0.0", "'; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '_js_libs/amcharts/flash/expressInstall.swf", clientsVars, params);
												    
											    	$(\'#period\').daterangepicker({
											    		dateFormat : \'yy/mm/dd\',
											    		posY : \'258px\',
														onChange: function(){ 
															var period = Base64.encode($(\'#period\').val());
															salesVars.data_file = "'; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
<?php echo '/view/sales," + period;
															ordersVars.data_file = "'; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
<?php echo '/view/orders," + period;
															clientsVars.data_file = "'; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
<?php echo '/view/clients," + period;
															swfobject.embedSWF("'; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '_js_libs/amcharts/flash/amline.swf", "desktop-simple-stats-sales-chart", "850", "400", "8.0.0", "'; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '_js_libs/amcharts/flash/expressInstall.swf", salesVars, params);
														    swfobject.embedSWF("'; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '_js_libs/amcharts/flash/amline.swf", "desktop-simple-stats-orders-chart", "850", "400", "8.0.0", "'; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '_js_libs/amcharts/flash/expressInstall.swf", ordersVars, params);
														    swfobject.embedSWF("'; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '_js_libs/amcharts/flash/amline.swf", "desktop-simple-stats-customers-chart", "850", "400", "8.0.0", "'; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '_js_libs/amcharts/flash/expressInstall.swf", clientsVars, params);
														}, 
													});							            
											    	
												});
											'; ?>

											</script>

									</div>

								</div>

							</div>
						<!-- end: Simple stats -->

						<!-- begin: Four columns -->
							<div class="layout-four-columns">

								<div class="column">

									<!-- begin: Recent orders -->
										<div class="box">

											<h3><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/blocks/recent-orders.png" alt=""/>Ostatnie zamówienia</h3>
											<table cellspacing="0" class="full-size list">
												<thead>
													<tr>
														<th>Zamawiający</th>
														<th class="align-right">Suma</th>
													</tr>
												</thead>
												<tbody>
													<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=10) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
														<tr class="<?php echo smarty_function_cycle(array('values' => "o,e"), $this);?>
">
															<th scope="row"><a title="<?php echo $this->_tpl_vars['lastorder'][$this->_sections['i']['index']]['surname']; ?>
" href="<?php echo $this->_tpl_vars['URL']; ?>
order/edit/<?php echo $this->_tpl_vars['lastorder'][$this->_sections['i']['index']]['id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['lastorder'][$this->_sections['i']['index']]['surname'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 18) : smarty_modifier_truncate($_tmp, 18)); ?>
</a></th>
															<td class="align-right"><?php echo $this->_tpl_vars['lastorder'][$this->_sections['i']['index']]['price']; ?>
<?php if ($this->_tpl_vars['lastorder'][$this->_sections['i']['index']]): ?> zł<?php endif; ?></td>
														</tr>
													<?php endfor; endif; ?>
												</tbody>
											</table>
											<p class="more"><a href="<?php echo $this->_tpl_vars['URL']; ?>
order/index/">Zobacz raport</a></p>

										</div>
									<!-- end: Recent orders -->

								</div>

								<div class="column">

									<!-- begin: New customers -->
										<div class="box">

											<h3><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/blocks/new-customers.png" alt=""/>Nowi klienci</h3>
											<table cellspacing="0" class="full-size list">
												<thead>
													<tr>
														<th>Imię</th>
														<th abbr="Sztuk" class="align-center">Nazwisko</th>
													</tr>
												</thead>
												<tbody>
													<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=10) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
														<tr class="<?php echo smarty_function_cycle(array('values' => "o,e"), $this);?>
">
															<th scope="row"><a title="<?php echo $this->_tpl_vars['newclient'][$this->_sections['i']['index']]['firstname']; ?>
" href="<?php echo $this->_tpl_vars['URL']; ?>
client/edit/<?php echo $this->_tpl_vars['newclient'][$this->_sections['i']['index']]['id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['newclient'][$this->_sections['i']['index']]['firstname'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 23) : smarty_modifier_truncate($_tmp, 23)); ?>
</a></th>
															<td class="align-center"><?php echo $this->_tpl_vars['newclient'][$this->_sections['i']['index']]['surname']; ?>
</td>
														</tr>
													<?php endfor; endif; ?>
												</tbody>
											</table>
											<p class="more"><a href="<?php echo $this->_tpl_vars['URL']; ?>
client/index/">Zobacz raport</a></p>

										</div>
									<!-- end: New customers -->

								</div>

								<div class="column">

									<!-- begin: Bestsellers -->
										<div class="box">

											<h3><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/blocks/bestsellers.png" alt=""/>Bestsellery</h3>
											<table cellspacing="0" class="full-size list">
												<thead>
													<tr>
														<th>Produkt</th>
														<th abbr="Sztuk" class="align-center">szt</th>
														<th class="align-right">Suma</th>
													</tr>
												</thead>
												<tbody>
													<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=10) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
														<tr class="<?php echo smarty_function_cycle(array('values' => "o,e"), $this);?>
">
															<th scope="row"><?php if ($this->_tpl_vars['topten'][$this->_sections['i']['index']]['productid'] > 0): ?><a href="<?php echo $this->_tpl_vars['URL']; ?>
product/edit/<?php echo $this->_tpl_vars['topten'][$this->_sections['i']['index']]['productid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['topten'][$this->_sections['i']['index']]['label'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 18) : smarty_modifier_truncate($_tmp, 18)); ?>
</a><?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['topten'][$this->_sections['i']['index']]['label'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 18) : smarty_modifier_truncate($_tmp, 18)); ?>
<?php endif; ?></th>
															<td class="align-center"><?php echo $this->_tpl_vars['topten'][$this->_sections['i']['index']]['value']; ?>
<?php if ($this->_tpl_vars['topten'][$this->_sections['i']['index']]): ?><?php endif; ?></td>
															<td class="align-right"><?php echo $this->_tpl_vars['topten'][$this->_sections['i']['index']]['productprice']; ?>
<?php if ($this->_tpl_vars['topten'][$this->_sections['i']['index']]): ?> zł<?php endif; ?></td>
														</tr>
													<?php endfor; endif; ?>
												</tbody>
											</table>
											<p class="more"><a href="<?php echo $this->_tpl_vars['URL']; ?>
product/index/">Zobacz raport</a></p>

										</div>
									<!-- end: Bestsellers -->

								</div>

								<div class="column">

									<!-- begin: Most popular -->
										<div class="box">

											<h3><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/blocks/most-popular.png" alt=""/>Szukane frazy</h3>
											<table cellspacing="0" class="full-size list">
												<thead>
													<tr>
														<th>Produkt</th>
														<th class="align-right">szt</th>
													</tr>
												</thead>
												<tbody>
													<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=10) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
														<tr class="<?php echo smarty_function_cycle(array('values' => "o,e"), $this);?>
">
															<th scope="row" title="<?php echo $this->_tpl_vars['mostsearch'][$this->_sections['i']['index']]['productname']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['mostsearch'][$this->_sections['i']['index']]['productname'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 23) : smarty_modifier_truncate($_tmp, 23)); ?>
</th>
															<td class="align-right"><?php echo $this->_tpl_vars['mostsearch'][$this->_sections['i']['index']]['qty']; ?>
</td>
														</tr>
													<?php endfor; endif; ?>
												</tbody>
											</table>
											<p class="more"><a href="<?php echo $this->_tpl_vars['URL']; ?>
mostsearch/index/">Zobacz raport</a></p>

										</div>
									<!-- end: Most popular -->

								</div>
								
								
								<div class="column">

									<!-- begin: Most popular -->
										<div class="box">

											<h3><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/blocks/users-online.png" alt=""/>Klienci on-line</h3>
											<table cellspacing="0" class="full-size list">
												<thead>
													<tr>
														<th>Imię</th>
														<th class="align-right">Nazwisko</th>
													</tr>
												</thead>
												<tbody>
													<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=10) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
														<tr class="<?php echo smarty_function_cycle(array('values' => "o,e"), $this);?>
">
															<th scope="row" title="<?php echo $this->_tpl_vars['clientOnline'][$this->_sections['i']['index']]['firstname']; ?>
"><?php echo $this->_tpl_vars['clientOnline'][$this->_sections['i']['index']]['firstname']; ?>
</th>
															<td class="align-right"><?php echo $this->_tpl_vars['clientOnline'][$this->_sections['i']['index']]['surname']; ?>
</td>
														</tr>
													<?php endfor; endif; ?>
												</tbody>
											</table>
											<p class="more"><a href="<?php echo $this->_tpl_vars['URL']; ?>
spy/index/">Zobacz raport</a></p>

										</div>
									<!-- end: Most popular -->

								</div>

							</div>
						<!-- end: Four columns -->

					</div>

					<!-- begin: Remote news -->
						<div id="remote-news"></div>
					<!-- end: Remote news -->
					