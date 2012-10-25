<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/desktop.png" alt=""/>Statystyki produktów</h2>

					<!-- begin: Remote ad -->
						<div id="remote-ad" class="ad-panel"></div>
					<!-- end: Remote ad -->
<div id="debug"></div>
					<div class="block" id="desktop">

						<!-- begin: Simple stats -->
							<div class="simple-stats layout-two-columns">

								<div class="column narrow">

									<dl class="stats-summary">
										<dt>Produktów</dt><dd>{$summaryStats.summary.products}</dd>
										<!--<dt>Kategorii</dt><dd>{$summaryStats.summary.categories}</dd>-->
									</dl>

								</div>

								<div class="column wide">

									<div class="box">

										<h3 class="aural">Wykresy</h3>

										<div class="tabs">
											<ul>
												<li><a href="#desktop-simple-stats-bestsellers">Bestsellery</a></li>
												<!--<li><a href="#desktop-simple-stats-viewed">Oglądane</a></li>
												<li><a href="#desktop-simple-stats-tagged">Tagowane</a></li>-->
												
											</ul>
										</div>

										<div id="desktop-simple-stats-bestsellers">

											
											<div class="field-select">
												<label for="desktop-simple-stats-bestsellers-range">Produktów:</label>
												<span class="field">
													<select id="desktop-simple-stats-bestsellers-range" name="limit">
														 {section name=i loop=$limits}
 															 <option value="{$limits[i]}">{$limits[i]}</option>
  														 {/section}
													</select>
												</span>
											</div>

											
											<div class="chart" style="height: 500px;" id="desktop-simple-stats-bestsellers-chart"></div>
											
											<script type="text/javascript">
											{literal}
												GCore.OnLoad(function() {
													
													$('#desktop-simple-stats-bestsellers-chart').GChart({
														fSource: xajax_bestsellerschart,
														oParams: {
															type: 'bestsellers'
														},
														asParamFields: [
															'desktop-simple-stats-bestsellers-range'
														],
													});
												});
											{/literal}
											</script>
											

										</div>

										<!--<div id="desktop-simple-stats-viewed">

											<div class="field-select">
												<label for="desktop-simple-stats-viewed-range">Produktów:</label>
												<span class="field">
													<select id="desktop-simple-stats-viewed-range" name="limit">
														{section name=i loop=$limits}
 															 <option value="{$limits[i]}">{$limits[i]}</option>
  														 {/section}
													</select>
												</span>
											</div>

											<div class="chart" style="height: 500px;" id="desktop-simple-stats-viewed-chart"></div>

											<script type="text/javascript">
											{literal}												
												GCore.OnLoad(function() {
													$('#desktop-simple-stats-viewed-chart').GChart({
														fSource: xajax_viewedchart,
														oParams: {
															type: 'viewed'
														},
														asParamFields: [
															'desktop-simple-stats-viewed-range'
														],
														sOFCPath: '{$DESIGNPATH}/_data_panel/open-flash-chart.swf',
														//sEmptyDataFile: '{/literal}{$URL}empty/index{literal}'
													});
												});
											{/literal}
											</script>
										</div>

										<div id="desktop-simple-stats-tagged">

											<div class="field-select">
												<label for="desktop-simple-stats-tagged-range">Produktów:</label>
												<span class="field">
													<select id="desktop-simple-stats-tagged-range" name="limit">
														{section name=i loop=$limits}
 															 <option value="{$limits[i]}">{$limits[i]}</option>
  														 {/section}
													</select>
												</span>
											</div>

											<div class="chart" style="height: 500px;" id="desktop-simple-stats-tagged-chart"></div>

											<script type="text/javascript">
											{literal}												
												GCore.OnLoad(function() {
													$('#desktop-simple-stats-tagged-chart').GChart({
														fSource: xajax_taggedchart,
														oParams: {
															type: 'tagged'
														},
														asParamFields: [
															'desktop-simple-stats-tagged-range'
														],
														sOFCPath: '{$DESIGNPATH}/_data_panel/open-flash-chart.swf',
														//sEmptyDataFile: '{/literal}{$URL}empty/index{literal}'
													});
												});
											{/literal}
											</script>
										</div>-->

									</div>

								</div>

							</div>
						<!-- end: Simple stats -->

					</div>

					<!-- begin: Remote news -->
						<div id="remote-news"></div>
					<!-- end: Remote news -->
				

