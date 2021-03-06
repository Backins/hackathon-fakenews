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

    const SCORE_CONFIANCE = 70;
    const SCORE_CONFIANCE_COLOR = "#26B0BD";
    const SCORE_MOYEN = 35;
    const SCORE_MOYEN_COLOR = "#F8A931";
    const SCORE_MAUVAIS_COLOR = "#E7334D";

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

        return array_splice($data, 0, 3);
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
        $level = $targetSource ? $targetSource->getConfidenceLevel() : 0 ;
        $score+= self::NOTE * $level * self::COEFF_DOUBTFUL_DOMAIN;

        $reviews = $this->reviewRepository->findBy(['urlArticle' =>$article->pageUrl]);
        $score+= self::NOTE * count($reviews) * 0.5 * self::COEFF_FAKE_REVIEW;

        $scoreResult = (self::COEFF_DOUBTFUL_DOMAIN * 4) / $score;
        $backgroundColor = self::SCORE_CONFIANCE_COLOR;
        $textColor = "#FFF";
        $scorePercent = $scoreResult * 100;
        if($scorePercent < self::SCORE_CONFIANCE) {
            $backgroundColor = self::SCORE_MOYEN_COLOR;
        }
        if($scorePercent < self::SCORE_MOYEN) {
            $backgroundColor = self::SCORE_MAUVAIS_COLOR;
        }
        if($scorePercent < 60){
            $textColor = "#1B2439";
        }
        if($scoreResult < 0){
            $scoreResult = 0;
        } elseif($scoreResult > 1){
            $scoreResult = 0.99;
        }
        return  [
            "value" => $scoreResult ,
            "textColor" => $textColor,
            "backgroundColor" => $backgroundColor,
        ];
    }

    private function calculMistakesMatches($mistakeMatches,$nbMistakes){

        foreach($mistakeMatches->matches as $match){
            if($match->type->typeName === 'UnknownWord')
                $nbMistakes++;
        }


        return $nbMistakes < 0 ?  0 : $nbMistakes;

    }
}
