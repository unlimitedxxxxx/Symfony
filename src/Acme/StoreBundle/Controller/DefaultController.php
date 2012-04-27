<?php

namespace Acme\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\StoreBundle\Entity\Category;
use Acme\StoreBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    
   public function indexAction($name)
   {
      return $this->render('AcmeStoreBundle:Default:index.html.twig', array('name' => $name));
   }
    
   public function createAction()
   {
      $product = new Product();
      $product->setName('A Foo Bar');
      $product->setPrice('19.99');
      $product->setDescription('Lorem ipsum dolor');

      $em = $this->getDoctrine()->getEntityManager();
      $em->persist($product);
      $em->flush();

      return new Response('Created product id '.$product->getId());
   }
   
   public function showAction($id)
   {
       $product = new Product();
      $product = $this->getDoctrine()
         ->getRepository('AcmeStoreBundle:Product')
         ->find($id);

      if (!$product) {
         throw $this->createNotFoundException('No product found for id '.$id);
      }

      $category = new Category();
      $category = $product->getCategory();
      
      $cate = $category->getName();
      return new Response('Show-'.$cate);
      
   }

   public function updateAction($id)
   {
      $em = $this->getDoctrine()->getEntityManager();
      $product = $em->getRepository('AcmeStoreBundle:Product')->find($id);

      if (!$product) {
         throw $this->createNotFoundException('No product found for id '.$id);
      }

      $product->setName('New product name!');
      $em->flush();

      return $this->redirect($this->generateUrl('homepage'));
   }
   
   public function createProductAction()
    {
        $category = new Category();
        $category->setName('Main Products');

        $product = new Product();
        $product->setName('Foo');
        $product->setPrice(19.99);
        $product->setDescription('test');
        
        // この商品をカテゴリに関連付ける
        $product->setCategory($category);

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($category);
        $em->persist($product);
        $em->flush();

        return new Response(
            'Created product id: '.$product->getId().' and category id: '.$category->getId()
        );
    }
    
    public function showProductAction($id)
    {
        $category = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:Category')
            ->find($id);

        $products = $category->getProducts();

        // ...
        return new Response('showProduct');

    }
}
