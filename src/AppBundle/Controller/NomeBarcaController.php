<?php

namespace AppBundle\Controller;

use AppBundle\Form\NomeBarcaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\Controller\Bar;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class NomeBarcaController extends BaseController
{
    protected $entity = "NomeBarca";

    /**
     * @Route( "nuova-barca", name="crea_barca" )
     */
    public function createAction(Request $request)
    {

        $postform = $this->createForm(new NomeBarcaType());

        if ($request->isMethod('POST')) {
            $postform->handleRequest($request);

            if ($postform->isValid()) {


                /*
                 * $data['title']
                 * $data['body']
                 */
                $data = $postform->getData();

                $em = $this->getDoctrine()->getManager();

                if (!$em->getRepository('AppBundle:NomeBarca')->findByNome($data->getNome())) {
                    $em->persist($data);
                    $em->flush();


                    $response['success'] = true;
                    $response['response'] = $data->getId();
                } else {
                    $response['success'] = false;
                    $response['response'] = array("nome" => "Questo nome esiste già!");
                }


            } else {
                $response['success'] = false;


                $response['response'] = $this->getErrorsAsArray($postform);

            }

            return new JsonResponse($response);
        }

        return $this->render(
            'AppBundle:Crud:create.html.twig',
            array('form' => $postform->createView(), "titolo" => "Crea ".$this->entity)
        );
    }


    /**
     * @Route( "modifica/{id}", name="modifica_barca" )
     * @Template()
     */
    public function patchAction(Request $request, $id)
    {
        return $this->patchForm($request, new NomeBarcaType(), $id, "Barca");
    }


    /**
     * @Route( "vota/{id}", name="vota_barca" )
     * @Template()
     */
    public function votaAction(Request $request, $id)
    {

        $barca = $this->getDoctrine()->getManager()->getRepository('AppBundle:NomeBarca')->find($id);

        if ($barca) {

            $barca->setPunti($barca->getPunti() + 1);
            $this->getDoctrine()->getManager()->merge($barca);
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse(array("response" => "ok", "success" => true));
        }

    }


    /**
     * @Route( "list", name="list_barca" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }


    /**
     * @Route( "elimina/{id}", name="delete_barca" )
     * @Template()
     */
    public function eliminaAction(Request $request, $id)
    {
        return $this->delete($id);
    }

    /**
     * @Route("/", name="nome_barca_home")
     */
    public function nomiBarcaAction()
    {

        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:NomeBarca');
        $query = $repository->createQueryBuilder('p')
            ->orderBy("p.punti", "DESC");

        $barche = $query->getQuery()->getResult();

        $lettere = array();
        foreach ($barche as $barca) {
            $firstone = substr(strtoupper(trim($barca->getNome())), 0, 1);
            if (!in_array($firstone, $lettere)) {
                $lettere[] = $firstone;
            }
        }

        $form['vars'] = array("full_name" => "appbundle_nome_barca");

        sort($lettere);

        return $this->render(
            'AppBundle:NomeBarca:lista.html.twig',
            array("barche" => $barche, "form" => $form, "lettere" => $lettere)
        );

    }


    /**
     * @Route("/{lettera}", name="nome_barca_singolo")
     */
    public function letteraAction($lettera)
    {


        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:NomeBarca');
        $query = $repository->createQueryBuilder('p')
            ->where("p.nome like '".$lettera."%'");

        $query->orderBy("p.punti", "DESC");

        $barche = $query->getQuery()->getResult();


        $titolo = "Nomi di Barca - ".strtoupper($lettera);

        $lettere = array();
        foreach ($barche as $barca) {
            $firstone = substr(strtoupper(trim($barca->getNome())), 0, 1);
            if (!in_array($firstone, $lettere)) {
                $lettere[] = $firstone;
            }
        }
        $form['vars'] = array("full_name" => "appbundle_nome_barca");

        sort($lettere);

        return $this->render(
            'AppBundle:NomeBarca:lettera.html.twig',
            array("form" => $form, "barche" => $barche, "titolo" => $titolo, "lettere" => $lettere)
        );

    }


}
