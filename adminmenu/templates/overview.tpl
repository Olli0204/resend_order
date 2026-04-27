{if empty($orders)}
<p class="text-muted">Derzeit gibt es keine Bestellungen mit dem Status Pending.</p>
{else}

<p class="mb-3">Die folgenden Bestellungen wurden vom Warenwirtschaftssystem noch nicht abgeholt (Status <strong>Pending</strong>). Wähle eine oder mehrere Bestellungen aus und setze den Status zurück, damit sie erneut übertragen werden können.</p>

<form id="modelform" name="modelform" method="post" action="{$action}">
    {$jtl_token}
    <input type="hidden" name="action" value="deleteSelected">

    <div class="table-responsive">
        <table class="table table-sm table-striped table-align-top">
            <thead>
                <tr>
                    <th style="width: 2rem;" class="text-center">
                        <input type="checkbox" id="select-all" name="checkall">
                    </th>
                    <th>Bestellnummer</th>
                    <th>Zahlungsart</th>
                    <th>Gesamtsumme</th>
                    <th>Bestelldatum</th>
                </tr>
            </thead>
            <tbody>
                {foreach $orders as $order}
                <tr>
                    <td class="text-center">
                        <input type="checkbox" name="item_ids[]" value="{$order->kBestellung|intval}" class="check-item">
                    </td>
                    <td>{$order->cBestellNr|escape:'html'}</td>
                    <td>{$order->cZahlungsartName|default:'-'|escape:'html'}</td>
                    <td>{$order->fGesamtsumme|string_format:"%.2f"|replace:'.':','} &euro;</td>
                    <td>{$order->dErstellt|escape:'html'}</td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-2 mb-3">
        <button type="submit" id="mass-reset-btn" class="btn btn-sm btn-warning" disabled>
            <i class="fa fa-undo"></i> Auswahl zurücksetzen
        </button>
    </div>
</form>

<script>
(function () {
    var selectAll  = document.getElementById('select-all');
    var massBtn    = document.getElementById('mass-reset-btn');
    var checkboxes = document.querySelectorAll('.check-item');

    function updateBtn() {
        massBtn.disabled = !Array.from(checkboxes).some(function (c) { return c.checked; });
    }

    selectAll.addEventListener('change', function () {
        checkboxes.forEach(function (c) { c.checked = selectAll.checked; });
        updateBtn();
    });

    checkboxes.forEach(function (c) {
        c.addEventListener('change', function () {
            selectAll.checked = Array.from(checkboxes).every(function (x) { return x.checked; });
            updateBtn();
        });
    });
}());
</script>

{/if}
