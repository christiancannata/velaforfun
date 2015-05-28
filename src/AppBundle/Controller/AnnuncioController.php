<?php

namespace AppBundle\Controller;

use AppBundle\Form\AnnuncioType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\Controller\Bar;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CCDNForum\ForumBundle\Entity\Post;
use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Board;


class AnnuncioController extends BaseController
{
    protected $entity = "Annuncio";

    /**
     * @Route( "crea", name="create_annuncio" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        $postform = $this->createForm(new AnnuncioType());

        if ($request->isMethod('POST')) {

            $postform->handleRequest($request);

            if ($postform->isValid()) {

                $annuncio = $postform->getData();
                $em = $this->container->get('doctrine')->getManager();

                $repository = $this->container->get('doctrine')
                    ->getRepository('AppBundle:User');
                $user = $repository->findOneBy(array("email" => $annuncio->getEmail()));
                if (!$user) {
                    $userManager = $this->container->get('fos_user.user_manager');
                    $user = $userManager->createUser();
                    $user->setEmail($annuncio->getEmail());
                    $user->setNome($annuncio->getReferente());
                    $username = strtolower(str_replace(" ", "", $annuncio->getReferente()));
                    $user->setUsername($username);
                    $user->setPlainPassword($username."1");
                    $user->setEnable(true);
                    $em->persist($user);
                    $em->flush();
                }


                $firstTopic = new Topic();
                $firstTopic->setTitle($annuncio->getTitolo());
                $firstTopic->setCachedViewCount(1);

                if ($annuncio->getTipo() == "COMPRO") {
                    $firstTopic->setBoard(
                        $this->container->get('doctrine')
                            ->getRepository('CCDNForumForumBundle:Board')->find(17)
                    );
                } else {
                    $firstTopic->setBoard(
                        $this->container->get('doctrine')
                            ->getRepository('CCDNForumForumBundle:Board')->find(18)
                    );
                }


                $em->persist($firstTopic);
                $em->flush();


                $post = new Post();
                $post->setTopic($firstTopic);
                $post->setCreatedDate(new \DateTime());
                $post->setCreatedBy(
                    $user
                );


                $post->setBody($annuncio->getDescrizione());

                $em->persist($post);
                $em->flush();


                $annuncio->setTopic($firstTopic);
                $em->persist($annuncio);
                $em->flush();


                $response['success'] = true;
                $response['response'] = $annuncio->getId();


            } else {
                $response['success'] = false;


                $response['reponse'] = $this->getErrorsAsArray($postform);

            }

            return new JsonResponse($response);
        }

        return $this->render(
            'AppBundle:Crud:create.html.twig',
            array('form' => $postform->createView(), "titolo" => "Crea ".$this->entity)
        );
    }


    /**
     * @Route( "modifica/{id}", name="modifica_annuncio" )
     * @Template()
     */
    public function patchAction(Request $request, $id)
    {
        return $this->patchForm($request, new AnnuncioType(), $id, "Annuncio");
    }


    /**
     * @Route( "list", name="list_annuncio" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }


    /**
     * @Route( "elimina/{id}", name="delete_annuncio" )
     * @Template()
     */
    public function eliminaAction(Request $request, $id)
    {
        return $this->delete($id);
    }
}
