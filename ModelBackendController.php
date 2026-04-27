<?php declare(strict_types=1);

namespace Plugin\resend_order;

use JTL\Alert\Alert;
use JTL\Helpers\Form;
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

        if (($post['action'] ?? '') === 'deleteSelected'
            && !empty($post['item_ids'])
            && Form::validateToken()
        ) {
            $count = 0;
            foreach ((array) $post['item_ids'] as $id) {
                $obj            = new \stdClass();
                $obj->cAbgeholt = 'N';
                $this->getDB()->update('tbestellung', 'kBestellung', (int) $id, $obj);
                $count++;
            }
            Shop::Container()->getAlertService()->addAlert(
                Alert::TYPE_SUCCESS,
                $count . ' Bestellung(en) erfolgreich zurückgesetzt.',
                'resendOrderReset'
            );
            return (new \Laminas\Diactoros\Response())->withHeader('location', $backendURL);
        }

        $orders = $this->getDB()->selectAll('tbestellung', 'cAbgeholt', 'P');

        $smarty->assign('orders', $orders)
               ->assign('action', $backendURL);

        return new HtmlResponse(
            $smarty->fetch(__DIR__ . '/adminmenu/templates/overview.tpl')
        );
    }
}
