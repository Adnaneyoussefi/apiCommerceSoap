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
        if(!isset($nom) && empty($nom)){
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
        if(!isset($id) && empty($id)){
            $message = new Message("F-122","l'id de la categorie est non valide");
        }
        if(!isset($nom) && empty($nom)){
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
        try{
            $this->entityManager->remove($categorie);
            $this->entityManager->flush();
            $message = new Message(1,"OK");
        }
        catch(\Exception $e){
            $message = new Message(2,"KO");
        }
        return $message;
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
        if(!isset($nom) && empty($nom)){
            $message = new Message("F-110","le nom est non valide");
        }
        else if(!isset($description) && empty($description)){
            $message = new Message("F-111","la description est non valide");
        }
        else if(!isset($prix) && empty($prix)){
            $message = new Message("F-112","le prix est non valide");
        }
        else if(!isset($image) && empty($image)){
            $message = new Message("F-113","l'image est non valide");
        }
        else if(!isset($quantite) && empty($quantite)){
            $message = new Message("F-114","le quantite est non valide");
        }
        else if(!isset($categorie_id) && empty($categorie_id)){
            $message = new Message("F-115","le categorie_id est non valide");
        }
        else {
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
            if(!isset($id) && empty($id)){
                $message = new Message("F-110","l'id est non valide");
            }
            else if(!isset($nom) && empty($nom)){
                $message = new Message("F-116","le nom est non valide");
            }
            else if(!isset($description) && empty($description)){
                $message = new Message("F-117","la description est non valide");
            }
            else if(!isset($prix) && empty($prix)){
                $message = new Message("F-118","le prix est non valide");
            }
            else if(!isset($image) && empty($image)){
                $message = new Message("F-119","l'image est non valide");
            }
            else if(!isset($quantite) && empty($quantite)){
                $message = new Message("F-120","le quantite est non valide");
            }
            else if(!isset($categorie_id) && empty($categorie_id)){
                $message = new Message("F-121","le categorie_id est non valide");
            }
            else {
                $produit = $this->entityManager->getRepository(Produit::class)->find($id);
                $categorie = $this->entityManager->getRepository(Categorie::class)->find($categorie_id);
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
        try{
        $produit = $this->entityManager->getRepository(Produit::class)->find($id);
        $this->entityManager->remove($produit);
        $this->entityManager->flush();
        $message = new Message(1,"OK");
        }
        catch(\Exception $e){
            $message = new Message(2,"KO");
        }
        return $message;
    }
}