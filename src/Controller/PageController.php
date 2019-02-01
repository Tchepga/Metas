<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class PageController extends AbstractController
{
    /**
    * @Route("/",name="accueil")
    */
    public function index()
    {
        $pages = $this->getDoctrine()->getRepository(\App\Entity\Page::class)->findTopFive();
        $firstEvent = $this->getDoctrine()->getRepository(Event::class)->findAll();

        $goodPlan= array();
        if(!empty($firstEvent))
            $firstEvent = $firstEvent[0];

        foreach ($pages as $key)
        {
            // check if the page is a good plan
            if($key->getCategory()->getLibelle()== "goodplan")
            {
                array_push($goodPlan,$key);

            }
        }

        if($goodPlan)
            $goodPlan = $goodPlan[0];
       
        return $this->render('pages/home.html.twig',['event' => $firstEvent, 'goodplans' => $goodPlan]);
    }
    
     /**
    * @Route("/contact ")
    */
    public function contact()
    {
        // get information about the administration team of metas 
        // note: president's email correspond at the metas adress
        $president = $this->getDoctrine()->getManager()->getRepository(\App\Entity\User::class)->findOneBy(['type' => 'president']); 
        $secretary = $this->getDoctrine()->getManager()->getRepository(\App\Entity\User::class)->findOneBy(['type' => 'secretary']); 
        $treasury = $this->getDoctrine()->getManager()->getRepository(\App\Entity\User::class)->findOneBy(['type' => 'treasury']); 
        $communication = $this->getDoctrine()->getManager()->getRepository(\App\Entity\User::class)->findOneBy(['type' => 'communication']); 
        $sport = $this->getDoctrine()->getManager()->getRepository(\App\Entity\User::class)->findOneBy(['type' => 'activity']); 
        $event = $this->getDoctrine()->getManager()->getRepository(\App\Entity\User::class)->findOneBy(['type' => 'sport']); 
      
        
        return $this->render('pages/contact.html.twig',
                                    ['president' => $president, 
                                     'secretary' => $secretary,
                                     'treasury' => $treasury,
                                     'com' => $communication,
                                     'sport' => $sport,
                                     'event' => $event
                                    ]);
    }

    /**
    * @Route("/add_contact ")
    */
    public function addContact( Request $request)
    {
        $newMail = $request->query->get('email');
        
        if($newMail){

            $entityManager = $this->getDoctrine()->getManager();

            $president = $entityManager->getRepository(\App\Entity\User::class)->findOneBy(['type' => 'president']);
            $president->setEMail($newMail);
            $entityManager->flush();
  
            $this->addFlash(
                'notice',
                'Contact Metas modifié'
            );
             
           
        }
         
     
       
        return new Response($newMail);
    }
    
     /**
    * @Route("/actu ", name="actu")
    */
    public function news()
    {
        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();
        return $this->render('pages/actu.html.twig',['events' => $events]);
    }
    
     /**
    * @Route("/partenaire",name="partenaire")
    */
    public function partner()
    {
       
        return $this->render('pages/partenaire.html.twig');
    }
    
    
     /**
    * @Route("/authentification ")
    */
    public function authen()
    {
       
        return $this->render('pages/authentification.html.twig');
    }
} 