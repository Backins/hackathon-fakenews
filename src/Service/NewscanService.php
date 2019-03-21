<?php
/**
 * Created by PhpStorm.
 * User: backin
 * Date: 04/02/2019
 * Time: 18:21
 */

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\TargetSourceRepository;
use App\Repository\ReviewRepository;

class NewscanService
{

    const DIFFBOTKEY = "73ea7f6f944b60ac8cba410d3fdc5b49";
    const OZAEKEY = "7533622b78214434a24d49d4c4bda74a";
    const NOTE = 5;
    const COEFF_TITLE_UPPER = 2;
    const COEFF_EXCLAMATION_MARK = 3;
    const COEFF_TITLE_MISTAKE = 4;
    const COEFF_DOUBTFUL_DOMAIN = 8;
    const COEFF_ARTICLE_MISTAKE = 3;
    const COEFF_FAKE_REVIEW = 1;

    public $targetSourceRepository;
    public $reviewRepository;

    public function __construct(TargetSourceRepository $targetSourceRepository,ReviewRepository $reviewRepository)
    {
        $this->targetSourceRepository = $targetSourceRepository;
        $this->reviewRepository = $reviewRepository;
    }

    public function getArticle($url)
    {
        $url = "https://api.diffbot.com/v3/article?token=".self::DIFFBOTKEY."&url=".$url;
        $response = $this->callApi($url);

        return $response;
    }

    private function getBestTopics($topics)
    {
        $score = [];$data = [];
        foreach ($topics as $topic){
            $score[]=$topic->score;
        }
        arsort($score);
        foreach($score as $k => $s)
        {
            $data[] = $topics [$k];
        }

        return array_splice($data, 0, 1);
    }

    public function getTopicsArticle($topics)
    {
        $date = new \DateTime();
        $now = $date->format('Ymd');
        $date->setTimestamp(mktime(0, 0, 0, date("m")-3, date("d"),   date("Y")));
        $threeMonth = $date->format('Ymd');
        $arrayReturn = [];

        $topics = self::getBestTopics($topics);

        foreach($topics as $topic){
            $url = "https://api.ozae.com/gnw/articles?date=".$threeMonth."__".$now."&key=".self::OZAEKEY."&edition=fr-fr&query=".urlencode($topic->label)."&hard_limit=4";
            $response = $this->callApi($url);
            $arrayReturn[] = [
                'label' => $topic->label,
                'labelUrl' => str_replace(' ', '-', $topic->label),
                'ozaeArticles' => $response
            ];
        }
        return $arrayReturn;
    }
//
//    private function callApi($url)
//    {
//        $response = file_get_contents($url);
//        return json_decode($response);
//    }

    private function callApi($url,$type = 'GET',$data = [])
    {

        $context = stream_context_create(array(
            'http' => array(
                'method' => $type,
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($data),
                'timeout' => 120
            )
        ));

        $resp = file_get_contents($url, FALSE, $context);
        return json_decode($resp);
    }

    public function calculArticleConfidenceLevel($article)
    {
        $score  = 0 ;


        if(ctype_upper($article->title))
            $score+= self::NOTE * self::COEFF_TITLE_UPPER;

        $nbExclamationMark = substr_count($article->title,'!') ;

        if($nbExclamationMark > 1)
            $score+= self::NOTE * self::COEFF_EXCLAMATION_MARK;

        $data = [
            'text' => $article->title,
            'language' => 'fr',
            'enabledOnly' => false
        ];

        $titleMistakesMatches = $this->callApi('https://languagetool.org/api/v2/check','POST',$data);
        $nbTitleMistakes = $this->calculMistakesMatches($titleMistakesMatches,-3);
        $score+= self::NOTE * $nbTitleMistakes * self::COEFF_TITLE_MISTAKE;

        $data['text'] = $article->text ;
        $textMistakesMatches = $this->callApi('https://languagetool.org/api/v2/check','POST',$data);
        $nbTextMistakes = $this->calculMistakesMatches($textMistakesMatches,- 10);
        $score+= self::NOTE * $nbTextMistakes * self::COEFF_ARTICLE_MISTAKE;

        $host = parse_url($article->pageUrl)['host'];
        $domain = str_replace("www.", "", $host);
        $targetSource = $this->targetSourceRepository->findOneBy(['domain' =>$domain]);

        $score+= self::NOTE * $targetSource->getConfidenceLevel() * self::COEFF_DOUBTFUL_DOMAIN;

        $reviews = $this->reviewRepository->findBy(['urlArticle' =>$article->pageUrl]);
        $score+= self::NOTE * count($reviews) * 0.5 * self::COEFF_FAKE_REVIEW;

        $scorePercent = (self::COEFF_DOUBTFUL_DOMAIN * 4) / $score;
        return  $scorePercent < 0 ? 0 : $scorePercent;
    }

    private function calculMistakesMatches($mistakeMatches,$nbMistakes){

        foreach($mistakeMatches->matches as $match){
            if($match->type->typeName === 'UnknownWord')
                $nbMistakes++;
        }


        return $nbMistakes < 0 ?  0 : $nbMistakes;

    }
}
