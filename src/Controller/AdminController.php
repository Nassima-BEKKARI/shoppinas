<?php

namespace App\Controller;

use DateTime;
use App\Entity\Produit;
use App\Entity\Categorie;
use App\Form\ProduitType;
use App\Form\CategorieType;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminController extends AbstractController
{
    public function add(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger)
    {
        $produit = new Produit;

        $form = $this->createform(ProduitType::class, $produit);
        $form->handlerequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $file = $form->get('photoForm')->getData();
            $fileName = $slugger->slug($produit->getTitre()) . uniqid() . '.' . $file->guessExtension();

            try{
                $file->move($this->getParameter('photos_produits'), $fileName);
            } catch (FileException $error){

            }
            $produit->setPhoto($fileName);
            $produit->setDateEnregistrement(new DateTime('now'));
            
            $manager = $doctrine->getManager();
            $manager->persist($produit);
            $manager->flush();

            return $this->redirectToRoute('app_home');
        }
        return $this->render('admin/formulaire.html.twig',[
            'formProduit'=> $form->createView()]);
    }

    public function update(ManagerRegistry $doctrine, ProduitRepository $repo, $id, Request $request, SluggerInterface $slugger)
    {
        $produit = $repo->find($id);
        $form = $this->createform(ProduitType::class, $produit);
        $form->handlerequest($request);
        $image = $produit->getPhoto();
        if($form->isSubmitted() && $form->isValid())
        {
            if($form->get('photoForm')->getData() )
            {
                $imageFile = $form->get('photoForm')->getData();
    
                $fileName = $slugger->slug($produit->getTitre()) . uniqid() . '.' . $imageFile->guessExtension();
    
                try{
                    $imageFile->move($this->getParameter('photos_produits'), $fileName);
                }catch(FileException $e){
                }
                $produit->setPhoto($fileName);
            }
        $manager = $doctrine->getManager();
        $manager->persist($produit);
        $manager->flush();

        return $this->redirectToRoute('admin_app_all');
        }
        return $this->render('admin/formulaire.html.twig', [
            'formProduit' => $form->createView()
        ]);
    }

    public function allProduits(ProduitRepository $repo)
    {
        $produits = $repo->findAll();

        return $this->render('admin/produits.html.twig', [
            'produits'=>$produits
        ]);
    }


    public function delete(ProduitRepository $repo, $id, )
    {
        $produit = $repo->find($id);
        $repo->remove($produit, 1);

        return $this->redirectToRoute("admin/produits.html.twig");
    }

    public function addCategorie(Request $request, CategorieRepository $repo)
    {
        $categorie = new Categorie();
        $form = $this->createform(CategorieType::class, $categorie);
        $form->handlerequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $repo->add($categorie,1);
            return $this->redirectToRoute("app_home");
        }
            return $this->render("admin/formulaireCategorie.html.twig",[
            "formCategorie"=> $form->createView()]);
        
    }
}
