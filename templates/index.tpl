{config_load file=test.conf section="my foo"}
{include file=header.tpl title=foo}

Title: {#title#|capitalize}

{$SCRIPT_NAME}

{* A simple variable test *}
hello, my name is {$Name|upper}

My interests are:
{section name=outer loop=$FirstName}
	{if %outer.index% is odd by 2}
		. {$outer/FirstName} {$outer/LastName}
	{else}
		* {$outer/FirstName} {$outer/LastName}
	{/if}
{sectionelse}
	none
{/section}

({$FirstName|@count})

{insert name=paginate}
