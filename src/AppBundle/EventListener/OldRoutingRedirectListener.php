<?php
namespace AppBundle\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class OldRoutingRedirectListener extends ContainerAware
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Constructor
     *
     * @param LoggerInterface $logger the monolog logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * onKernelRequest called on event kernel.request
     *
     * @param GetResponseEvent $event the event dispatched on kernel.request
     * @return array|null
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();
        $path = $request->getPathInfo();
        $redirect=false;
        $route="homepage";

        if (strstr($path, "comprovendo.asp")) {
            die("jjj");
        }

        if (strstr($path, "portolano/index.asp")) {
            $redirect=true;
            $route="list_segnalazione_portolano";
        }

        if (strstr($path, "porti/index.asp")) {
            $redirect=true;
            $route="porti_italia";
        }

        if (strstr($path, "nodi.asp")) {
            $redirect=true;
            $route="nodi";
        }

        if (strstr($path, "imbarco_i.asp")) {
            die("jjj");
        }

        if (strstr($path, "public/forum/visual.asp")) {
            die("jjj");
        }
        if (strstr($path, "scambio_posto.asp")) {
            die("jjj");
        }

        if (strstr($path, "comunicati/index.asp")) {
            die("jjj");
        }

        if (strstr($path, "comunicati/comunicato_leggi.asp")) {
            die("jjj");
        }

        if (strstr($path, "porti/dettaglio.asp")) {
            die("jjj");

        }

        if($redirect){
            $url = $this->container->get('router')->generate($route);
            $response = new RedirectResponse($url);
            $event->setResponse($response);
        }

    }
}