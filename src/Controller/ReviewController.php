<?php
/**
 * Created by PhpStorm.
 * User: backin
 * Date: 18/03/2019
 * Time: 23:02
 */

namespace App\Controller;


use App\Entity\Review;
use App\Repository\ReviewRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package App\Controller
 * @Route(path="/review", name="app_review_")
 */
class ReviewController extends AbstractController
{
    /**
     * @param Request $request
     * @IsGranted("ROLE_USER")
     * @Route(path="/add", name="add", methods={"POST"})
     */
    public function add(Request $request, ReviewRepository $reviewRepository)
    {
        $Review = new Review();
        $link = $request->request->get("urlArticle");
        if (filter_var($link, FILTER_VALIDATE_URL)) {
            if($this->getUser()){
                $user = $this->getUser();
                $em = $this->getDoctrine()->getManager();
                $findVote = $em->getRepository(Review::class)->checkVoteUser($link, $user);
                if(!$findVote){
                    $Review->setUrlArticle($link);
                    $Review->setUserReview($this->getUser());
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($Review);
                    $entityManager->flush();
                    $response = new Response(json_encode(array('status' => "ok", 'data' => $link)));
                    $response->headers->set('Content-Type', 'application/json');

                    return $response;
                }
            }
        }
        $response = new Response(json_encode(array('status' => "erreur")));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}