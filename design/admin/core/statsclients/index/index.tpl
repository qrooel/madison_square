<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/desktop.png" alt=""/>Statystyki klientów</h2>


					<div class="block" id="desktop">

						<!-- begin: Simple stats -->
							<div class="simple-stats layout-two-columns">

								<div class="column narrow">

									<dl class="stats-summary">
										
									    <dt>Nowych klientów dzisiaj</dt><dd>{$summary.day.dayclients}</dd>
									    <dt>Nowych klientów w tym miesiącu</dt><dd>{$summary.month.monthclients}</dd>
									    <dt>Nowych klientów w tym roku</dt><dd>{$summary.year.yearclients}</dd>
									    <dt>Klientów łącznie</dt><dd>{$summary.total.totalclients}</dd>
									    
									</dl>

								</div>

								<div class="column wide">

									<div class="box">

										<h3 class="aural">Wykresy</h3>

										<div class="tabs">
											<ul>
												<li><a href="#desktop-simple-stats-customersgroups">Grupy klientów</a></li>
												
											</ul>
										</div>
										
										<div id="desktop-simple-stats-customersgroups">
											
											<div class="chart" id="desktop-simple-stats-customersgroups-chart"></div>

											<script type="text/javascript">
											{literal}
												GCore.OnLoad(function() {
													$('#desktop-simple-stats-customersgroups-chart').GChart({
														fSource: xajax_clientsgroupschart,
														oParams: {
															type: 'customersgroups'
														},
														asParamFields: [
															
														]
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