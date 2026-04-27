<?php declare(strict_types=1);

use JTL\Shop;
use JTL\Helpers\Form;

$message     = null;
$messageType = 'success';

if (Form::validateToken() && !empty($_POST['orders']) && is_array($_POST['orders'])) {
    $count = 0;
    foreach ($_POST['orders'] as $rawOrderNr) {
        $orderNr = (string) $rawOrderNr;
        $order   = Shop::Container()->getDB()->select('tbestellung', 'cBestellNr', $orderNr);
        if ($order !== null && $order->cAbgeholt === 'P') {
            $obj            = new \stdClass();
            $obj->cAbgeholt = 'N';
            Shop::Container()->getDB()->update('tbestellung', 'cBestellNr', $orderNr, $obj);
            $count++;
        }
    }
    $message = $count . ' Bestellung(en) erfolgreich zurückgesetzt.';
}

$result = Shop::Container()->getDB()->selectAll('tbestellung', 'cAbgeholt', 'P');

?>

<?php if ($message !== null): ?>
<div class="alert alert-<?= $messageType ?> alert-dismissible fade show mb-3" role="alert">
    <i class="fa fa-check-circle mr-2"></i>
    <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Schließen">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <div class="subheading1">Bestellungen mit Status Pending</div>
        <hr class="mb-n3">
    </div>

    <?php if (empty($result)): ?>

    <div class="card-body">
        <p class="text-muted mb-0">Derzeit gibt es keine Bestellungen mit dem Status Pending.</p>
    </div>

    <?php else: ?>

    <form method="post" id="overview-form">
        <?= Form::getTokenInput() ?>

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

        <div class="table-responsive">
            <table class="table table-striped table-hover table-align-top" id="orders-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" id="select-all" title="Alle auswählen">
                        </th>
                        <th>Bestellnummer</th>
                        <th>Status</th>
                        <th>Kundenemail</th>
                        <th>Zahlungsart</th>
                        <th>Gesamtsumme</th>
                        <th>Bestelldatum</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result as $list):
                        $date          = new DateTime($list->dErstellt);
                        $formattedDate = $date->format('d.m.Y H:i:s');
                        $userquery     = Shop::Container()->getDB()->select('tkunde', 'kKunde', $list->kKunde);
                        $email         = $userquery !== null ? $userquery->cMail : '(Kunde gelöscht)';
                        $orderNr       = htmlspecialchars($list->cBestellNr, ENT_QUOTES, 'UTF-8');
                    ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="orders[]" value="<?= $orderNr ?>" class="order-cb">
                        </td>
                        <td><?= $orderNr ?></td>
                        <td><span class="badge badge-warning">Pending</span></td>
                        <td><?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($list->cZahlungsartName, ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars(number_format((float) $list->fGesamtsumme, 2, ',', '.'), ENT_QUOTES, 'UTF-8') ?> €</td>
                        <td><?= htmlspecialchars($formattedDate, ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                    <?php endforeach; ?>
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

    <?php endif; ?>
</div>

<script>
(function () {
    var selectAll  = document.getElementById('select-all');
    var resetBtn   = document.getElementById('reset-btn');
    var checkboxes = document.querySelectorAll('.order-cb');
    var searchInput = document.getElementById('table-search');
    var tableRows   = document.querySelectorAll('#orders-table tbody tr');

    function updateButton() {
        var anyChecked = Array.from(checkboxes).some(function (cb) { return cb.checked; });
        resetBtn.disabled = !anyChecked;
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
            var visibleBoxes = Array.from(checkboxes).filter(function (c) {
                return c.closest('tr').style.display !== 'none';
            });
            selectAll.checked = visibleBoxes.length > 0 && visibleBoxes.every(function (c) { return c.checked; });
            updateButton();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            var term = this.value.toLowerCase();
            tableRows.forEach(function (row) {
                var text = row.textContent.toLowerCase();
                row.style.display = text.indexOf(term) !== -1 ? '' : 'none';
            });
            selectAll.checked = false;
            updateButton();
        });
    }
}());
</script>
