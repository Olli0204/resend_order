<?php declare(strict_types=1);

namespace Plugin\resend_order;

use JTL\Events\Dispatcher;
use JTL\Helpers\Form;
use JTL\Helpers\Request;
use JTL\Link\LinkInterface;
use JTL\Plugin\Bootstrapper;
use JTL\Shop;
use JTL\Smarty\JTLSmarty;
use JTL\Backend\Notification;

/**
 * Class Bootstrap
 * @package Plugin\resend_order
 */
class Bootstrap extends Bootstrapper
{
    /**
     * @inheritdoc
     */
    public function boot(Dispatcher $dispatcher): void
    {
        $plugin     = $this->getPlugin();
        $backendURL = \method_exists($plugin->getPaths(), 'getBackendURL')
            ? $plugin->getPaths()->getBackendURL()
            : Shop::getAdminURL() . '/plugin.php?kPlugin=' . $plugin->getID();

        $result = $this->getDB()->selectAll('tbestellung', 'cAbgeholt', 'P');
        if (count($result) > 0) {
            Notification::getInstance()->add(
                2,
                $this->getPlugin()->getMeta()->getName(),
                'Es gibt eine Bestellung mit Status Pending!',
                $backendURL
            );
        }
    }

    /**
     * @param array $args
     */
    public function addConsentItem(array $args): void
    {
    }

    /**
     * @inheritdoc
     */
    public function installed(): void
    {
    }

    /**
     * @inheritdoc
     */
    public function updated($oldVersion, $newVersion): void
    {
    }

    /**
     * @inheritdoc
     */
    public function uninstalled(bool $deleteData = true): void
    {
    }

    /**
     * @inheritdoc
     */
    public function prepareFrontend(LinkInterface $link, JTLSmarty $smarty): bool
    {
        return false;
    }

    private function checkOrder(string $ordernumber): bool
    {
        $result = Shop::Container()->getDB()->select('tbestellung', 'cBestellNr', $ordernumber);
        if ($result === null) {
            return false;
        }

        return $result->cAbgeholt === 'P';
    }

    /**
     * @inheritdoc
     */
    public function renderAdminMenuTab(string $tabName, int $menuID, JTLSmarty $smarty): string
    {
        $plugin     = $this->getPlugin();
        $backendURL = \method_exists($plugin->getPaths(), 'getBackendURL')
            ? $plugin->getPaths()->getBackendURL()
            : Shop::getAdminURL() . '/plugin.php?kPlugin=' . $plugin->getID();

        $smarty->assign('menuID', $menuID)
            ->assign('posted', null);

        $template = 'reset.tpl';

        if ($tabName === 'Status zurücksetzen') {
            if (Form::validateToken() && ($posted = Request::postVar('reset_input')) !== null) {
                $smarty->assign('posted', $posted);

                if ($this->checkOrder((string) $posted) === true) {
                    $obj             = new \stdClass();
                    $obj->cAbgeholt  = 'N';
                    Shop::Container()->getDB()->update('tbestellung', 'cBestellNr', (string) $posted, $obj);
                    $smarty->assign('output', 'Erfolgreich geändert!');
                } else {
                    $smarty->assign('output', 'Bestellung hat nicht den Status Pending!');
                }
            }
        }

        return $smarty->assign('backendURL', $backendURL)
            ->fetch($this->getPlugin()->getPaths()->getAdminPath() . '/templates/' . $template);
    }
}
