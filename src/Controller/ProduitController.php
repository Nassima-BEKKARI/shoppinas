<?php

namespace App\Controller;

use DateTime;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
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

    public function allProduits(ProduitRepository $repo)
    {
        $produits = $repo->findAll();

        return $this->render('produit/produits.html.twig', [
            'produits'=>$produits
        ]);
    }
}
