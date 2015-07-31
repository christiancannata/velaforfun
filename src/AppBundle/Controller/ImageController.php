<?php

namespace AppBundle\Controller;

use AppBundle\Form\NodoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Finder\Finder;

class ImageController extends BaseController
{

    /**
     * @Route( "list", name="list_images" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        $json = array();

        $finder = new Finder();
        $finder->files()->in('../web/images/articoli');

        foreach ($finder as $file) {
            $attributes = [
                "thumb" => "/images/articoli/".$file->getFilename(),
                "image" => "/images/articoli/".$file->getFilename(),
                "title" => $file->getFilename(),
                "folder" => "articoli",
            ];
            $json[] = $attributes;
        }


        return new JsonResponse($json);
    }


    /**
     * @Route( "upload", name="upload_images" )
     */
    public function uploadAction(Request $request)
    {
        $dir = '../web/images/articoli';


        $files = $request->files;

        $array=array();

        foreach ($files as $uploadedFile) {
            $filename = $uploadedFile->getClientOriginalName()."-".md5(date('YmdHis')).".".$uploadedFile->guessExtension();


            if($uploadedFile->move($dir, $filename)){
                // displaying file
                $array[]= array(
                    'filelink' => '/images/articoli/'.$filename
                );
            }

        }

        return new JsonResponse($array);
    }


}
