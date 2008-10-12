Test caching and nocache attribute
<br>cached time is {time()}
<br>nocached time by '{time() nocache}' {time() nocache}
<br>
<br>calling '{include file="nocache_inc.tpl" caching_lifetime=25}' {include file="nocache_inc.tpl" caching_lifetime=25}
<br>
<br>calling '{include file="nocache_inc.tpl" nocache}' {include file="nocache_inc.tpl" nocache}
