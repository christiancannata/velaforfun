<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoriaController extends Controller
{

    public function showAction($permalink)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $categoria = $em->getRepository('BlogBundle:Categoria')->findOneByPermalink($permalink);
        if (!$categoria) {
            throw $this->createNotFoundException('Unable to find Categoria.');
        }

        $articoli=$em->getRepository('BlogBundle:Articolo')->findByCategoria($categoria);

        return $this->render('BlogBundle:Categoria:show.html.twig', array(
            'articoli'      => $articoli,
        ));
    }
}
