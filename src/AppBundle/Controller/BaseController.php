<?php

namespace AppBundle\Controller;

use AppBundle\Form\VideoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BaseController extends Controller
{

    public function postForm(Request $request,AbstractType $type)
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
}
