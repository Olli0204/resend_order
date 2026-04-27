<?php declare(strict_types=1);

namespace Plugin\resend_order\Models;

use Exception;
use JTL\Model\DataAttribute;
use JTL\Model\DataModel;

/**
 * @property int    $kBestellung
 * @method   int    getKBestellung()
 * @method   void   setKBestellung(int $value)
 * @property string $cBestellNr
 * @method   string getCBestellNr()
 * @method   void   setCBestellNr(string $value)
 * @property string $cAbgeholt
 * @method   string getCAbgeholt()
 * @method   void   setCAbgeholt(string $value)
 * @property string $cZahlungsartName
 * @method   string getCZahlungsartName()
 * @method   void   setCZahlungsartName(string $value)
 * @property float  $fGesamtsumme
 * @method   float  getFGesamtsumme()
 * @method   void   setFGesamtsumme(float $value)
 * @property string $dErstellt
 * @method   string getDErstellt()
 * @method   void   setDErstellt(string $value)
 */
final class PendingOrder extends DataModel
{
    public function getTableName(): string
    {
        return 'tbestellung';
    }

    public function setKeyName($keyName): void
    {
        throw new Exception(__METHOD__ . ': setting of keyname is not supported', self::ERR_DATABASE);
    }

    public function getAttributes(): array
    {
        static $attributes = null;
        if ($attributes !== null) {
            return $attributes;
        }

        $id = DataAttribute::create('kBestellung', 'int', null, false, true);
        $id->getInputConfig()->setHidden(true);

        $status = DataAttribute::create('cAbgeholt', 'varchar', null, false);
        $status->getInputConfig()->setHidden(true);

        $zahlungsart = DataAttribute::create('cZahlungsartName', 'varchar', null, true);
        $zahlungsart->getInputConfig()->setHidden(true);

        $summe = DataAttribute::create('fGesamtsumme', 'decimal', null, true);
        $summe->getInputConfig()->setHidden(true);

        $datum = DataAttribute::create('dErstellt', 'varchar', null, true);
        $datum->getInputConfig()->setHidden(true);

        $attributes = [
            'kBestellung'      => $id,
            'cBestellNr'       => DataAttribute::create('cBestellNr', 'varchar', null, false),
            'cAbgeholt'        => $status,
            'cZahlungsartName' => $zahlungsart,
            'fGesamtsumme'     => $summe,
            'dErstellt'        => $datum,
        ];

        return $attributes;
    }

    public static function loadAll(\JTL\DB\DbInterface $db, $conditions, $limit): array
    {
        return parent::loadAll($db, ['cAbgeholt' => 'P'], $limit);
    }
}
