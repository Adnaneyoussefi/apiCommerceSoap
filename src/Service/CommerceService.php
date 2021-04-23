<?php

namespace App\Service;

use App\Entity\Message;
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
        if(!isset($categorie) && empty($categorie)){
            $message=new Message("T-111","data base problem");
        }
        return $categorie;
    }

    public function getListCategories()
    {
        $categories = $this->entityManager->getRepository(Categorie::class)->findAll();
        foreach($categories as $c) {
            $c->setProduits($c->getProduits()->toArray());
        }
        return $categories;
    }

    public function addNewCategorie($nom){
        try{
        if(!isset($nom) || empty($nom)){
            $message = new Message("F-111","le nom de la categorie est non valide");
        }
        else{
        $categorie = new Categorie();
        $categorie->setNom($nom);
        $this->entityManager->persist($categorie);
        $this->entityManager->flush();
        $message = new Message(1,"OK");
        }
            }
        catch(\Exception $e){
            $message = new Message("T-111","Erreur dans la base de donnée");
        }
        return $message;
    }

    public function updateCategorie($id, $nom){
    try{
        if(!isset($id) || empty($id)){
            $message = new Message("F-122","l'id de la categorie est non valide");
        }
        if(!isset($nom) || empty($nom)){
            $message = new Message("F-123","le nom de la categorie est non valide");
        }
        else{
            $categorie = $this->entityManager->getRepository(Categorie::class)->find($id);
            $categorie->setNom($nom);
            $this->entityManager->persist($categorie);
            $this->entityManager->flush();
            $message = new Message(1,"OK");
        }
    }
        catch(\Exception $e){
            $message = new Message("T-223","Erreur dans la base de donnée");
        }
        return $message;
    }

    public function deleteCategorie($id){
        $categorie = $this->entityManager->getRepository(Categorie::class)->find($id);
        if(isset($categorie) && !empty($categorie)){
            $this->entityManager->remove($categorie);
            $this->entityManager->flush();
            $message = new Message(1,"OK");
        }
        else{
            $message=new Message("T-111","data base problem");
        return $message;
    }
}

    //Produit
    public function getProduitById($id)
    {
        $produit = $this->entityManager->getRepository(Produit::class)->find($id);
        return $produit;
    }

    public function getListProduits()
    {
        $produits = $this->entityManager->getRepository(Produit::class)->findAll();
        return $produits;
    }

    public function addNewProduit($nom, $description, $prix, $image, $quantite, $categorie_id){
    try {
        $categorie = $this->entityManager->getRepository(Categorie::class)->find($categorie_id);
        if($categorie == null){
        $message = new Message("F-110","la categorie n'existe pas");
        }
        else if(!isset($nom) || empty($nom)){
            $message = new Message("F-110","le nom est non valide");
        }
        else if(!isset($description) || empty($description)){
            $message = new Message("F-111","la description est non valide");
        }
        else if(!isset($prix) || empty($prix)){
            $message = new Message("F-112","le prix est non valide");
        }
        else if(!isset($image) || empty($image)){
            $message = new Message("F-113","l'image est non valide");
        }
        else if(!isset($quantite) || empty($quantite)){
            $message = new Message("F-114","le quantite est non valide");
        }
        else if(!isset($categorie_id) || empty($categorie_id)){
            $message = new Message("F-115","le categorie_id est non valide");
        }
        else {
        $produit = new Produit();
        $produit->setNom($nom)
                ->setDescription($description)
                ->setPrix($prix)
                ->setImage($image)
                ->setQuantite($quantite)
                ->setCategorie($categorie);
        $this->entityManager->persist($produit);
        $this->entityManager->flush();
        $message = new Message(1,"OK");
        }
    }
    catch(\Exception $e){
        $message = new Message("T-222","Erreur dans la base de donnée");
        }
        return $message;
    }

    public function updateProduit($id, $nom, $description, $prix, $image, $quantite, $categorie_id) {
        try{
            $categorie = $this->entityManager->getRepository(Categorie::class)->find($categorie_id);
            $produit = $this->entityManager->getRepository(Produit::class)->find($id);
            if($categorie == null){
                $message = new Message("F-110","la categorie n'existe pas");
            }
            else if($produit == null){
                $message = new Message("F-110","le produit n'existe pas");
            }
            else if(!isset($id) || empty($id)){
                $message = new Message("F-110","l'id est non valide");
            }
            else if(!isset($nom) || empty($nom)){
                $message = new Message("F-116","le nom est non valide");
            }
            else if(!isset($description) || empty($description)){
                $message = new Message("F-117","la description est non valide");
            }
            else if(!isset($prix) || empty($prix)){
                $message = new Message("F-118","le prix est non valide");
            }
            else if(!isset($image) || empty($image)){
                $message = new Message("F-119","l'image est non valide");
            }
            else if(!isset($quantite) || empty($quantite)){
                $message = new Message("F-120","le quantite est non valide");
            }
            else if(!isset($categorie_id) || empty($categorie_id)){
                $message = new Message("F-121","le categorie_id est non valide");
            }
            else {
                $produit->setNom($nom)
                        ->setDescription($description)
                        ->setPrix($prix)
                        ->setImage($image)
                        ->setQuantite($quantite)
                        ->setCategorie($categorie);
                $this->entityManager->persist($produit);
                $this->entityManager->flush();
                $message = new Message(1,"OK");
            }
        }
        catch(\Exception $e){
        $message = new Message("T-221","Erreur dans la base de donnée");

        }
        return $message;
    }

    public function deleteProduit($id) {
        $produit = $this->entityManager->getRepository(Produit::class)->find($id);
        if($produit == null){
            $message = new Message("F-110","le produit n'existe pas");
        }
        else if(isset($produit) && !empty($produit)){
            $this->entityManager->remove($produit);
            $this->entityManager->flush();
            $message = new Message(1,"OK");
        }
        else{
            $message=new Message("T-111","database problem");
        }
        return $message;
    }
}