<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\News;

class NewsObj{
    public $autor;
    public $title;
    public $description;
    public $source;
    public $url;
    public $image;
    public $publishedAt;
    public $content;
}


class NewsController extends Controller
{
    /**
    * Get News
    * @param Request $request
    * @return News
    */
    public function getNews(Request $request)
    {
        try {
            $query = News::select('*');
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function ($query) use ($search){
                    $query->where('title', 'LIKE', '%'.$search.'%')
                    ->orWhere('author', 'LIKE', '%'.$search.'%')
                    ->orWhere('description', 'LIKE', '%'.$search.'%');
                });
            }
            if ($request->has('date')) {
                $date = $request->input('date');
                $query->where(function ($query) use ($date){
                    $query->where('publishedAt', '>=',  date('Y-m-d H:i:s',strtotime($date." 00:00:00")));
                    $query->where('publishedAt', '<=',  date('Y-m-d H:i:s',strtotime($date." 23:59:59")));
                });
            }
            if ($request->has('source')) {
                $query->where('source', $request->input('source'));
            }
            if ($request->has('category')) {
                $category = $request->input('category');
                $query->where(function ($query) use ($category){
                    $query->where('title', 'LIKE', '%'.$category.'%')
                    ->orWhere('author', 'LIKE', '%'.$category.'%')
                    ->orWhere('description', 'LIKE', '%'.$category.'%');
                });
            }

            return response()->json([
                'status' => true,
                'message' => 'News Data Retrived Successfully',
                'news' => $query->get(),
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
    * Get Live News
    * @param Request $request
    * @return News
    */
    public function getLiveNews(Request $request)
    {
        try {
            $queryParams = array();
            if ($request->has('search')) {
                $queryParams['search'] = $request->input('search');
            }
            if ($request->has('date')) {
                $queryParams['date'] = $request->input('date');
            }
            if ($request->has('category')) {
                $queryParams['category'] = $request->input('category');
            }

            /********** FORMAT SEARCH PARAM *********/
            $searchParam = array_key_exists("search",$queryParams) ? "q={$queryParams['search']}&" : "" ;
            $dateParam = array_key_exists("date",$queryParams) ? "from={$queryParams['date']}&to={$queryParams['date']}&" : "" ;
            $categoryParam = array_key_exists("category",$queryParams) ? "category={$queryParams['category']}&" : "" ;
            $newsApiKey = \Config::get('keys.NEWS_API_AUTH_KEY');
            $response = (Http::get("https://newsapi.org/v2/top-headlines?{$searchParam}{$dateParam}{$categoryParam}apiKey={$newsApiKey}"))->json($key = null, $default = null);
            $articles = array();
            if($response["status"] == "ok" && $response["totalResults"] > 0) {
                $articles = array_map(
                    function($article){
                        $news = new NewsObj();
                        $news->autor = $article["author"];
                        $news->title = $article["title"];
                        $news->description = $article["description"];
                        $news->source = "New API";
                        $news->url = $article["url"];
                        $news->image = $article["urlToImage"] == null ? "images/default-image.png" : $article["urlToImage"];
                        $news->publishedAt = $article["publishedAt"];
                        $news->content = $article["content"];
                        return $news;
                    },
                    $response["articles"]
                );
            }


            /********** FORMAT SEARCH PARAM *********/
            $dataInput = date("Ymd", strtotime($queryParams['date']));
            $dateParam = array_key_exists("date",$queryParams) ? "begin_date=" . $dataInput . "&end_date=" . $dataInput . "&" : "" ;
            $categoryParam = array_key_exists("category",$queryParams) ? "facet_fields=section_name&facet_filter=true&fq={$queryParams['category']}&" : "" ;
            $timesApiKey = \Config::get('keys.NEWYORK_TIMES_API_AUTH_KEY');
            $response = (Http::get("https://api.nytimes.com/svc/search/v2/articlesearch.json?{$searchParam}{$dateParam}{$categoryParam}api-key={$timesApiKey}"))->json($key = null, $default = null);
            $news = array();
            if($response["status"] == "OK") {
                $news = array_map(
                    function($article){
                        $news = new NewsObj();
                        $news->autor = "New York Times " . $article["type_of_material"];
                        $news->title = $article["headline"]["main"];
                        $news->description = $article["abstract"];
                        $news->source = "New York Times";
                        $news->url = $article["web_url"];
                        count($article["multimedia"]) ? $news->image =  "https://www.nytimes.com/{$article["multimedia"][0]["url"]}" : "images/default-image.png";
                        $news->publishedAt = $article["pub_date"];
                        $news->content = $article["lead_paragraph"];
                        return $news;
                    },
                    $response["response"]["docs"]
                );
            }

            /********** FORMAT SEARCH PARAM *********/
            $guardianApiKey = \Config::get('keys.THE_GUARDIAN_API_AUTH_KEY');
            $dataInput = date("Y-m-d", strtotime($queryParams['date']));
            $dateParam = array_key_exists("date",$queryParams) ? "from-date={$dataInput}&to-date={$dataInput}&" : "" ;
            $categoryParam = array_key_exists("category",$queryParams) ? "section={$queryParams['category']}&" : "" ;
            $response = ((Http::get("https://content.guardianapis.com/search?{$searchParam}{$dateParam}{$categoryParam}api-key={$guardianApiKey}"))->json($key = null, $default = null))["response"];
            $guardianNews = array();
            if($response["status"] == "ok" && $response["total"] > 0) {
                $guardianNews = array_map(
                    function($article){
                        $news = new NewsObj();
                        $news->autor = "The Guardian " . $article["sectionName"];
                        $news->title = $article["webTitle"];
                        $news->description = $article["webTitle"];
                        $news->source = "The Guardian";
                        $news->url = $article["webUrl"];
                        $news->image = "images/default-image.png";
                        $news->publishedAt = $article["webPublicationDate"];
                        $news->content = "";
                        return $news;
                    },
                    $response["results"]
                );
            }

            return response()->json([
                'status' => true,
                'message' => 'News Data Retrived Successfully',
                'queryParams' => $dateParam,
                'news' => array_merge($articles,$news,$guardianNews),
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
