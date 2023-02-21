<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConferenceController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(Request $req, ConferenceRepository $cr): Response
    {
        return $this->render('conference/index.html.twig',[
            "conferences" => $cr->findAll(),
        ]);
    }



    #[Route('/conference/{id}', name: 'conference')]
    public function conference(Request $req, Conference $conf, CommentRepository $comRepo, ConferenceRepository $cr): Response
    {
        $offset = max(0, $req->query->getInt('offset', 0));
        $paginator = $comRepo->getCommentPaginator($conf, $offset);

       

        return $this->render('conference/show.html.twig',[
            
            "conference" => $conf,
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE)
        ]);
    }
}
