<?php

namespace App\Service;

use App\Entity\Produit;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;

class CommerceService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    //Categorie
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

    //Produit
    public function getProduitById($id)
    {
        $produit = $this->entityManager->getRepository(Produit::class)->find($id);
        return $produit;
    }

    public function addNewProduit($nom, $description, $prix, $image, $quantite, $categorie_id){
        $produit = new Produit();
        $categorie = $this->entityManager->getRepository(Categorie::class)->find($categorie_id);
        $produit->setNom($nom)
                ->setDescription($description)
                ->setPrix($prix)
                ->setImage($image)
                ->setQuantite($quantite)
                ->setCategorie($categorie);
        $this->entityManager->persist($produit);
        $this->entityManager->flush();
        return "l'ajout du produit avec succés!";
    }
}