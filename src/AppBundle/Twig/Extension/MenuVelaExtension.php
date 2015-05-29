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
            'menu' => new \Twig_Function_Method($this, 'getMenu'),
            'meteo' => new \Twig_Function_Method($this, 'getMeteo')
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


    public function getMeteo ($string) {


        $meteoIcon = [];
        $meteoIcon["01d"] = "wi-day-sunny";
        $meteoIcon["02d"] = "wi-day-cloudy";
        $meteoIcon["03d"] = "wi-cloud";
        $meteoIcon["04d"] = "wi-cloudy";
        $meteoIcon["09d"] = "wi-showers";
        $meteoIcon["10d"] = "wi-day-showers";
        $meteoIcon["11d"] = "wi-thunderstorm";
        $meteoIcon["13d"] = "wi-snow";
        $meteoIcon["50d"] = "wi-fog";
        $meteoIcon["01n"] = "wi-night-sunny";
        $meteoIcon["02n"] = "wi-night-cloudy";
        $meteoIcon["03n"] = "wi-cloud";
        $meteoIcon["04n"] = "wi-cloudy";
        $meteoIcon["09n"] = "wi-showers";
        $meteoIcon["10n"] = "wi-night-showers";
        $meteoIcon["11n"] = "wi-thunderstorm";
        $meteoIcon["13n"] = "wi-snow";
        $meteoIcon["50n"] = "wi-fog";


        return $meteoIcon[$string];
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