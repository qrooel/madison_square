<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/category-edit.png" alt=""/>{trans}TXT_DUPLICATE_CATEGORY{/trans}: {$categoryName}</h2>
<ul class="possibilities">
	<li><a href="{$URL}category/index" class="button return"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-gray.png" title="{trans}TXT_CATEGORY_LIST{/trans}" alt="{trans}TXT_CATEGORY_LIST{/trans}"/></span></a></li>
	<li><a href="#duplicate_category" rel="reset" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/clean.png" alt=""/>{trans}TXT_START_AGAIN{/trans}</span></a></li>
	<li><a href="#duplicate_category" rel="submit" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/check.png" alt=""/>{trans}TXT_SAVE{/trans}</span></a></li>
</ul>

<script type="text/javascript">
	{literal}
		/*<![CDATA[*/
			function openCategoryEditor(sId) {
				if (sId == undefined) {
					window.location = '{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/';
				}
				else {
					window.location = '{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/edit/' + sId;
				}
			};
			
			function openCategoryEditorDuplicate(sId) {
				if (sId == undefined) {
					window.location = '{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/';
				}
				else {
					window.location = '{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/duplicate/' + sId;
				}
			};
			
			function disableCeneo(sId)
			{
				alert(1);
			}
			
			function liveUrlTitle(title)
       {
           var defaultTitle = '';
           var NewText = title;
                      if (defaultTitle != '')
           {
               if (NewText.substr(0, defaultTitle.length) == defaultTitle)
               {
                   NewText = NewText.substr(defaultTitle.length)
               }               }
                      NewText = NewText.toLowerCase();
           var separator = "-";
                      if (separator != "_")
           {
               NewText = NewText.replace(/\_/g, separator);
           }
           else
           {
               NewText = NewText.replace(/\-/g, separator);
           }
              // Foreign Character Attempt
                      var NewTextTemp = '';
           for(var pos=0; pos<NewText.length; pos++)
           {
               var c = NewText.charCodeAt(pos);
                              if (c >= 32 && c < 128)
               {
                   NewTextTemp += NewText.charAt(pos);
               }
               else
               {
               if (c == '223') {NewTextTemp += 'ss'; continue;}
               if (c == '224') {NewTextTemp += 'a'; continue;}
               if (c == '225') {NewTextTemp += 'a'; continue;}
               if (c == '226') {NewTextTemp += 'a'; continue;}
               if (c == '229') {NewTextTemp += 'a'; continue;}
               if (c == '227') {NewTextTemp += 'ae'; continue;}
               if (c == '230') {NewTextTemp += 'ae'; continue;}
               if (c == '228') {NewTextTemp += 'ae'; continue;}
               if (c == '231') {NewTextTemp += 'c'; continue;}
               if (c == '232') {NewTextTemp += 'e'; continue;}
               if (c == '233') {NewTextTemp += 'e'; continue;}
               if (c == '234') {NewTextTemp += 'e'; continue;}
               if (c == '235') {NewTextTemp += 'e'; continue;}
               if (c == '236') {NewTextTemp += 'i'; continue;}
               if (c == '237') {NewTextTemp += 'i'; continue;}
               if (c == '238') {NewTextTemp += 'i'; continue;}
               if (c == '239') {NewTextTemp += 'i'; continue;}
               if (c == '241') {NewTextTemp += 'n'; continue;}
               if (c == '242') {NewTextTemp += 'o'; continue;}
               if (c == '243') {NewTextTemp += 'o'; continue;}
               if (c == '244') {NewTextTemp += 'o'; continue;}
               if (c == '245') {NewTextTemp += 'o'; continue;}
               if (c == '246') {NewTextTemp += 'oe'; continue;}
               if (c == '249') {NewTextTemp += 'u'; continue;}
               if (c == '250') {NewTextTemp += 'u'; continue;}
               if (c == '251') {NewTextTemp += 'u'; continue;}
               if (c == '252') {NewTextTemp += 'ue'; continue;}
               if (c == '255') {NewTextTemp += 'y'; continue;}
               if (c == '257') {NewTextTemp += 'aa'; continue;}
               if (c == '269') {NewTextTemp += 'ch'; continue;}
               if (c == '275') {NewTextTemp += 'ee'; continue;}
               if (c == '291') {NewTextTemp += 'gj'; continue;}
               if (c == '299') {NewTextTemp += 'ii'; continue;}
               if (c == '311') {NewTextTemp += 'kj'; continue;}
               if (c == '316') {NewTextTemp += 'lj'; continue;}
               if (c == '326') {NewTextTemp += 'nj'; continue;}
               if (c == '353') {NewTextTemp += 'sh'; continue;}
               if (c == '363') {NewTextTemp += 'uu'; continue;}
               if (c == '382') {NewTextTemp += 'zh'; continue;}
               if (c == '256') {NewTextTemp += 'aa'; continue;}
               if (c == '268') {NewTextTemp += 'ch'; continue;}
               if (c == '274') {NewTextTemp += 'ee'; continue;}
               if (c == '290') {NewTextTemp += 'gj'; continue;}
               if (c == '298') {NewTextTemp += 'ii'; continue;}
               if (c == '310') {NewTextTemp += 'kj'; continue;}
               if (c == '315') {NewTextTemp += 'lj'; continue;}
               if (c == '325') {NewTextTemp += 'nj'; continue;}
               if (c == '352') {NewTextTemp += 'sh'; continue;}
               if (c == '362') {NewTextTemp += 'uu'; continue;}
               if (c == '381') {NewTextTemp += 'zh'; continue;}
               
                if (c == '281') {NewTextTemp += 'e'; continue;}
			   if (c == '261') {NewTextTemp += 'a'; continue;}
			   if (c == '263') {NewTextTemp += 'c'; continue;}
			   if (c == '322') {NewTextTemp += 'l'; continue;}
			   if (c == '324') {NewTextTemp += 'n'; continue;}
			   if (c == '347') {NewTextTemp += 's'; continue;}
			   if (c == '347') {NewTextTemp += 's'; continue;}
			   if (c == '378') {NewTextTemp += 'z'; continue;}
			   if (c == '380') {NewTextTemp += 'z'; continue;}
				}
           }
              NewText = NewTextTemp;
                      NewText = NewText.replace('/<(.*?)>/g', '');
           NewText = NewText.replace('/\&#\d+\;/g', '');
           NewText = NewText.replace('/\&\#\d+?\;/g', '');
           NewText = NewText.replace('/\&\S+?\;/g','');
           NewText = NewText.replace(/['\"\?\.\!*$\#@%;:,=\(\)\[\]]/g,'');
           NewText = NewText.replace(/\s+/g, separator);
           NewText = NewText.replace(/\//g, separator);
           NewText = NewText.replace(/[^a-z0-9-_]/g,'');
           NewText = NewText.replace(/\+/g, separator);
           NewText = NewText.replace(/[-_]+/g, separator);
           NewText = NewText.replace(/\&/g,'');
           NewText = NewText.replace(/-$/g,'');
           NewText = NewText.replace(/_$/g,'');
           NewText = NewText.replace(/^_/g,'');
           NewText = NewText.replace(/^-/g,'');
                      return NewText;           }

          $(document).ready(function() {
          $.each(GCore.aoLanguages,function(l,language){
              var name = "#required_data__language_data__"+language.id+"__name";
              var seo = "#required_data__language_data__"+language.id+"__seo";
              var keywordtitle = "#required_data__language_data__"+language.id+"__keywordtitle";
              $(name).bind('change keyup',function(){
                  $(seo).val(liveUrlTitle($(this).val()));
              });
              $(name).bind('change keyup',function(){
                  $(keywordtitle).val($(this).val());
              });
              if($(seo).val() == ''){
               $(seo).val(liveUrlTitle($(name).val()));
              }
       		if($(keywordtitle).val() == ''){
               $(keywordtitle).val($(name).val());
              }
            });});
			
		/*]]>*/
	{/literal}
</script>

<div class="layout-two-columns">

	<div class="column narrow-collapsed">
		<div class="block">
			{fe_form form=$tree render_mode="JS"}
		</div>
	</div>

	<div class="column wide-collapsed">
		{fe_form form=$form render_mode="JS"}
	</div>
	
</div>
  