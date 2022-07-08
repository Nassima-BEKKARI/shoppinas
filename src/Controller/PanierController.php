<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    public function show(SessionInterface $session, ProduitRepository $repo): Response
    {
        $panier = $session->get("panier", []);

        $dataPanier = [];
        $total =0;
 
    // - pour chaque ligne de mon tableau panier dans la session, je recupere le produit qui correspond à l'id qui est en clé et la quantité en valeur.
    // - dans le tableau dataPanier je rajoute à chaque tour de boucle un nouveau tableau qui contient une clé "produit" avec comme valeur le produit récuperé, et une autre "quantite" qui contint la quantite du produit en question
    // - puis à chaque tour de boucle je calcule le prix total du produit (prix du produit * quantité) et je l'ajoute à ma variable $total 
    
        foreach($panier as $id => $quantite){
            $produit = $repo->find($id);
            $dataPanier[] = 
            [
                "produit" => $produit,
                "quantite" => $quantite
            ];

            $total += $produit->getPrix() * $quantite;
        }
        return $this->render('panier/index.html.twig', [
            'dataPanier' => $dataPanier,
            'total' => $total
        ]);
    }

    public function panierAdd($id, SessionInterface $session)
    {
       $panier = $session->get('panier',[]);

       // On vérifie si l'id existe déjà, dans ce cas on incrémente sinon on le crée
       if(empty($panier[$id]))
       {
        $panier[$id] = 1;
       } else {
        $panier[$id]++;
       }
        $session->set('panier', $panier);
        return $this->redirectToRoute("app_panier");

    }

    public function panierDelete($id, SessionInterface $session)
    {
        $panier = $session->get("panier", []);
        if(!empty($panier[$id]))
        {
            unset($panier[$id]);
        } else {
            $this->addFlash("error","Le produit que vous essayez de retirer du panier n'existe pas");

            return $this->redirectToRoute("app_panier");
        }

        $session->set("panier", $panier);
        $this->addFlash("success","Le produit a bien été retiré du panier");

        return $this->redirectToRoute("app_panier");
    }
}
