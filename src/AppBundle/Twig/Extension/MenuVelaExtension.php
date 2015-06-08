<?php
/**
 * Created by PhpStorm.
 * User: christiancannata
 * Date: 06/05/15
 * Time: 21:53
 */
namespace AppBundle\Twig\Extension;

use Symfony\Component\HttpKernel\KernelInterface;

class MenuVelaExtension extends \Twig_Extension
{

    private $container;

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'menu' => new \Twig_Function_Method($this, 'getMenu'),
            'meteo' => new \Twig_Function_Method($this, 'getMeteo'),
            'getRuolo' => new \Twig_Function_Method($this, 'getRuolo'),
        );
    }

    public function getFilters()
    {
        return array(
            'auto_link_text' => new \Twig_Filter_Method($this, 'auto_link_text', array('is_safe' => array('html'))),
            'replace_tags' => new \Twig_Filter_Method($this, 'replace_tags', array('is_safe' => array('html')))
        );
    }

    /**
     * @param string $string
     * @return int
     */
    public function getMenu($string)
    {

        $em = $this->container->get('doctrine')->getManager();
        $menu = $em->getRepository('AppBundle:Menu')->findOneBy(array("nome" => $string));
        $vociMenu = [];
        if ($menu) {
            $vociMenu = $em->getRepository('AppBundle:NodoMenu')->findBy(
                array("menu" => $menu),
                array('ordering' => 'ASC')
            );

        }

        return $vociMenu;
    }


    /**
     * @param string $string
     * @return int
     */
    public function getRuolo($user)
    {
        $messaggi = count($user->getMessaggi());
        //RUOLI SUI MESSAGGI
        switch ($messaggi) {
            case ($messaggi < 30):
                $ruolo = "Velista in porto";
                break;
            case ($messaggi >= 30 && $messaggi <= 100):
                $ruolo = "Mozzo";
                break;
            case ($messaggi >= 100 && $messaggi <= 500):
                $ruolo = "Velista";
                break;
            case ($messaggi >= 500 && $messaggi <= 700):
                $ruolo = "Skipper";
                break;
            case ($messaggi >= 700 && $messaggi <= 1000):
                $ruolo = "Velista d'alto mare";
                break;
            case ($messaggi > 1000):
                $ruolo = "Capitano";
                break;
            default:
                $ruolo="Velista in porto";
                break;
        }

        return $ruolo;
    }


    public function auto_link_text($string)
    {

        return preg_replace(
            '/\s((http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.\/]+\.[a-zA-Z\/]{2,3}(\/\S*)?)/',
            '<a target="_blank" href="${1}">${1}</a>',
            $string
        );

    }

    public function replace_tags($string)
    {

        $pattern = Array();
        $pattern[0] = "~\[img](.+?)\[/img]~i";
        $replacement = Array();
        $replacement[0] = '<img src="${1}" />';

        return preg_replace($pattern, $replacement, $string);
    }

    public function getMeteo($string)
    {


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
    public function getName()
    {
        return 'my_bundle';
    }

    public function setContainer($container)
    {
        $this->container = $container;
    }
}