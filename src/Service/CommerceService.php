<?php

namespace App\Service;

use App\Entity\Message;
use App\Entity\Produit;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

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
        if ($categorie != null) {
            return $categorie;
        }
    }

    public function getListCategories()
    {
        try {
            $categories = $this->entityManager->getRepository(Categorie::class)->findAll();
            if ($categories != null) {
                foreach ($categories as $c) {
                    $c->setProduits($c->getProduits()->toArray());
                }
            }
            $message = new Message("201", "OK");
            return $categories;
        } catch(ConnectionException $e) {
            
        }
        
    }

    public function addNewCategorie($nom)
    {
        try {
            if (!isset($nom) || empty($nom)) {
                $message = new Message("F-301", "le nom de la categorie est non valide");
            } else {
                $categorie = new Categorie();
                $categorie->setNom($nom);
                $this->entityManager->persist($categorie);
                $this->entityManager->flush();
                $message = new Message("201", "OK");
            }
        } catch (ConnectionException $e) {
            $message = new Message("T-501", "Erreur de la connexion à la base de données");
        }
        catch(\Exception $e) {
            $message = new Message("T-500", $e->getMessage());
        }
        return $message;
    }

    public function updateCategorie($id, $nom)
    {
        try {
            $categorie = $this->entityManager->getRepository(Categorie::class)->find($id);
            if (!isset($id) || empty($id)) {
                $message = new Message("F-300", "l'id de la categorie est non valide");
            }
            if (!isset($nom) || empty($nom)) {
                $message = new Message("F-301", "le nom de la categorie est non valide");
            } else if ($categorie == null) {
                $message = new Message("T-302", "categorie n'existe pas");
            } else {
                $categorie->setNom($nom);
                $this->entityManager->persist($categorie);
                $this->entityManager->flush();
                $message = new Message("200", "OK");
            }
        } catch (ConnectionException $e) {
            $message = new Message("T-501", "Erreur de la connexion à la base de données");
        }
        catch(\Exception $e) {
            $message = new Message("T-500", $e->getMessage());
        }
        return $message;
    }

    public function deleteCategorie($id)
    {
        try {
            $categorie = $this->entityManager->getRepository(Categorie::class)->find($id);
            if (empty($id)) {
                $message = new Message("F-300", "id non valide");
            } else if (is_numeric($id) != 1) {
                $message = new Message("F-303", "id doit etre un nombre");
            } else if ($categorie == null) {
                $message = new Message("T-302", "categorie n'existe pas");
            } else {
                $this->entityManager->remove($categorie);
                $this->entityManager->flush();
                $message = new Message("204", "OK");
            }
        } catch (ConnectionException $e) {
            $message = new Message("T-501", "Erreur de la connexion à la base de données");
        }
        catch(ForeignKeyConstraintViolationException $e) {
            $message = new Message("T-502", "Vous ne pouvez pas supprimé la catégorie parcequ'elle existe 
            comme clé étrangère dans une autre entité");
        }
        catch(\Exception $e) {
            $message = new Message("T-500", $e->getMessage());
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
        if ($produits != null) {
            return $produits;
        }
    }

    public function addNewProduit($nom, $description, $prix, $image, $quantite, $categorie_id)
    {
        try {
            $categorie = $this->entityManager->getRepository(Categorie::class)->find($categorie_id);
            if (!isset($nom) || empty($nom)) {
                $message = new Message("F-401", "le nom est non valide");
            } else if (!isset($description) || empty($description)) {
                $message = new Message("F-404", "la description est non valide");
            } else if (is_string($prix)) {
                $message = new Message("F-405", "le prix est non valide");
            } else if (is_string($quantite)) {
                $message = new Message("F-406", "le quantite est non valide");
            } else if (!isset($categorie_id) || empty($categorie_id)) {
                $message = new Message("F-407", "le categorie_id est non valide");
            } else if ($categorie == null) {
                $message = new Message("T-302", "la categorie n'existe pas");
            } else {
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
                $message = new Message("201", "OK");
            }
        } catch (ConnectionException $e) {
            $message = new Message("T-501", "Erreur de la connexion à la base de données");
        }
        catch (\Exception $e) {
            $message = new Message("T-500", $e->getMessage());
        }
        return $message;
    }

    public function updateProduit($id, $nom, $description, $prix, $image, $quantite, $categorie_id)
    {
        try {
            $categorie = $this->entityManager->getRepository(Categorie::class)->find($categorie_id);
            $produit = $this->entityManager->getRepository(Produit::class)->find($id);
            if (!isset($id) || empty($id)) {
                $message = new Message("F-400", "l'id est non valide");
            } else if (is_numeric($id) != 1) {
                $message = new Message("T-403", "id doit etre un nombre");
            } else if (!isset($nom) || empty($nom)) {
                $message = new Message("F-401", "le nom est non valide");
            } else if (!isset($description) || empty($description)) {
                $message = new Message("F-404", "la description est non valide");
            } else if (!isset($prix) || empty($prix)) {
                $message = new Message("F-405", "le prix est non valide");
            } else if (is_numeric($prix) != 1) {
                $message = new Message("T-410", "le prix doit etre un nombre");
            } else if (!isset($quantite) || empty($quantite)) {
                $message = new Message("F-406", "le quantite est non valide");
            } else if (!isset($categorie_id) || empty($categorie_id)) {
                $message = new Message("F-407", "le categorie_id est non valide");
            } else if ($produit == null) {
                $message = new Message("T-402", "le produit n'existe pas");
            } else if ($categorie == null) {
                $message = new Message("T-302", "la categorie n'existe pas");
            } else {
                $produit->setNom($nom)
                    ->setDescription($description)
                    ->setPrix($prix)
                    ->setImage($image)
                    ->setQuantite($quantite)
                    ->setCategorie($categorie);
                $this->entityManager->persist($produit);
                $this->entityManager->flush();
                $message = new Message("200", "OK");
            }
        } catch (ConnectionException $e) {
            $message = new Message("T-501", "Erreur de la connexion à la base de données");
        }
        catch (\Exception $e) {
            $message = new Message("T-500", $e->getMessage());
        }
        return $message;
    }

    public function deleteProduit($id)
    {
        try {
            $produit = $this->entityManager->getRepository(Produit::class)->find($id);
            if (empty($id)) {
                $message = new Message("F-400", "id non valide");
            } else if (is_numeric($id) != 1) {
                $message = new Message("T-403", "id doit etre un nombre");
            } else if ($produit == null) {
                $message = new Message("T-402", "produit n'existe pas");
            } else {
                $this->entityManager->remove($produit);
                $this->entityManager->flush();
                $message = new Message("204", "OK");
            }
        } catch (ConnectionException $e) {
            $message = new Message("T-501", "Erreur de la connexion à la base de données");
        }
        catch(ForeignKeyConstraintViolationException $e) {
            $message = new Message("T-502", "Vous ne pouvez pas supprimé le produit parcequ'il existe 
            dans une autre entité");
        }
        catch (\Exception $e) {
            $message = new Message("T-500", $e->getMessage());
        }
        return $message;
    }
}