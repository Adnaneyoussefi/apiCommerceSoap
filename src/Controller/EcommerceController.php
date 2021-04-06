<?php
namespace App\Controller;
use App\Service\EcommerceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EcommerceController extends AbstractController
{
    /**
     * @Route("/soap")
     */
    public function index(EcommerceService $ecoService)
    {
        $soapServer = new \SoapServer('http://127.0.0.1:8000/ecommerce.wsdl');
        $soapServer->setObject($ecoService);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');

        ob_start();
        $soapServer->handle();
        $response->setContent(ob_get_clean());

        return $response;
    }
}