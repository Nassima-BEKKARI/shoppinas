<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
   
    public function home(ProduitRepository $repo): Response
    {
        $produits = $repo->findBy([], ["dateEnregistrement"=>"DESC"],5);
        return $this->render('home/index.html.twig',[
            'produits'=>$produits
        ]);
    }
}
