<?php declare(strict_types=1);

namespace Plugin\resend_order\Models;

use Exception;
use JTL\Model\DataAttribute;
use JTL\Model\DataModel;
use JTL\Shop;

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
        throw new Exception(__METHOD__ . ': not supported', self::ERR_DATABASE);
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

        $attributes = [
            'kBestellung'      => $id,
            'cBestellNr'       => DataAttribute::create('cBestellNr', 'varchar', null, false),
            'cAbgeholt'        => $status,
            'cZahlungsartName' => DataAttribute::create('cZahlungsartName', 'varchar', null, true),
            'fGesamtsumme'     => DataAttribute::create('fGesamtsumme', 'decimal', null, true),
            'dErstellt'        => DataAttribute::create('dErstellt', 'varchar', null, true),
        ];

        return $attributes;
    }

    /**
     * model_list.tpl calls getId() to build per-row action URLs.
     * We alias it to our primary key kBestellung.
     */
    public function getId(): int
    {
        return (int) $this->getKBestellung();
    }

    /**
     * Called by GenericModelController::handle() for delete actions.
     * We reset the order status instead of deleting the record.
     */
    public function delete(): bool
    {
        $obj            = new \stdClass();
        $obj->cAbgeholt = 'N';
        Shop::Container()->getDB()->update('tbestellung', 'kBestellung', $this->getId(), $obj);
        return true;
    }

    /**
     * Always filter to pending orders only.
     * Uses N+1 via loadByAttributes to return proper DataModel instances.
     */
    public static function loadAll(\JTL\DB\DbInterface $db, $conditions, $limit): array
    {
        $rows = $db->selectAll('tbestellung', 'cAbgeholt', 'P');

        return array_values(array_filter(array_map(
            static fn($row) => static::loadByAttributes(['kBestellung' => (int) $row->kBestellung], $db),
            $rows
        )));
    }
}
