<?php declare(strict_types=1);

namespace Plugin\resend_order;

use JTL\Widgets\AbstractWidget;
use JTL\Shop;

/**
 * Class InfoWidget
 * @package Plugin\resend_order
 */
class InfoWidget extends AbstractWidget
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $result = Shop::Container()->getDB()->selectAll('tbestellung', 'cAbgeholt', 'P');
        $count  = count($result);
        $this->oSmarty->assign('output', $count);
        $this->oSmarty->assign('color_active', '');

        if ($count > 0) {
            $this->oSmarty->assign('color_active', 'color: #ff000099;');
        }

        $plugin     = $this->getPlugin();
        $backendURL = \method_exists($plugin->getPaths(), 'getBackendURL')
            ? $plugin->getPaths()->getBackendURL()
            : Shop::getAdminURL() . '/plugin.php?kPlugin=' . $plugin->getID();

        $this->oSmarty->assign('plugin_path', $backendURL);
    }

    /**
     * @inheritDoc
     */
    public function getContent()
    {
        return $this->oSmarty->fetch(__DIR__ . '/infowidget.tpl');
    }
}
