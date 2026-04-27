<?php declare(strict_types=1);

namespace Plugin\resend_order;

use JTL\Helpers\Form;
use JTL\Helpers\Request;
use JTL\Plugin\PluginInterface;
use JTL\Router\Controller\Backend\GenericModelController;
use JTL\Shop;
use JTL\Smarty\JTLSmarty;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ModelBackendController extends GenericModelController
{
    public int $menuID = 0;

    public PluginInterface $plugin;

    public function getResponse(ServerRequestInterface $request, array $args, JTLSmarty $smarty): ResponseInterface
    {
        $backendURL = $this->plugin->getPaths()->getBackendURL();
        $post       = (array) $request->getParsedBody();
        $postAction = $post['action'] ?? '';

        if (Form::validateToken()) {
            if ($postAction === 'delete') {
                $id = Request::getInt('id');
                if ($id > 0) {
                    $obj            = new \stdClass();
                    $obj->cAbgeholt = 'N';
                    $this->getDB()->update('tbestellung', 'kBestellung', $id, $obj);
                }
                return (new \Laminas\Diactoros\Response())->withHeader('location', $backendURL);
            }

            if ($postAction === 'deleteSelected' && !empty($post['item_ids'])) {
                foreach ((array) $post['item_ids'] as $id) {
                    $obj            = new \stdClass();
                    $obj->cAbgeholt = 'N';
                    $this->getDB()->update('tbestellung', 'kBestellung', (int) $id, $obj);
                }
                return (new \Laminas\Diactoros\Response())->withHeader('location', $backendURL);
            }
        }

        $smarty->assign('orders', $this->getDB()->selectAll('tbestellung', 'cAbgeholt', 'P'))
               ->assign('action', $backendURL);

        return new HtmlResponse(
            $smarty->fetch(__DIR__ . '/adminmenu/templates/overview.tpl')
        );
    }
}
