{extend file='test_block_base.tpl'}
This template should not output anything, ignore all Smarty tags but {block}.
{block name=headline}-- headline from test_block_section.tlp --{/block}
{'Hello World'}
{block name=parent}included parent{/block}
