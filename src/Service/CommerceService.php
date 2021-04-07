<?php

namespace App\Service;

use App\Entity\Produit;
use App\Entity\Categorie;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommerceService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getCategorieById($id)
    {
        $categorie = $this->entityManager->getRepository(Categorie::class)->find($id);
        return $categorie;
    }

    public function getListCategories()
    {
        $categories = $this->entityManager->getRepository(Categorie::class)->findAll();
        return $categories;
    }

    public function getProduitById($id)
    {
        $produit = $this->entityManager->getRepository(Produit::class)->find($id);
        return $produit;
    }
}