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
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(path="/comment-ca-fonctionne", name="howappwork")
     */
    public function HowAppWork()
    {
        return $this->render('front/howAppWork.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(path="/qui-somme-nous", name="whoareus")
     */
    public function WhoWeAre()
    {
        return $this->render('front/whoWeAre.html.twig');
    }

    /**
     * @param $article
     * @Route(path="/show", name="show", methods={"POST"})
     */

    public function show(Request $request, ReviewRepository $reviewRepository, NewscanService $newscanService)
    {
        $link = $request->request->get('linkSearch');
        if (filter_var($link, FILTER_VALIDATE_URL)) {
            $source = parse_url($link);
            $source = $source["scheme"].'://'.$source["host"];
            $response = $newscanService->getArticle($link);
            if(isset($response->errorCode)){
                return $this->render('front/show.html.twig', [
                    'errors' => ['Votre lien semble incorrecte'],
                    'link' => $link,
                    'score' => ['textColor' =>0, 'backgroundColor' => 0 ,'value' => 0],
                ]);
            } else {
                $targetArticle = $response->objects[0];
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

                $em = $this->getDoctrine()->getManager();
                $countReview = $em->getRepository(Review::class)->getCount($link);

                $score = $newscanService->calculArticleConfidenceLevel($targetArticle);

                $topics = $newscanService->getTopicsArticle($targetArticle->tags);

                return $this->render('front/show.html.twig', [
                    'topics' => $topics,
                    'article' => $targetArticle,
                    'user' => $user,
                    'findVote' => $findVote,
                    'score' => $score,
                    'link' => $link,
                    'source' => $source,
                    'nbVote' => $countReview[1]
                ]);
            }

        }
        return $this->render('front/show.html.twig', [
            'errors' => [
                'Votre saisie ne correspond pas à un lien',
            ],
            'link' => $link,
            'score' => ['textColor' =>0, 'backgroundColor' => 0 ,'value' => 0],
        ]);
    }
}
