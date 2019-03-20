<?php
/**
 * Created by PhpStorm.
 * User: backin
 * Date: 04/02/2019
 * Time: 18:21
 */

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class NewscanService
{
    private $manager;
    private $templating;

    const DIFFBOTKEY = "73ea7f6f944b60ac8cba410d3fdc5b49";
    const OZAEKEY = "7533622b78214434a24d49d4c4bda74a";

    public function getArticle($url)
    {
        $url = "https://api.diffbot.com/v3/article?token=".self::DIFFBOTKEY."&url=".$url;
        $response = self::callApi($url);

        return $response;
    }

    public function getTopicsArticle($topics)
    {
        $date = new \DateTime();
        $now = $date->format('Ymd');
        $date->setTimestamp(mktime(0, 0, 0, date("m")-3, date("d"),   date("Y")));
        $threeMonth = $date->format('Ymd');
        $arrayReturn = [];

        $topics = array_splice($topics, 0, 3);
        foreach($topics as $topic){
            $url = "https://api.ozae.com/gnw/articles?date=".$threeMonth."__".$now."&key=".self::OZAEKEY."&edition=fr-fr&query=".urlencode($topic->label)."&hard_limit=2";
            $response = self::callApi($url);
            $arrayReturn[] = [
                'label' => $topic->label,
                'labelUrl' => str_replace(' ', '-', $topic->label),
                'ozaeArticles' => $response
            ];
        }
        return $arrayReturn;
    }

    private function callApi($url)
    {
        $response = file_get_contents($url);
        return json_decode($response);
    }
}