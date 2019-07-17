<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class SiteController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Category::class);
        $cat = $repo->createQueryBuilder("category")
            ->select('category.id, category.category_name');
        $categories =$cat->getQuery()->getResult();

        $category = []; // for name category
        $product = []; // for name product
        for ($i = 0; $i < count($categories); $i++){
            $id = $categories[$i]["id"];
            $prod = $this->getDoctrine()->getRepository(Product::class);
            $q = $prod->createQueryBuilder("c")
                ->select('c.name')
                ->innerJoin('c.category', 'p')
                ->andWhere('p.id = :id')
                ->setParameter('id', $id)
                ->setMaxResults(1);
            $products =$q->getQuery()->getResult();

            if ($products) {
                array_push($product, $products[0]["name"]);
            }
            else {
                array_push($product, 'Пусто');
            }
            array_push($category, $categories[$i]["category_name"]);
//            echo '<pre>'. print_r($netflix[0],true) .'</pre>';
        };

        $results = array_map(null, $category, $product);
        return $this->render('site/index.html.twig', [
            'results' => $results,
        ]);
    }

    /**
     * @Route("/{categoryName}", name="category_name")
     */

    public function categoryId($categoryName) {
        $repo = $this->getDoctrine()->getRepository(Product::class);
        $q = $repo->createQueryBuilder("c")
            ->select('c.name')
            ->innerJoin('c.category', 'p')
//            ->addSelect('c')
            ->andWhere('p.category_name = :category_name')
            ->setParameter('category_name', $categoryName);
        $qb =$q->getQuery()->getResult();

        return $this->render('site/category.html.twig', [
            'qb' => $qb,
        ]);
    }

    /**
     * @Route("/create", name="create_product")
     */
    public function createProduct()
    {

        $entityManager = $this->getDoctrine()->getManager();
        $category = new Category();
        $category->setCategoryName('Grapes1');
        $product = new Product();
        $product->setName('Grape12');

        $entityManager->persist($category);
        $entityManager->persist($product);
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
    }
}