<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Categorie;
use App\Service\CommerceService;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommerceController extends AbstractController
{
    /**
     * @Route("/soap")
     */
    public function index(CommerceService $commerceService)
    {
        $soapServer = new \SoapServer('http://127.0.0.1:8000/commerce.wsdl');
        $soapServer->setObject($commerceService);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');

        ob_start();
        $soapServer->handle();
        $response->setContent(ob_get_clean());

        return $response;
    }

    /**
     * @Route("/go")
     */
    public function afficher(ProduitRepository $productRepository)
    {
        $produit = $productRepository
            ->find(2);
       // $produit = $entityManager->getRepository(Produit::class)->find(1);
        dd($produit);
        return $this->json($categories);
    }
}