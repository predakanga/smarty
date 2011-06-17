# Documentation ToDo #

these changes must reflect on the documentation at some point…

## General ##


## Syntax ##

* {block ... hide} option to supress block if no child is defined
* {setfilter} tag 
* {break} {continue} are present but currently not documented


## Plugins ##

* update documentation to load nested plugins by $_smarty->loadPlugin()

## Functions ##

* - added attributes [month_names, all_id, year_id, month_id, day_id] to function.html_select_date
* - missing attribute all_empty of function.html_select_date
* - added attributes [field_separator, option_separator, all_id, hour_id, minute_id, second_id, meridian_id, all_empty, hour_empty, minute_empty, second_empty, meridian_empty, hour_format, hour_value_format, minute_format, minute_value_format, second_format, second_value_format] to function.html_select_time
* - made time-attribute of {html_select_date} and {html_select_time} accept arrays as defined by attributes prefix and field_array


## Modifiers ##

* - added argument $lc_rest to modifier.capitalize - if true, only first character of word is UC, rest is LC
* - modifiercompiler.count_sentences treats .?! as proper sentence ending (previously only . counted)
* - added argument $double_encode to modifier.escape - if false, &amp; will not be converted to &amp;amp;
* - added modifiercompiler.unescape - decode "entity", "htmlall", "html"
* - added modifiercompiler.from_charset.php - convert encoding from given to internal charset
* - added modifiercompiler.to_charset.php - convert encoding from internal to given charset


## Registry and File Access ##

http://www.smarty.net/docs/en/template.resources.tpl

* - Smarty:: set/get/addTemplateDir()
* Smarty::registerDefaultTemplateHandler()
* Smarty::registerDefaultConfigHandler()
* Smarty::registerDefaultPluginHandler()
* - Smarty::$use_include_path
* - template_dir identification: {include file="[foo]bar.tpl"} see http://code.google.com/p/smarty-php/source/detail?r=3947
* config uses same Smarty_Resource instances as template
* - ./ and ../ behaviour in {include} and {extend} as well as $smarty->fetch()
* - Smarty::fetch("extends:db:foo.tpl|file:bar.tpl") Smarty_Resources with {extend}
* - eval: and string: resources in conjunction with extend:
* need for proper compile_id when using dynamic inheritance {extends file="{$parent}"}
* SmartySecurity::$allowed_modifiers, SmartySecurity::$disabled_modifiers, SmartySecurity::$allowed_tags, SmartySecurity::$disabled_tags
* Block Plugins output on opening tag
* SmartyBC for BackwardCompatibility
* {php} and {include_php} with SmartyBC only
* add example for php template resource, since it's been removed from source
* $smarty->compile_check = Smarty:.COMPILECHECK_CACHEMISS
* Smarty::$escape_html 


## Smarty_CacheResource ##

* - Smarty_CacheResource_Custom
* - Smarty_CacheResource_KeyValueStore
* - Smarty::registerCacheResource()
* - Smarty::unregisterCacheResource()
* - example distribution/demo/plugins/cacheresource.mysql.php
* - example distribution/demo/plugins/cacheresource.memcache.php


## Smarty_Resource ##

* - see notes.md for infos
* - callback-functions deprecated
* - Smarty_Resource_Custom
* - example distribution/demo/plugins/resource.mysql.php
* - example distribution/demo/plugins/resource.mysqls.php

