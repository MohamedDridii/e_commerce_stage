<?php

namespace App\Controller\Client;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\StockRepository;
use App\Repository\StoreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/client/product', name: 'client.product.')]
final class ClientProductController extends AbstractController
{
    #[IsGranted('ROLE_CLIENT')]
    #[Route('/home', name: 'home')]
    public function index(ProductRepository  $productrepo,CategoryRepository $categoryrepo,Request $request,StoreRepository $storerepo): Response
    {
        //read the category ID frm query parametre(the Query parametre takes any parametre passed in the path section in the view and add it to the url ala aaks route parametre li enti deifnehom kima {id} lezm taamlehom manuellement ama bel $request->query twali tasnaalek query param yetaada fel url b ay esm aaditou enti fel view kima fel cas hedhi category,edheka alehch aadina getInt('category',0))
        $categoryId=$request->request->getInt('category', 0);        
        //read the category ID frm query parametre(the Query parametre takes any parametre passed in the path section in the view and add it to the url ala aaks route parametre li enti deifnehom kima {id} lezm taamlehom manuellement ama bel $request->query twali tasnaalek query param yetaada fel url b ay esm aaditou enti fel view kima fel cas hedhi category,edheka alehch aadina getInt('category',0))
        $storeId=$request->request->getInt('store', 0);        
        //read the category ID frm query parametre(the Query parametre takes any parametre passed in the path section in the view and add it to the url ala aaks route parametre li enti deifnehom kima {id} lezm taamlehom manuellement ama bel $request->query twali tasnaalek query param yetaada fel url b ay esm aaditou enti fel view kima fel cas hedhi category,edheka alehch aadina getInt('category',0))
        $minstock=$request->request->getInt('min_stock', 0);    
        
        $searchQuery=$request->request->get('searchQuery',null);
       
        //find all categories 
        $categories=$categoryrepo->findAll();
        //find all stores
        $stores=$storerepo->findAll();
        $products=$productrepo->findFiltredProducts($categoryId,$storeId,$minstock,$searchQuery);
        return $this->render('client_product/index.html.twig', [
            'products' => $products,
            'categories'=>$categories,
            'stores'=>$stores,
            'selectedCategory'=>$categoryId,//we have sent those id so that the view can put as elected filed when secting the field 
            'selectedStore'=>$storeId,
            'selectedMinStock'=>$minstock,
            'search'=>$searchQuery,
        ]);
    }
    
    #[Route('/show/{id}', name: 'show')]
    public function show(ProductRepository  $productrepo,StockRepository $stockRepo,$id): Response
    {
        $products=$productrepo->find($id);
        $stores=$stockRepo->findStoresByProductIdDQL($id);

        return $this->render('client_product/showProduct.html.twig', [
            'product' => $products,
            'stores'=>$stores
        ]);
    }
}
