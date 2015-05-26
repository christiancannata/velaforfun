<?php

namespace BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;

class CategoriaController extends BaseController
{

    /**
     * @Route("/{permalink}", name="categoria")
     */
    public function showAction($permalink)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $categoria = $em->getRepository('BlogBundle:Categoria')->findOneByPermalink($permalink);
        if (!$categoria) {
            throw $this->createNotFoundException('Unable to find Categoria.');
        }

        $articoli = $em->getRepository('BlogBundle:Articolo')->findBy(array('categoria' => $categoria, 'stato' => "ATTIVO"),array('id' => 'desc'));

        return $this->render(
            'BlogBundle:Categoria:categoria.html.twig',
            array(
                'articoli' => $articoli,
                'categoria' => $categoria
            )
        );
    }
}
