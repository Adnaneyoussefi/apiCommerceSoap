<?php


namespace App\Service;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;

class EcommerceService
{
  private  $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function hello($id)
    {

        $categorie = $this->entityManager->getRepository(Categorie::class)->find($id);

        $xml=$categorie->getNom();

        return $xml;
    }



}