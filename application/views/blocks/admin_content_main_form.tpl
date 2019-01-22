[{capture assign="parentblock"}][{$smarty.block.parent}][{/capture}]
[{$parentblock|replace:'padding-top:20px;padding-bottom:20px;':''}]
<tr>
    <td class="edittext" colspan="2" style="padding: 0 0 5px 0;">
        <input type="hidden" name="generatepdf" value='0'/>
        <input class="edittext" type="checkbox" name="generatepdf" value='1' [{if $edit->getPdfLink()}]checked[{/if}] [{ $readonly }] id="blapdf">
        <label for="blapdf">[{ oxmultilang ident="bla_cms2pdf_generatepdf" }]</label>
    </td>
</tr>
[{if $edit->getPdfLink() }]
    <tr>
        <td class="edittext" colspan="2" style="padding: 5px 0 10px 0;">
            <a href='[{$edit->getPdfLink()}]' target="_blank"/>[{$edit->getPdfLink()}]</a>
        </td>
    </tr>
[{/if}]
<tr>
    <td colspan="2">
        <hr>
    </td>
</tr>