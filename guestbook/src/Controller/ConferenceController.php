<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConferenceController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(Request $req): Response
    {
        $greet ='';
        if($name = $req->query->get('hello')){
            $greet = sprintf('<h1>Hello %s!</h1>', htmlspecialchars($name));
        }
        dd($req);
        
        return new Response(<<<EOF
                    <html>
                        <body><img src="/images/under-construction.gif" /></body>
                    </html>
               EOF);
    }

    #[Route('/conference', name: 'conference')]
    public function conference(Request $req): Response
    {

        $data = '';

        return $this->render('conference/index.html.twig',[
            "data" => $data
        ]);
    }
}
