{if empty($orders)}
<p class="text-muted">Derzeit gibt es keine Bestellungen mit dem Status Pending.</p>
{else}

<div class="row ml-n2 mr-n2 mb-2 align-items-center">
    <div class="col-sm-auto ml-auto">
        <div class="input-group input-group-sm">
            <input type="search" id="table-search" class="form-control" placeholder="Suche...">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fa fa-search"></i></span>
            </div>
        </div>
    </div>
</div>

<form id="modelform" name="modelform" method="post" action="{$action}">
    {$jtl_token}
    <input type="hidden" name="action" value="deleteSelected">
    <input type="hidden" name="id" value="" id="single-id">

    <div class="table-responsive">
        <table class="table table-sm table-striped table-align-top" id="orders-table">
            <thead>
                <tr>
                    <th style="width: 2rem;" class="text-center">
                        <input type="checkbox" id="select-all" name="checkall">
                    </th>
                    <th>Bestellnummer</th>
                    <th>Zahlungsart</th>
                    <th>Gesamtsumme</th>
                    <th>Bestelldatum</th>
                    <th style="width: 4rem;" class="text-right"></th>
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
                    <td class="text-right">
                        <button type="button"
                                class="btn btn-link btn-sm p-0 text-warning"
                                title="Zurücksetzen"
                                onclick="resendSingle({$order->kBestellung|intval}, '{$order->cBestellNr|escape:'javascript'}')">
                            <i class="fa fa-undo"></i>
                        </button>
                    </td>
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
    var search     = document.getElementById('table-search');
    var rows       = document.querySelectorAll('#orders-table tbody tr');

    function updateBtn() {
        massBtn.disabled = !Array.from(checkboxes).some(function (c) {
            return c.checked;
        });
    }

    selectAll.addEventListener('change', function () {
        checkboxes.forEach(function (c) {
            if (c.closest('tr').style.display !== 'none') c.checked = selectAll.checked;
        });
        updateBtn();
    });

    checkboxes.forEach(function (c) {
        c.addEventListener('change', function () {
            var visible = Array.from(checkboxes).filter(function (x) {
                return x.closest('tr').style.display !== 'none';
            });
            selectAll.checked = visible.length > 0 && visible.every(function (x) { return x.checked; });
            updateBtn();
        });
    });

    search.addEventListener('input', function () {
        var term = this.value.toLowerCase();
        rows.forEach(function (r) {
            r.style.display = r.textContent.toLowerCase().indexOf(term) !== -1 ? '' : 'none';
        });
        selectAll.checked = false;
        updateBtn();
    });
}());

function resendSingle(id, nr) {
    if (!confirm('Bestellung ' + nr + ' zurücksetzen?')) return;
    var form = document.getElementById('modelform');
    form.querySelector('[name="action"]').value = 'delete';
    document.getElementById('single-id').name = 'id';
    document.getElementById('single-id').value = id;
    form.submit();
}
</script>

{/if}
