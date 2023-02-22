<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Conference;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ConferenceController extends AbstractController
{
    

    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    #[Route('/', name: 'homepage')]
    public function index(Request $req, ConferenceRepository $cr): Response
    {
        return $this->render('conference/index.html.twig',[
            "conferences" => $cr->findAll(),
        ]);
    }



    #[Route('/conference/{slug}', name: 'conference')]
    public function conference(Request $req, Conference $conf, CommentRepository $comRepo, #[Autowire('%photo_dir%')] string $photoDir): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);

        $offset = max(0, $req->query->getInt('offset', 0));
        $paginator = $comRepo->getCommentPaginator($conf, $offset);

        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setConference($conf);

            if ($photo = $form['photo']->getData()) {
                $filename = bin2hex(random_bytes(6)).'.'.$photo->guessExtension();
                try {
                    $photo->move($photoDir, $filename);
                } catch (FileException $e) {
                    // unable to upload the photo, give up
                }
                $comment->setPhotoFilename($filename);
            }

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->redirectToRoute('conference', ['slug' => $conf->getSlug()]);
        }

       

        return $this->render('conference/show.html.twig',[
            "comment_form" => $form->createView(),
            "conference" => $conf,
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE)
        ]);
    }
}
