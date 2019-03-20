<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 2019-02-04
 * Time: 11:29
 */

namespace App\EventListener;


use App\Entity\Review;
use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;

class EntityListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof User || $entity instanceof Review){
            $entity->setCreatedAt(new \DateTime());
            $entity->setUpdatedAt(new \DateTime());
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof User || $entity instanceof Review){
            $entity->setUpdatedAt(new \DateTime());
        }
    }
}