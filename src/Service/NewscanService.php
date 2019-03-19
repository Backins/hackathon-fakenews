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
        $arrayReturn = [];
        foreach($topics as $topic){
            $url = "https://api.ozae.com/gnw/articles?date=20140101__20190630&key=".self::OZAEKEY."&edition=fr-fr&query=".urlencode($topic->label)."&hard_limit=20";
            $response = self::callApi($url);
            $arrayReturn[] = [
                'label' => $topic->label,
                'ozaeArticles' => $response
            ];
            dump($arrayReturn);
            die;
        }
        return $topics;
    }

    private function callApi($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        return json_decode($response);
    }
}