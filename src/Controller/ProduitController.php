<?php

namespace App\Controller;

use DateTime;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProduitController extends AbstractController
{
  public function select($id, ProduitRepository $repo)
    {
        $produit = $repo->find($id);

        return $this->render('produit/produit.html.twig', [
            'produit'=>$produit
        ]);
    }

    public function allProduits(ProduitRepository $repo, CategorieRepository $repoCat)
    {

        $produits = $repo->findAll();
        $categories = $repoCat->findAll();
        
        return $this->render('produit/produits.html.twig', [
            'produits'=>$produits,
            'categories'=>$categories
        ]);
    }

    public function categorieProduits($id, CategorieRepository $repo)
    {
        //On récupère la catégorie sur laquelle on a cliqué pour accéder aux produits liés
        $categorie = $repo->find($id);
        $produits = $categorie->getProduits();
        //On récupère toutes les catégories pour les afficher dans la liste de la page
        $categories = $repo->findAll();

        return $this->render('produit/produits.html.twig',[
            'produits' => $produits,
            'categories' => $categories
        ]);
    }
}
