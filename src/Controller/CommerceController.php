<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Produit;
use App\Entity\Categorie;
use App\Service\CommerceService;
use App\Repository\ProduitRepository;
use Doctrine\DBAL\Driver\PDOException;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Exception\ConnectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

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
    public function afficher()
    {
        try {
            $categorie = $this->getDoctrine()->getRepository(Categorie::class)->find(35);
                $this->getDoctrine()->getManager()->remove($categorie);
                $this->getDoctrine()->getManager()->flush();
                $message = new Message("200", "OK");
            
                
        } catch (Message $e) {
            dump($e);
        }
        catch(\Exception $e) {
            dump($e);
        }
        
        return $this->json("");       
    }
}