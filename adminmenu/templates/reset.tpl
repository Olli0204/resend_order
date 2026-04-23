<p>Hier die Bestellnummer für den fehlenden Auftrag eintragen.</p>
<form method="post" class="form-inline">
    {$jtl_token}
    <div class="form-group">
        <div class="form-group mr-2">
            <input type="hidden" name="kPluginAdminMenu" value="{$menuID}">
            <input type="text" name="reset_input" value="{$posted|default:''}" class="form-control" size="22" placeholder="46578">
        </div>
        <button class="btn btn-primary" type="submit">{__('submit')}</button> 
    </div>
</form>
<div style="margin-top: 5px;">
    <span>{$output|default:''}</span>
</div>