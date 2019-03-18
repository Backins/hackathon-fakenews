<?php
/**
 * Created by PhpStorm.
 * User: backin
 * Date: 18/03/2019
 * Time: 23:02
 */

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
}