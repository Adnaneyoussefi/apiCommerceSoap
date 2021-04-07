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

    public function addNewCategorie($nom){
        $categorie = new Categorie();
        $categorie->setNom($nom);
        $this->entityManager->persist($categorie);
        $this->entityManager->flush();
        return "l'ajout avec succés!";
    }

    public function deleteCategorie($id){
        $categorie = $this->entityManager->getRepository(Categorie::class)->find($id);
        $this->entityManager->remove($categorie);
        $this->entityManager->flush();
        return "supression avec succés!";
    }

    public function updateCategorie($id,$nom){

        $categorie = $this->entityManager->getRepository(Categorie::class)->find($id);
        $categorie->setNom($nom);
        $this->entityManager->persist($categorie);
        $this->entityManager->flush();
        return "modification avec succés !";
        
    }

}