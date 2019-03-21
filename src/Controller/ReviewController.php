<?php
/**
 * Created by PhpStorm.
 * User: backin
 * Date: 18/03/2019
 * Time: 23:02
 */

namespace App\Controller;


use App\Entity\Review;
use App\Service\NewscanService;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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
    public function add(Request $request)
    {
        $Review = new Review();
        $link = $request->request->get("urlArticle");
        if (filter_var($link, FILTER_VALIDATE_URL)) {
            $Review->setUrlArticle($link);
            $Review->setUserReview($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Review);
            $entityManager->flush();
        }
        return $this->redirectToRoute("app_default_index");
    }
}