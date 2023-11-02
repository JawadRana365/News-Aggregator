<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\News;

class NewsCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'We are going to fetch data from multiple sources using this cron job';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /********* NEWS API *********/
        $newsApiKey = \Config::get('keys.NEWS_API_AUTH_KEY');
        $date = date('Y-m-d',strtotime("-1 days"));
        $startDate = date('Y-m-d',strtotime("-30 days"));
        $response = (Http::get("https://newsapi.org/v2/everything?q=all&from={$startDate}&to{$date}&apiKey={$newsApiKey}"))->json($key = null, $default = null);
        if($response["status"] == "ok" && $response["totalResults"] > 0) {
            $articles = $response["articles"];
            foreach($articles as $article){
                $article = (array)$article;
                $publishedAt = date('Y-m-d H:i:s',strtotime($article["publishedAt"]));
                News::create([
                    'author' => $article["author"],
                    'title' => $article["title"],
                    'description' => $article["description"],
                    'source' => "New API",
                    'url' => $article["url"],
                    'image' => $article["urlToImage"] == null ? "images/default-image.png" : $article["urlToImage"],
                    'publishedAt' => $publishedAt,
                    'content' => $article["content"]
                ]);
            }
        }

        /********* THE NEW YORK TIMES API *********/
        $timesApiKey = \Config::get('keys.NEWYORK_TIMES_API_AUTH_KEY');
        $date = date('Ymd',strtotime("-1 days"));
        $startDate = date('Ymd',strtotime("-30 days"));
        $response = (Http::get("https://api.nytimes.com/svc/search/v2/articlesearch.json?begin_date={$startDate}&end_date={$date}&api-key={$timesApiKey}"))->json($key = null, $default = null);
        if($response["status"] == "OK") {
            $articles = $response["response"]["docs"];
            foreach($articles as $article){
                $article = (array)$article;
                $publishedAt = date('Y-m-d H:i:s',strtotime($article["pub_date"]));
                News::create([
                    'author' =>  "New York Times " . $article["type_of_material"],
                    'title' => $article["headline"]["main"],
                    'description' => $article["abstract"],
                    'source' => "New York Times",
                    'url' => $article["web_url"],
                    'image' => count($article["multimedia"]) ? "https://www.nytimes.com/{$article["multimedia"][0]["url"]}" : "images/default-image.png",
                    'publishedAt' => $publishedAt,
                    'content' => $article["lead_paragraph"]
                ]);
            }
        }



        /********* THE Guardians API *********/
        $guardianApiKey = \Config::get('keys.THE_GUARDIAN_API_AUTH_KEY');
        $date = date('Y-m-d',strtotime("-1 days"));
        $startDate = date('Y-m-d',strtotime("-30 days"));
        $response = ((Http::get("https://content.guardianapis.com/search?from-date={$startDate}&to-date={$date}&api-key={$guardianApiKey}"))->json($key = null, $default = null))["response"];
        if($response["status"] == "ok" && $response["total"] > 0) {
            $articles = $response["results"];
            foreach($articles as $article){
                $article = (array)$article;
                $publishedAt = date('Y-m-d H:i:s',strtotime($article["webPublicationDate"]));
                News::create([
                    'author' =>  "The Guardian " . $article["sectionName"],
                    'title' => $article["webTitle"],
                    'description' => $article["webTitle"],
                    'source' => "The Guardian",
                    'url' => $article["webUrl"],
                    'image' => "images/default-image.png",
                    'publishedAt' => $publishedAt,
                    'content' => ""
                ]);
            }
            if($response["total"]>1){
                $totalPages = $response["total"];
                for ($i=2; $i <=  $totalPages; $i++) { 
                    $response = ((Http::get("https://content.guardianapis.com/search?from-date={$date}&to-date={$date}&page={$i}&api-key={$guardianApiKey}"))->json($key = null, $default = null))["response"];
                    if($response["status"] == "ok" && $response["total"] > 0) {
                        $articles = $response["results"];
                        foreach($articles as $article){
                            $article = (array)$article;
                            $publishedAt = date('Y-m-d H:i:s',strtotime($article["webPublicationDate"]));
                            News::create([
                                'author' =>  "The Guardian " . $article["sectionName"],
                                'title' => $article["webTitle"],
                                'description' => $article["webTitle"],
                                'source' => "The Guardian",
                                'url' => $article["webUrl"],
                                'image' => "images/default-image.png",
                                'publishedAt' => $publishedAt,
                                'content' => ""
                            ]);
                        }
                    }
                }
                
            }
        }
    }
}
