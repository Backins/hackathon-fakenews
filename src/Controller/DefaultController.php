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
use App\Service\NewscanService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 * @package App\Controller
 * @Route(name="app_default_")
 */
class DefaultController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(path="/", name="index")
     */
    public function index()
    {
        return $this->render('front/index.html.twig');
    }

    /**
     * @param $article
     * @Route(path="/show", name="show", methods={"POST"})
     */
    public function show(Request $request, ReviewRepository $reviewRepository)
    {
        $link = $request->request->get('linkSearch');
        if (filter_var($link, FILTER_VALIDATE_URL)) {
            $Newscan = new NewscanService();
            $response = $Newscan->getArticle($link);
            $targetArticle = $response->objects[0];

            $topics = $Newscan->getTopicsArticle($targetArticle->tags);
            $user = $this->getUser();
            $findVote = false;
            if($user){
                $findVote = $reviewRepository->findBy([
                    'urlArticle' => $link,
                    'userReview' => $this->getUser(),
                ]);
                if(empty($findVote)){
                    $findVote = false;
                }
            }


            return $this->render('front/show.html.twig', [
                'topics' => $topics,
                'article' => $targetArticle,
                'user' => $user,
                'findVote' => $findVote
            ]);
        }
        return $this->render('front/show.html.twig', [
            'errors' => [
                'Votre saisie ne correspond pas à un lien',
            ],
            'link' => $link,
        ]);
    }
}