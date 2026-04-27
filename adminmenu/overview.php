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
            $obj             = new \stdClass();
            $obj->cAbgeholt  = 'N';
            Shop::Container()->getDB()->update('tbestellung', 'cBestellNr', $orderNr, $obj);
            $count++;
        }
    }
    $message = $count . ' Bestellung(en) erfolgreich zurückgesetzt.';
}

$result = Shop::Container()->getDB()->selectAll('tbestellung', 'cAbgeholt', 'P');

?>

<span class="font-weight-bold d-block mb-3">Alle Bestellungen mit dem Status Pending</span>

<?php if ($message !== null): ?>
<div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Schließen">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>

<?php if (empty($result)): ?>

    <p>Derzeit gibt es keine Bestellungen mit dem Status Pending.</p>

<?php else: ?>

<form method="post" id="overview-form">
    <?= Form::getTokenInput() ?>

    <div class="table-responsive">
        <table class="table table-striped table-align-top">
            <thead>
                <tr>
                    <th style="width: 40px;">
                        <input type="checkbox" id="select-all" title="Alle auswählen">
                    </th>
                    <th>Bestellnummer</th>
                    <th>Abgeholt</th>
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
                        <input type="checkbox" name="orders[]" value="<?= $orderNr ?>">
                    </td>
                    <td><?= $orderNr ?></td>
                    <td><?= htmlspecialchars($list->cAbgeholt, ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($list->cZahlungsartName, ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) $list->fGesamtsumme, ENT_QUOTES, 'UTF-8') ?> €</td>
                    <td><?= htmlspecialchars($formattedDate, ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-2">
        <button type="submit" class="btn btn-warning" id="reset-btn" disabled>
            Ausgewählte Bestellungen zurücksetzen
        </button>
    </div>
</form>

<script>
(function () {
    const selectAll  = document.getElementById('select-all');
    const resetBtn   = document.getElementById('reset-btn');
    const checkboxes = document.querySelectorAll('input[name="orders[]"]');

    function updateButton() {
        const anyChecked = Array.from(checkboxes).some(function (cb) { return cb.checked; });
        resetBtn.disabled = !anyChecked;
    }

    selectAll.addEventListener('change', function () {
        checkboxes.forEach(function (cb) { cb.checked = selectAll.checked; });
        updateButton();
    });

    checkboxes.forEach(function (cb) {
        cb.addEventListener('change', function () {
            selectAll.checked = Array.from(checkboxes).every(function (c) { return c.checked; });
            updateButton();
        });
    });
}());
</script>

<?php endif; ?>
