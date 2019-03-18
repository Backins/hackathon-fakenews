<?php
/**
 * Created by PhpStorm.
 * User: backin
 * Date: 04/02/2019
 * Time: 18:21
 */

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class UserService
{
    private $manager;
    private $templating;


    /**
     * UserService constructor.
     * @param ObjectManager $manager
     * @param \Twig_Environment $templating
     */
    public function __construct(ObjectManager $manager, \Twig_Environment $templating)
    {
        $this->manager = $manager;
        $this->templating = $templating;
    }
}