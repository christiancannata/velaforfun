<?php
/**
 * Created by PhpStorm.
 * User: christiancannata
 * Date: 06/05/15
 * Time: 21:53
 */
namespace AppBundle\Twig\Extension;

use Symfony\Component\HttpKernel\KernelInterface;

class MenuVelaExtension extends \Twig_Extension {

    private $container;

    /**
     * {@inheritdoc}
     */
    public function getFunctions() {
        return array(
            'menu' => new \Twig_Function_Method($this, 'getMenu')
        );
    }

    /**
     * @param string $string
     * @return int
     */
    public function getMenu ($string) {

        $em=$this->container->get('doctrine')->getManager();
        $menu=$em->getRepository('AppBundle:Menu')->findOneBy(array("nome"=>$string));
        $vociMenu=[];
        if($menu){
            $vociMenu=$em->getRepository('AppBundle:NodoMenu')->findBy(array("menu"=>$menu), array('ordering' => 'ASC'));

        }
        return $vociMenu;
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'my_bundle';
    }

    public function setContainer($container){
        $this->container=$container;
    }
}