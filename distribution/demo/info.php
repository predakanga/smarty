<?php

require_once dirname(__FILE__) . '/../libs/Smarty.class.php';

ini_set('pcre.backtrack_limit', -1);

$smarty = new Smarty();
$smarty->setTemplateDir(array(
    'mine' => '../../my-templates',
    'default' => './templates',
));
$smarty->addPluginsDir('./plugins');

$smarty->right_delimiter = $smarty->left_delimiter;
$smarty->caching = 1;
$smarty->cache_lifetime = 336699;

$tpl = $smarty->createTemplate('eval:foobar');
$tpl->caching = 2;
echo $tpl->info();