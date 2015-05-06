<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BaseController extends Controller
{

    public function postForm(Request $request, AbstractType $type)
    {
        $postform = $this->createForm($type);

        if ($request->isMethod('POST')) {

            $postform->handleRequest($request);

            if ($postform->isValid()) {


                /*
                 * $data['title']
                 * $data['body']
                 */
                $data = $postform->getData();
                $em = $this->getDoctrine()->getManager();

                $em->persist($data);
                $em->flush();


                $response['success'] = true;

            } else {

                $response['success'] = false;
                $response['cause'] = $postform->getErrors();

            }

            return new JsonResponse($response);
        }

        return $this->render('AppBundle:Crud:create.html.twig', array('form' => $postform->createView()));
    }

    public function patchForm(Request $request, AbstractType $type, $id)
    {

        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository("AppBundle:".$this->entity)->find($id);

        $postform = $this->createForm($type, $entity);

        if ($request->isMethod('POST')) {

            $postform->handleRequest($request);

            if ($postform->isValid()) {


                /*
                 * $data['title']
                 * $data['body']
                 */
                $em = $this->getDoctrine()->getManager();

                $em->flush();


                $response['success'] = true;

            } else {

                $response['success'] = false;
                $response['cause'] = $postform->getErrors();

            }

            return new JsonResponse($response);
        }

        return $this->render('AppBundle:Crud:create.html.twig', array('form' => $postform->createView()));
    }


    public function cGet()
    {

        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository("AppBundle:".$this->entity)->findAll();


        return $this->render('AppBundle:Crud:list.html.twig', array('entities' => $entities));
    }

    public function delete($id)
    {

        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository("AppBundle:".$this->entity)->find($id);
        if(!$entity){
            $response['success'] = false;
            $response['cause'] = "oggetto da eliminare non trovato!";
        }else{
            $em->remove($entity);
            $em->flush();
            $response['success'] = true;
        }


        return new JsonResponse($response);
    }
}
