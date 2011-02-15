# Documentation ToDo #

these changes must reflect on the documentation at some point…


## Syntax ##

* {block ... hide} option to supress block if no child is defined


## Functions ##

* added attributes [month_names, all_id, year_id, month_id, day_id] to function.html_select_date
* missing attribute all_empty of function.html_select_date
* added attributes [field_separator, option_separator, all_id, hour_id, minute_id, second_id, meridian_id, all_empty, hour_empty, minute_empty, second_empty, meridian_empty, hour_format, hour_value_format, minute_format, minute_value_format, second_format, second_value_format] to function.html_select_time


## Modifiers ##

* added argument $lc_rest to modifier.capitalize - if true, only first character of word is UC, rest is LC
* modifiercompiler.count_sentences treats .?! as proper sentence ending (previously only . counted)
* added argument $double_encode to modifier.escape - if false, &amp; will not be converted to &amp;amp;
* added modifiercompiler.unescape - decode "entity", "htmlall", "html"
* added modifiercompiler.from_charset.php - convert encoding from given to internal charset
* added modifiercompiler.to_charset.php - convert encoding from internal to given charset


## Registry and File Access ##

* Smarty::registerDefaultTemplateHandler()
* Smarty::registerDefaultConfigHandler()
* Smarty::$use_include_path
* template_dir identification: {include file="[foo]bar.tpl"} see http://code.google.com/p/smarty-php/source/detail?r=3947
* config uses same Smarty_Resource instances as template


## Smarty_CacheResource ##

* Smarty_CacheResource_Custom
* Smarty_CacheResource_KeyValueStore
* Smarty::registerCacheResource()
* example development/PHPUnit/PHPunitplugins/cacheresource.mysql.php
* example development/PHPUnit/PHPunitplugins/cacheresource.memcache.php


## Smarty_Resource ##

* see notes.md for infos
* callback-functions deprecated
* Smarty_Resource_Custom
* example development/PHPUnit/PHPunitplugins/resource.mysql.php
* example development/PHPUnit/PHPunitplugins/resource.mysqls.php

