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

    private function getNameSpace()
    {
        $matches = array();
        $controller = $this->getRequest()->attributes->get('_controller');
        $namespace=explode("\\",$controller);
        return $namespace[0];
    }

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


                $response['cause'] = $this->getErrorsAsArray($postform);

            }

            return new JsonResponse($response);
        }

        return $this->render(
            'AppBundle:Crud:create.html.twig',
            array('form' => $postform->createView(), "titolo" => "Crea ". $this->entity)
        );
    }

    public function patchForm(Request $request, AbstractType $type, $id)
    {

        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository($this->getNameSpace().":".$this->entity)->find($id);

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

        return $this->render('AppBundle:Crud:create.html.twig', array('form' => $postform->createView(), "titolo" => "Modifica ". $this->entity. " - ".$id));
    }


    public function cJsonGet()
    {

        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository($this->getNameSpace().":".$this->entity)->findAll();


        return $entities;
    }

    public function cGet()
    {

        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository($this->getNameSpace().":".$this->entity)->findAll();


        return $this->render('AppBundle:Crud:list.html.twig', array('entities' => $entities));
    }

    public function delete($id)
    {

        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository($this->getNameSpace().":".$this->entity)->find($id);
        if (!$entity) {
            $response['success'] = false;
            $response['cause'] = "oggetto da eliminare non trovato!";
        } else {
            $em->remove($entity);
            $em->flush();
            $response['success'] = true;
        }


        return new JsonResponse($response);
    }


    public function getErrorsAsArray($form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $key => $child) {
            if ($err = $this->getErrorsAsArray($child)) {
                $errors[$key] = $err;
            }
        }

        return $errors;
    }
}
