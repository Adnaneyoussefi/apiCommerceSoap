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
    /**
     * @param int $id
     * @return Categorie
     */
    public function getCategorieById(int $id): Categorie
    {
        $categorie = $this->entityManager->getRepository(Categorie::class)->find($id);
        if ($categorie != null) {
            return $categorie;
        }
    }
    /**
     * @return array
     */
    public function getListCategories(): array
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
    /**
     * @param string $nom
     * @return Message
     */
    public function addNewCategorie(string $nom): Message
    {
        try {
            if (!isset($nom) || empty($nom)) {
                $message = new Message("F-204", "le nom de la categorie est non valide");
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
    /**
     * updateCategorie
     *
     * @param  int $id
     * @param  string $nom
     * @return Message
     */
    public function updateCategorie(int $id, string $nom): Message
    {
        try {
            $categorie = $this->entityManager->getRepository(Categorie::class)->find($id);
            if (!isset($id) || empty($id)) {
                $message = new Message("F-204", "l'id de la categorie est non valide");
            }
            if (!isset($nom) || empty($nom)) {
                $message = new Message("F-204", "le nom de la categorie est non valide");
            } else if ($categorie == null) {
                $message = new Message("T-204", "categorie n'existe pas");
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
    /**
     * @param int $id
     * @return Message
     */
    public function deleteCategorie(int $id): Message
    {
        try {
            $categorie = $this->entityManager->getRepository(Categorie::class)->find($id);
            if (empty($id)) {
                $message = new Message("F-204", "id non valide");
            } else if (is_numeric($id) != 1) {
                $message = new Message("F-204", "id doit etre un nombre");
            } else if ($categorie == null) {
                $message = new Message("F-204", "categorie n'existe pas");
            } else {
                $this->entityManager->remove($categorie);
                $this->entityManager->flush();
                $message = new Message("200", "OK");
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
    /**
     * @param int $id
     * @return Produit
     */
    public function getProduitById(int $id): Produit
    {
        $produit = $this->entityManager->getRepository(Produit::class)->find($id);
        return $produit;
    }
    /**
     * @return array
     */
    public function getListProduits(): array
    {
        $produits = $this->entityManager->getRepository(Produit::class)->findAll();
        if ($produits != null) {
            return $produits;
        }

    }
    /**
     * @param string $nom
     * @param string $description
     * @param float $prix
     * @param string $image
     * @param int $quantite
     * @param int $categorie_id
     * @return Message
     */
    public function addNewProduit(string $nom, string $description, float $prix, string $image, int $quantite, int $categorie_id): Message
    {
        try {
            $categorie = $this->entityManager->getRepository(Categorie::class)->find($categorie_id);
            if (!isset($nom) || empty($nom)) {
                $message = new Message("F-204", "le nom est non valide");
            } else if (!isset($description) || empty($description)) {
                $message = new Message("F-204", "la description est non valide");
            } else if (is_string($prix)) {
                $message = new Message("F-204", "le prix est non valide");
            } else if (is_string($quantite)) {
                $message = new Message("F-204", "le quantite est non valide");
            } else if (!isset($categorie_id) || empty($categorie_id)) {
                $message = new Message("F-204", "le categorie_id est non valide");
            } else if ($categorie == null) {
                $message = new Message("F-204", "la categorie n'existe pas");
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
    /**
     * @param int $id
     * @param string $nom
     * @param string $description
     * @param float $prix
     * @param string $image
     * @param int $quantite
     * @param int $categorie_id
     * @return Message
     */
    public function updateProduit(int $id, string $nom, string $description, float $prix, string $image, int $quantite, int $categorie_id): Message
    {
        try {
            $categorie = $this->entityManager->getRepository(Categorie::class)->find($categorie_id);
            $produit = $this->entityManager->getRepository(Produit::class)->find($id);
            if (!isset($id) || empty($id)) {
                $message = new Message("F-204", "l'id est non valide");
            } else if (is_numeric($id) != 1) {
                $message = new Message("T-204", "id doit etre un nombre");
            } else if (!isset($nom) || empty($nom)) {
                $message = new Message("F-204", "le nom est non valide");
            } else if (!isset($description) || empty($description)) {
                $message = new Message("F-204", "la description est non valide");
            } else if (!isset($prix) || empty($prix)) {
                $message = new Message("F-204", "le prix est non valide");
            } else if (is_numeric($prix) != 1) {
                $message = new Message("T-204", "le prix doit etre un nombre");
            } else if (!isset($quantite) || empty($quantite)) {
                $message = new Message("F-204", "le quantite est non valide");
            } else if (!isset($categorie_id) || empty($categorie_id)) {
                $message = new Message("F-204", "le categorie_id est non valide");
            } else if ($produit == null) {
                $message = new Message("F-204", "le produit n'existe pas");
            } else if ($categorie == null) {
                $message = new Message("F-204", "la categorie n'existe pas");
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
    /**
     * @param int $id
     * @return Message
     */
    public function deleteProduit(int $id): Message
    {
        try {
            $produit = $this->entityManager->getRepository(Produit::class)->find($id);
            if (empty($id)) {
                $message = new Message("T-204", "id non valide");
            } else if (is_numeric($id) != 1) {
                $message = new Message("T-204", "id doit etre un nombre");
            } else if ($produit == null) {
                $message = new Message("T-204", "produit n'existe pas");
            } else {
                $this->entityManager->remove($produit);
                $this->entityManager->flush();
                $message = new Message("200", "OK");
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