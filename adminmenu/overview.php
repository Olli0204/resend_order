<?php

    use JTL\Shop;

    $result = Shop::Container()->getDB()->selectAll('tbestellung', 'cAbgeholt', "P");

?>

<span class="font-weight-bold d-block mb-3">Alle Bestellungen mit dem Status Pending</span>

<?php  
    if($result == NULL)
    {
        echo "Derzeit gitb es keine Bestellungen mit dem Status Pending";
    }
    else
    {
?>

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
        </tbody>
            <?php 
            foreach($result as $list){

                $date = new DateTime($list->dErstellt);

                $formattedDate = $date->format('d.m.Y H:i:s');

                $userquery = Shop::Container()->getDB()->select('tkunde', 'kKunde', $list->kKunde);

                $user = $userquery->cMail;

                echo "<tr><td>".$list->cBestellNr."</td><td>".$list->cAbgeholt."</td><td>".$user."</td><td>".$list->cZahlungsartName."</td><td>".$list->fGesamtsumme." €</td><td>".$formattedDate."</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php

        }