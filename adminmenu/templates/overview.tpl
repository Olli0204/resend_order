<div class="card">
    <div class="card-header">
        <div class="subheading1">Bestellungen mit Status Pending</div>
        <hr class="mb-n3">
    </div>

    {if count($orders) === 0}

    <div class="card-body">
        <p class="text-muted mb-0">Derzeit gibt es keine Bestellungen mit dem Status Pending.</p>
    </div>

    {else}

    <div class="card-body pb-0">
        <div class="row">
            <div class="col-sm-5 ml-auto">
                <div class="input-group mb-3">
                    <input type="text" id="table-search" class="form-control" placeholder="Suche...">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="modelform" method="post" action="{$action}">
        {$jtl_token}
        <input type="hidden" name="action" value="deleteSelected">

        <div class="table-responsive">
            <table class="table table-striped table-hover table-align-top" id="orders-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" id="select-all" title="Alle auswählen">
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
                        <td>
                            <input type="checkbox" name="item_ids[]" value="{$order->getKBestellung()|intval}" class="order-cb">
                        </td>
                        <td>{$order->getCBestellNr()|escape:'html'}</td>
                        <td>{$order->getCZahlungsartName()|default:'-'|escape:'html'}</td>
                        <td>{$order->getFGesamtsumme()|string_format:"%.2f"|replace:'.':','} &euro;</td>
                        <td>{$order->getDErstellt()|escape:'html'}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>

        <div class="card-footer save-wrapper">
            <div class="row first-ml-auto">
                <div class="col-sm-6 col-xl-auto">
                    <button type="submit" class="btn btn-warning btn-block" id="reset-btn" disabled>
                        <i class="fa fa-undo mr-1"></i> Ausgewählte zurücksetzen
                    </button>
                </div>
            </div>
        </div>
    </form>

    {/if}
</div>

<script>
(function () {
    var selectAll   = document.getElementById('select-all');
    var resetBtn    = document.getElementById('reset-btn');
    var checkboxes  = document.querySelectorAll('.order-cb');
    var searchInput = document.getElementById('table-search');
    var tableRows   = document.querySelectorAll('#orders-table tbody tr');

    function updateButton() {
        resetBtn.disabled = !Array.from(checkboxes).some(function (cb) { return cb.checked; });
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(function (cb) {
                if (cb.closest('tr').style.display !== 'none') {
                    cb.checked = selectAll.checked;
                }
            });
            updateButton();
        });
    }

    checkboxes.forEach(function (cb) {
        cb.addEventListener('change', function () {
            var visible = Array.from(checkboxes).filter(function (c) {
                return c.closest('tr').style.display !== 'none';
            });
            if (selectAll) {
                selectAll.checked = visible.length > 0 && visible.every(function (c) { return c.checked; });
            }
            updateButton();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            var term = this.value.toLowerCase();
            tableRows.forEach(function (row) {
                row.style.display = row.textContent.toLowerCase().indexOf(term) !== -1 ? '' : 'none';
            });
            if (selectAll) selectAll.checked = false;
            updateButton();
        });
    }
}());
</script>
