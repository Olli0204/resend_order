<?php declare(strict_types=1);

namespace Plugin\resend_order;

use JTL\Helpers\Form;
use JTL\Helpers\Request;
use JTL\Plugin\PluginInterface;
use JTL\Router\Controller\Backend\GenericModelController;
use JTL\Shop;
use JTL\Smarty\JTLSmarty;
use Plugin\resend_order\Models\PendingOrder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ModelBackendController extends GenericModelController
{
    public int $menuID = 0;

    public PluginInterface $plugin;

    public function getResponse(ServerRequestInterface $request, array $args, JTLSmarty $smarty): ResponseInterface
    {
        $this->smarty        = $smarty;
        $this->route         = \str_replace(Shop::getAdminURL(), '', $this->plugin->getPaths()->getBackendURL());
        $this->modelClass    = PendingOrder::class;
        $this->adminBaseFile = \ltrim($this->route, '/');

        $backendURL = $this->plugin->getPaths()->getBackendURL();
        $post       = (array) $request->getParsedBody();
        $postAction = $post['action'] ?? '';

        // Single-row reset — model_list.tpl sends action=delete with id=kBestellung
        if ($postAction === 'delete' && Form::validateToken()) {
            $id = Request::getInt('id');
            if ($id > 0) {
                $obj            = new \stdClass();
                $obj->cAbgeholt = 'N';
                $this->getDB()->update('tbestellung', 'kBestellung', $id, $obj);
            }
            return (new \Laminas\Diactoros\Response())->withHeader('location', $backendURL);
        }

        // Mass reset — model_list.tpl sends action=deleteSelected with item_ids[]
        if ($postAction === 'deleteSelected' && !empty($post['item_ids']) && Form::validateToken()) {
            foreach ((array) $post['item_ids'] as $id) {
                $obj            = new \stdClass();
                $obj->cAbgeholt = 'N';
                $this->getDB()->update('tbestellung', 'kBestellung', (int) $id, $obj);
            }
            return (new \Laminas\Diactoros\Response())->withHeader('location', $backendURL);
        }

        // List view
        $smarty->assign('orders', PendingOrder::loadAll($this->getDB(), [], []))
               ->assign('route', $this->route)
               ->assign('action', $backendURL)
               ->assign('step', 'overview')
               ->assign('tab', 'overview');

        return $this->handle(__DIR__ . '/adminmenu/templates/overview.tpl');
    }
}
