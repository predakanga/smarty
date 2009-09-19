{extend file='test_block_section.tpl'}
{block name='title'}-- My titel --{/block}
{block name=headline}-- Yes we can --{/block}
{block name="description"} assigned description {$foo} {/block}
{block name="parent"}this is an {$smarty.parent} from block_section{/block}
