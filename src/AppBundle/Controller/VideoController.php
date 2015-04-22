<?php

namespace AppBundle\Controller;

use AppBundle\Form\VideoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class VideoController extends Controller
{
    /**
     * @Route( "/post/new", name="create_post" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        $postform = $this->createForm(new VideoType());

        if ($request->isMethod('POST')) {

            $postform->handleRequest($request);

            if ($postform->isValid()) {

                /*
                 * $data['title']
                 * $data['body']
                 */
                $data = $postform->getData();

                $response['success'] = true;

            } else {

                $response['success'] = false;
                $response['cause'] = 'whatever';

            }

            return new JsonResponse($response);
        }

        return array(
            'postform' => $postform->createView()
        );
    }
}
