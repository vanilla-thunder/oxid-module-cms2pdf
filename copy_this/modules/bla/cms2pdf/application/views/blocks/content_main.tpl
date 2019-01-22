[{* [{if $oView->getClassName() == 'content'}]
    [{if !$oContent}][{assign var="oContent" value=$oView->getContent()}][{/if}]
    <div class="show-for-print visible-print-block" style="@media screen { display: none !important; }">
        <span class="h3">[{$oxcmp_shop->oxshops__oxname->value}] - [{$oContent->getTitle()}]</span>
        <small>( [{oxmultilang ident="BLAPDF_PRINTHEADER"}] [{$oContent->getLink()}] )</small>
    </div>
[{/if}] *}]
[{$smarty.block.parent}]
[{if $oView->getClassName() == 'content'}]
    <div class="hide-for-print hidden-print" style="@media print { display: none !important; }">
        [{oxmultilang ident="BLAPDF_PRINT"}]
        [{if $oContent->getPdfLink()}]
            [{oxmultilang ident="BLAPDF_OR"}]
            <a href="[{$oContent->getPdfLink()}]" target="_blank">[{oxmultilang ident="BLAPDF_DOWNLOADPDF"}]</a>
            <small>([{oxmultilang ident="BLAPDF_PDFREADER"}] <a href="[{$oContent->getPdfReaderLink()}]" target="_blank">PDF Reader</a>)</small>
        [{/if}]
    </div>
[{/if}]
