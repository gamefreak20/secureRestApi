<?php
namespace App\Controller;
use App\Form\MovieType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Movie;

/**
 * Movie controller.
 * @Route("/api", name="api_")
 */
class MovieController extends FOSRestController
{
    /**
     * Lists all Movies.
     * @Rest\Get("/movies")
     *
     * @return Response
     */
    public function getMovieAction()
    {
        $repository = $this->getDoctrine()->getRepository(Movie::class);
        $movies = $repository->findall();
        return $this->handleView($this->view($movies));
    }

    /**
     * Create Movie.
     * @Rest\Post("/movie")
     *
     * @param Request $request
     * @return Request
     */
    public function postMovieAction(Request $request)
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $data = [
            'name' => $request->get('name'),
            'description' => $request->get('description'),
        ];
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();
            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
        }
        return $this->handleView($this->view($form->getErrors()));

        /*
        $entityManager = $this->getDoctrine()->getManager();
        $movie = new Movie();
        $movie->setName($request->get('name'));
        $movie->setDescription($request->get('description'));
        $entityManager->persist($movie);
        $entityManager->flush();
        return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
        */
    }
}