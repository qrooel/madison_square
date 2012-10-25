					<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/desktop.png" alt=""/>{trans}TXT_DESKTOP{/trans}</h2>

					<!-- begin: Remote ad -->
						<div id="remote-ad" class="ad-panel"></div>
					<!-- end: Remote ad -->
					<div id="debug"></div>
					<div class="block" id="desktop">

						<!-- begin: Simple stats -->
							<div class="simple-stats layout-two-columns">

								<div class="column narrow">

									<dl class="stats-summary">
										<dt>{trans}TXT_SALES{/trans} ({trans}TXT_TODAY{/trans} / {trans}TXT_CURRENT_MONTH{/trans})</dt><dd>{$summaryStats.todaysales.total} {trans}TXT_CURRENCY{/trans} / {$summaryStats.summarysales.total} {trans}TXT_CURRENCY{/trans}</dd>
										<dt>{trans}TXT_ORDERS{/trans} ({trans}TXT_TODAY{/trans} / {trans}TXT_CURRENT_MONTH{/trans})</dt><dd>{$summaryStats.todaysales.orders} / {$summaryStats.summarysales.orders}</dd>
									    <dt>{trans}TXT_CLIENTS{/trans} ({trans}TXT_TODAY{/trans} / {trans}TXT_CURRENT_MONTH{/trans})</dt><dd>{$summaryStats.todayclients.totalclients} / {$summaryStats.summaryclients.totalclients} </dd>
									</dl>

								</div>

								<div class="column wide">

									<div class="box">

										<h3 class="aural">{trans}TXT_GRAPH{/trans}</h3>

										<div class="tabs">
											<ul>
												<li><a href="#desktop-simple-stats-sales">{trans}TXT_SALES{/trans}</a></li>
												<li><a href="#desktop-simple-stats-orders">{trans}TXT_ORDERS{/trans}</a></li>
												<li><a href="#desktop-simple-stats-customers">{trans}TXT_CLIENTS{/trans}</a></li>
											</ul>
										</div>

										<div class="field-text" >
											<label for="desktop-simple-stats-orders-range" style="float: left;margin-top: 3px;margin-right: 5px;">{trans}TXT_PERIOD_LIST{/trans}:</label>
											<span class="field" style="width: 150px;">
												<input type="text" id="period" class="period" style="width:142px" value="{$from} - {$to}" />
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
											{literal}
												GCore.OnLoad(function() {

													var params = {
											        	bgcolor:"#FFFFFF",
											        	wmode: 'opaque'
											        };

													var period = Base64.encode($('#period').val());
													
												    var salesVars = {
												        path: "{/literal}{$DESIGNPATH}{literal}_js_libs/amcharts/flash/",
												        settings_file: "{/literal}{$DESIGNPATH}{literal}_data_panel/saleschart.xml",
												        data_file: "{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/view/sales," + period,
													};
													
												    var ordersVars = {
												        path: "{/literal}{$DESIGNPATH}{literal}_js_libs/amcharts/flash/",
												        settings_file: "{/literal}{$DESIGNPATH}{literal}_data_panel/orderschart.xml",
												        data_file: "{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/view/orders," + period,
													};
													
												    var clientsVars = {
												        path: "{/literal}{$DESIGNPATH}{literal}_js_libs/amcharts/flash/",
												        settings_file: "{/literal}{$DESIGNPATH}{literal}_data_panel/clientschart.xml",
												        data_file: "{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/view/clients," + period,
													};

												    swfobject.embedSWF("{/literal}{$DESIGNPATH}{literal}_js_libs/amcharts/flash/amline.swf", "desktop-simple-stats-sales-chart", "850", "400", "8.0.0", "{/literal}{$DESIGNPATH}{literal}_js_libs/amcharts/flash/expressInstall.swf", salesVars, params);
												    swfobject.embedSWF("{/literal}{$DESIGNPATH}{literal}_js_libs/amcharts/flash/amline.swf", "desktop-simple-stats-orders-chart", "850", "400", "8.0.0", "{/literal}{$DESIGNPATH}{literal}_js_libs/amcharts/flash/expressInstall.swf", ordersVars, params);
												    swfobject.embedSWF("{/literal}{$DESIGNPATH}{literal}_js_libs/amcharts/flash/amline.swf", "desktop-simple-stats-customers-chart", "850", "400", "8.0.0", "{/literal}{$DESIGNPATH}{literal}_js_libs/amcharts/flash/expressInstall.swf", clientsVars, params);
												    
											    	$('#period').daterangepicker({
											    		dateFormat : 'yy/mm/dd',
											    		posY : '258px',
														onChange: function(){ 
															var period = Base64.encode($('#period').val());
															salesVars.data_file = "{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/view/sales," + period;
															ordersVars.data_file = "{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/view/orders," + period;
															clientsVars.data_file = "{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/view/clients," + period;
															swfobject.embedSWF("{/literal}{$DESIGNPATH}{literal}_js_libs/amcharts/flash/amline.swf", "desktop-simple-stats-sales-chart", "850", "400", "8.0.0", "{/literal}{$DESIGNPATH}{literal}_js_libs/amcharts/flash/expressInstall.swf", salesVars, params);
														    swfobject.embedSWF("{/literal}{$DESIGNPATH}{literal}_js_libs/amcharts/flash/amline.swf", "desktop-simple-stats-orders-chart", "850", "400", "8.0.0", "{/literal}{$DESIGNPATH}{literal}_js_libs/amcharts/flash/expressInstall.swf", ordersVars, params);
														    swfobject.embedSWF("{/literal}{$DESIGNPATH}{literal}_js_libs/amcharts/flash/amline.swf", "desktop-simple-stats-customers-chart", "850", "400", "8.0.0", "{/literal}{$DESIGNPATH}{literal}_js_libs/amcharts/flash/expressInstall.swf", clientsVars, params);
														}, 
													});							            
											    	
												});
											{/literal}
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

											<h3><img src="{$DESIGNPATH}_images_panel/icons/blocks/recent-orders.png" alt=""/>{trans}TXT_LAST_ORDERS{/trans}</h3>
											<table cellspacing="0" class="full-size list">
												<thead>
													<tr>
														<th>Zamawiający</th>
														<th class="align-right">{trans}TXT_SUM{/trans}</th>
													</tr>
												</thead>
												<tbody>
													{section name=i loop=10}
														<tr class="{cycle values="o,e"}">
															<th scope="row"><a title="{$lastorder[i].surname}" href="{$URL}order/edit/{$lastorder[i].id}">{$lastorder[i].surname|truncate:18}</a></th>
															<td class="align-right">{$lastorder[i].price}{if $lastorder[i]} {trans}TXT_CURRENCY{/trans}{/if}</td>
														</tr>
													{/section}
												</tbody>
											</table>
											<p class="more"><a href="{$URL}order/index/">{trans}TXT_SHOW_RAPORTS{/trans}</a></p>

										</div>
									<!-- end: Recent orders -->

								</div>

								<div class="column">

									<!-- begin: New customers -->
										<div class="box">

											<h3><img src="{$DESIGNPATH}_images_panel/icons/blocks/new-customers.png" alt=""/>{trans}TXT_NEW_CUSTOMERS{/trans}</h3>
											<table cellspacing="0" class="full-size list">
												<thead>
													<tr>
														<th>{trans}TXT_FIRSTNAME{/trans}</th>
														<th abbr="Sztuk" class="align-center">{trans}TXT_SURNAME{/trans}</th>
													</tr>
												</thead>
												<tbody>
													{section name=i loop=10}
														<tr class="{cycle values="o,e"}">
															<th scope="row"><a title="{$newclient[i].firstname}" href="{$URL}client/edit/{$newclient[i].id}">{$newclient[i].firstname|truncate:23}</a></th>
															<td class="align-center">{$newclient[i].surname}</td>
														</tr>
													{/section}
												</tbody>
											</table>
											<p class="more"><a href="{$URL}client/index/">{trans}TXT_SHOW_RAPORTS{/trans}</a></p>

										</div>
									<!-- end: New customers -->

								</div>

								<div class="column">

									<!-- begin: Bestsellers -->
										<div class="box">

											<h3><img src="{$DESIGNPATH}_images_panel/icons/blocks/bestsellers.png" alt=""/>{trans}TXT_BESTSELLERS{/trans}</h3>
											<table cellspacing="0" class="full-size list">
												<thead>
													<tr>
														<th>{trans}TXT_PRODUCT{/trans}</th>
														<th abbr="Sztuk" class="align-center">{trans}TXT_QTY{/trans}</th>
														<th class="align-right">{trans}TXT_SUM{/trans}</th>
													</tr>
												</thead>
												<tbody>
													{section name=i loop=10}
														<tr class="{cycle values="o,e"}">
															<th scope="row">{if $topten[i].productid > 0}<a href="{$URL}product/edit/{$topten[i].productid}">{$topten[i].label|truncate:18}</a>{else}{$topten[i].label|truncate:18}{/if}</th>
															<td class="align-center">{$topten[i].value}{if $topten[i]}{/if}</td>
															<td class="align-right">{$topten[i].productprice}{if $topten[i]} {trans}TXT_CURRENCY{/trans}{/if}</td>
														</tr>
													{/section}
												</tbody>
											</table>
											<p class="more"><a href="{$URL}product/index/">{trans}TXT_SHOW_RAPORTS{/trans}</a></p>

										</div>
									<!-- end: Bestsellers -->

								</div>

								<div class="column">

									<!-- begin: Most popular -->
										<div class="box">

											<h3><img src="{$DESIGNPATH}_images_panel/icons/blocks/most-popular.png" alt=""/>{trans}TXT_MOST_SEARCH{/trans}</h3>
											<table cellspacing="0" class="full-size list">
												<thead>
													<tr>
														<th>{trans}TXT_PRODUCT{/trans}</th>
														<th class="align-right">{trans}TXT_QTY{/trans}</th>
													</tr>
												</thead>
												<tbody>
													{section name=i loop=10}
														<tr class="{cycle values="o,e"}">
															<th scope="row" title="{$mostsearch[i].productname}">{$mostsearch[i].productname|truncate:23}</th>
															<td class="align-right">{$mostsearch[i].qty}</td>
														</tr>
													{/section}
												</tbody>
											</table>
											<p class="more"><a href="{$URL}mostsearch/index/">{trans}TXT_SHOW_RAPORTS{/trans}</a></p>

										</div>
									<!-- end: Most popular -->

								</div>
								
								
								<div class="column">

									<!-- begin: Most popular -->
										<div class="box">

											<h3><img src="{$DESIGNPATH}_images_panel/icons/blocks/users-online.png" alt=""/>{trans}TXT_CLIENT_ONLINE{/trans}</h3>
											<table cellspacing="0" class="full-size list">
												<thead>
													<tr>
														<th>{trans}TXT_FIRSTNAME{/trans}</th>
														<th class="align-right">{trans}TXT_SURNAME{/trans}</th>
													</tr>
												</thead>
												<tbody>
													{section name=i loop=10}
														<tr class="{cycle values="o,e"}">
															<th scope="row" title="{$clientOnline[i].firstname}">{$clientOnline[i].firstname}</th>
															<td class="align-right">{$clientOnline[i].surname}</td>
														</tr>
													{/section}
												</tbody>
											</table>
											<p class="more"><a href="{$URL}spy/index/">{trans}TXT_SHOW_RAPORTS{/trans}</a></p>

										</div>
									<!-- end: Most popular -->

								</div>

							</div>
						<!-- end: Four columns -->

					</div>

					<!-- begin: Remote news -->
						<div id="remote-news"></div>
					<!-- end: Remote news -->
					