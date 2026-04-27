<?php declare(strict_types=1);

use JTL\Shop;

$result = Shop::Container()->getDB()->selectAll('tbestellung', 'cAbgeholt', 'P');

?>

<span class="font-weight-bold d-block mb-3">Alle Bestellungen mit dem Status Pending</span>

<?php if (empty($result)): ?>

    <p>Derzeit gibt es keine Bestellungen mit dem Status Pending.</p>

<?php else: ?>

<div class="table-responsive">
    <table class="table table-striped table-align-top">
        <thead>
            <tr>
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
            ?>
            <tr>
                <td><?= htmlspecialchars($list->cBestellNr, ENT_QUOTES, 'UTF-8') ?></td>
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

<?php endif; ?>
