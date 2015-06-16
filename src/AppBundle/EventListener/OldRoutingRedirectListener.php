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
        $redirect = false;
        $route = "homepage";
        $params = array();

        if (strstr($path, "comprovendo.asp")) {
            die("jjj");
        }

        if (strstr($path, "portolano/index.asp")) {
            $redirect = true;
            $route = "portolano_homepage";
        }

        if (strstr($path, "porti/index.asp")) {
            $redirect = true;
            $route = "porti_italia";
        }

        if (strstr($path, "calendario/index.asp")) {
            $redirect = true;
            $route = "calendario";
        }

        if (strstr($path, "contattaci.asp")) {
            $redirect = true;
            $route = "contatti";
        }

        if (strstr($path, "newsletter.asp#normal")) {
            $redirect = true;
            $route = "privacy";
        }


        if (strstr($path, "chisiamo.asp")) {
            $redirect = true;
            $route = "chi_siamo";
        }


        if (strstr($path, "nodi.asp")) {
            $redirect = true;
            $route = "nodi";
        }

        if (strstr($path, "bandiere.asp")) {
            $redirect = true;
            $route = "codice-internazionale";
        }


        if (strstr($path, "cucina.asp")) {
            $redirect = true;
            $route = "ricette";
        }

        if (strstr($path, "immagini.asp")) {
            $redirect = true;
            $route = "foto";
        }

        if (strstr($path, "img_visual.asp")) {

            if($request->get("cat")){
                $post = $this->container->get('doctrine')
                    ->getRepository('AppBundle:GalleriaFoto')->find($request->get("cat"));
                if($post) {
                    $redirect = true;
                    $route = "galleria_foto";
                    $params["permalink"] = $post->getPermalink();
                }else{
                    $redirect = true;
                    $route = "foto";
                }

            }else{
                $redirect = true;
                $route = "foto";
            }

        }


        if (strstr($path, "cucina_v.asp")) {

            if($request->get("ricetta")){
                $post = $this->container->get('doctrine')
                    ->getRepository('BlogBundle:Articolo')->findOneByIdOriginale($request->get("ricetta"));
                if($post) {
                    $redirect = true;
                    $route = "articolo";
                    $params["categoria"] = $post->getCategoria()->getPermalink();
                    $params["permalink"] = $post->getPermalink();
                }else{
                    $redirect = true;
                    $route = "ricette";
                }

            }else{
                $redirect = true;
                $route = "ricette";
            }

        }



        if (strstr($path, "imbarco_i.asp")) {
            die("jjj");
        }

        if (strstr($path, "public/forum/visual.asp")) {
            if($request->get("post")){
                $post = $this->container->get('doctrine')
                    ->getRepository('AppBundle:CompatibilitaForum')->findOneByIdOld($request->get("post"));
                if($post) {
                    $redirect = true;
                    $route = "ccdn_forum_user_topic_show";
                    $params["forumName"] = "velaforfun";
                    $params["topicId"] = $post->getIdNew();
                }else{
                    $redirect = true;
                    $route = "ccdn_forum_user_topic_show";
                    $params["forumName"] = "velaforfun";
                }

            }else{
                $redirect = true;
                $route = "ccdn_forum_user_topic_show";
                $params["forumName"] = "velaforfun";
            }

        }

        if (strstr($path, "forumdir.asp")) {
            if($request->get("forum")){
                $post = $this->container->get('doctrine')
                    ->getRepository('AppBundle:CompatibilitaForum')->findOneByIdOld($request->get("forum"));
                if($post){
                    $redirect = true;
                    $route = "ccdn_forum_user_category_index";
                    $params["forumName"] = "velaforfun";
                    $params["topicId"] = $post->getIdNew();
                }else{
                    $redirect = true;
                    $route = "ccdn_forum_user_category_index";
                    $params["forumName"] = "velaforfun";
                }

            }else{
                $redirect = true;
                $route = "ccdn_forum_user_category_index";
                $params["forumName"] = "velaforfun";
            }

        }



        if (strstr($path, "scambio_posto.asp")) {
            die("jjj");
        }

        if (strstr($path, "comunicati/index.asp")) {
            $redirect = true;
            $route = "BloggerBlogBundle_blog_show_categoria";
            $params["permalink"] = "comunicati";
        }

        if (strstr($path, "comunicati/comunicato_leggi.asp")) {
            die("jjj");
        }

        if (strstr($path, "porti/dettaglio.asp")) {
            if($request->get("id")){
                $post = $this->container->get('doctrine')
                    ->getRepository('AppBundle:Porto')->findOneByIdOriginale($request->get("id"));
                if($post) {
                    $redirect = true;
                    $route = "dettaglio_porto";
                    $params["permalink"] = $post->getPermalink();
                }else{
                    $redirect = true;
                    $route = "porti_italia";
                }

            }else{
                $redirect = true;
                $route = "porti_italia";
            }

        }

        if ($redirect) {
            $url = $this->container->get('router')->generate($route, $params);
            $response = new RedirectResponse($url);
            $event->setResponse($response);
        }
    }
}