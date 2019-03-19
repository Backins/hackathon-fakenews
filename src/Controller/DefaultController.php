<?php
/**
 * Created by PhpStorm.
 * User: backin
 * Date: 18/03/2019
 * Time: 23:02
 */

namespace App\Controller;


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
    public function show(Request $request)
    {
        $link = $request->request->get('linkSearch');
        if (filter_var($link, FILTER_VALIDATE_URL)) {
            $Newscan = new NewscanService();
            $response = $Newscan->getArticle($link);
            $object = $response->objects[0];

            $topics = $Newscan->getTopicsArticle($object->tags);
            $imageArticle = $object->images[0];
            $contentArticle = $object->text;

            return $this->render('front/show.html.twig', [
                'topics' => $topics
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