<?php

namespace AppBundle\Controller;

use AppBundle\Form\MenuType;
use AppBundle\Form\NodoMenuType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\Overlays\Animation;
use Ivory\GoogleMap\Overlays\Marker;
use Ivory\GoogleMap\Helper\MapHelper;


class MenuController extends BaseController
{

    protected $entity = "Menu";

    /**
     * @Route( "crea", name="create_menu" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request, new MenuType());
    }

    /**
     * @Route( "modifica/{id}", name="modifica_menu" )
     * @Template()
     */
    public function patchAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository("AppBundle:".$this->entity)->find($id);

        $postform = $this->createForm(new MenuType(), $entity);
        $postform->add(
            'callback',
            'hidden',
            array(
                'data' => 'reload',
                'mapped' => false,
                'attr' => array("class" => "callback")
            )
        );
        $nodi = $em->getRepository("AppBundle:NodoMenu")->findByMenu($entity,array("ordering"=>"asc"));

        if ($request->isMethod('POST')) {

            $params = $request->request->all();
            $em = $this->getDoctrine()->getManager();

            foreach($nodi as $nodoMenu){
                $nodoMenu->setMenu(null);
                $em->persist($nodoMenu);
            }

            $em->flush();

            $alberoNodi = json_decode($params['menuJson'], true);
            $entity->setNome($params['appbundle_menu']['nome']);
            $em->persist($entity);
            foreach ($alberoNodi as $key => $nodoAlbero) {


                $nodo = $em->getRepository("AppBundle:NodoMenu")->find($nodoAlbero['id']);

                $nodo->setOrdering($key + 1);

                $nodo->setMenu($entity);

                $figliAttuali = $nodo->getChildren();
                foreach ($figliAttuali as $figlioAttuale) {
                    $figlioAttuale->setParent(null);
                    $em->persist($figlioAttuale);
                }

                if (isset($nodoAlbero['children'])) {


                    foreach ($nodoAlbero['children'] as $keyChildren => $figlio) {
                        $figlioObj = $em->getRepository("AppBundle:NodoMenu")->find($figlio['id']);

                        $figlioObj->setOrdering($keyChildren + 1);
                        $nodo->addChildren($figlioObj);
                        $figlioObj->setMenu($entity);
                    }
                }

                $em->persist($nodo);
            }


            /*
             * $data['title']
             * $data['body']
             */


            $em->flush();


            $response['success'] = true;


            return new JsonResponse($response);
        }

        $formNodo = $this->createForm(new NodoMenuType(),null,array(
            'action' => "/nodomenu/crea",
            'method' => 'POST',
        ));

        return $this->render(
            'AppBundle:Crud:create-menu.html.twig',
            array(
                'menu' => $entity,
                'form' => $postform->createView(),
                "nodi" => $nodi,
                "titolo" => "Modifica ".$this->entity." - ".$id,
                "formNodo" => $formNodo->createView()
            )
        );
    }


    /**
     * @Route( "list", name="list_menu" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }


    /**
     * @Route( "elimina/{id}", name="delete_menu" )
     * @Template()
     */
    public function eliminaAction(Request $request, $id)
    {
        return $this->delete($id);
    }


}
