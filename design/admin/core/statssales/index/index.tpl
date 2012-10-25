<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/desktop.png" alt=""/>Statystyki sprzedaży</h2>

					<!-- begin: Remote ad -->
						<div id="remote-ad" class="ad-panel"></div>
					<!-- end: Remote ad -->
<div id="debug"></div>
					<div class="block" id="desktop">

						<!-- begin: Simple stats -->
							<div class="simple-stats layout-two-columns">

								<div class="column narrow">

									<dl class="stats-summary">
										<dt>Obrót (dzisiaj / bieżący miesiąc)</dt><dd>{$summaryStats.todaysales.total} {trans}TXT_CURRENCY{/trans} / {$summaryStats.summarysales.total} {trans}TXT_CURRENCY{/trans}</dd>
										<dt>Zamówień (dzisiaj / bieżący miesiąc)</dt><dd>{$summaryStats.todaysales.orders} / {$summaryStats.summarysales.orders}</dd>
									    <dt>Klientów (dzisiaj / bieżący miesiąc)</dt><dd>{$summaryStats.todayclients.totalclients} / {$summaryStats.summaryclients.totalclients} </dd>
									    
									</dl>

								</div>

								<div class="column wide">

									<div class="box">

										<h3 class="aural">Wykresy</h3>

										<div class="tabs">
											<ul>
												<li><a href="#desktop-simple-stats-sales">Sprzedaż</a></li>
												<li><a href="#desktop-simple-stats-orders">Zamówienia</a></li>
												
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
												        data_file: "{/literal}{$URL}{literal}mainside/view/sales," + period,
													};
													
												    var ordersVars = {
												        path: "{/literal}{$DESIGNPATH}{literal}_js_libs/amcharts/flash/",
												        settings_file: "{/literal}{$DESIGNPATH}{literal}_data_panel/orderschart.xml",
												        data_file: "{/literal}{$URL}{literal}mainside/view/orders," + period,
													};
													
												    swfobject.embedSWF("{/literal}{$DESIGNPATH}{literal}_js_libs/amcharts/flash/amline.swf", "desktop-simple-stats-sales-chart", "850", "400", "8.0.0", "{/literal}{$DESIGNPATH}{literal}_js_libs/amcharts/flash/expressInstall.swf", salesVars, params);
												    swfobject.embedSWF("{/literal}{$DESIGNPATH}{literal}_js_libs/amcharts/flash/amline.swf", "desktop-simple-stats-orders-chart", "850", "400", "8.0.0", "{/literal}{$DESIGNPATH}{literal}_js_libs/amcharts/flash/expressInstall.swf", ordersVars, params);
												    
											    	$('#period').daterangepicker({
											    		dateFormat : 'yy/mm/dd',
											    		posY : '258px',
														onChange: function(){ 
															var period = Base64.encode($('#period').val());
															salesVars.data_file = "{/literal}{$URL}{literal}mainside/view/sales," + period;
															ordersVars.data_file = "{/literal}{$URL}{literal}mainside/view/orders," + period;
															swfobject.embedSWF("{/literal}{$DESIGNPATH}{literal}_js_libs/amcharts/flash/amline.swf", "desktop-simple-stats-sales-chart", "850", "400", "8.0.0", "{/literal}{$DESIGNPATH}{literal}_js_libs/amcharts/flash/expressInstall.swf", salesVars, params);
														    swfobject.embedSWF("{/literal}{$DESIGNPATH}{literal}_js_libs/amcharts/flash/amline.swf", "desktop-simple-stats-orders-chart", "850", "400", "8.0.0", "{/literal}{$DESIGNPATH}{literal}_js_libs/amcharts/flash/expressInstall.swf", ordersVars, params);
														}, 
													});							            
											    	
												});
											{/literal}
											</script>

									</div>

										

									</div>

								</div>

							</div>
						<!-- end: Simple stats -->

					</div>

					<!-- begin: Remote news -->
						<div id="remote-news"></div>
					<!-- end: Remote news -->
				

