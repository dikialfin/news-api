<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\News;
use App\Libraries\Helpers;
use Exception;
use Illuminate\Support\Facades\DB;

class NewsController extends BaseController
{
    public function __construct(
        private $newsModel = new News()
    ){}

    public function getNews($category = null) {
       try {
        $date = Helpers::getDateToday();

        switch ($category) {
            case null:
                $result = $this->newsModel::select(
                    DB::raw('
                    tbl_news.id,
                    tbl_news.source, 
                    tbl_news.author, 
                    tbl_news.title, 
                    tbl_news.description, 
                    tbl_news.published_at, 
                    tbl_news.content, 
                    tbl_news.url_image, 
                    tbl_news.category, 
                    tbl_news.is_headline, 
                    tbl_news.total_love, count(tbl_comments.id_news) as total_comment')
                )
                ->leftJoin('tbl_comments','tbl_news.id','=','tbl_comments.id_news')
                ->where('is_headline',true)
                ->groupBy('tbl_news.id',
                'tbl_news.source', 
                'tbl_news.author', 
                'tbl_news.title', 
                'tbl_news.description', 
                'tbl_news.published_at', 
                'tbl_news.content', 
                'tbl_news.url_image', 
                'tbl_news.category', 
                'tbl_news.is_headline', 
                'tbl_news.total_love')
                ->paginate(5);
                return response()->json([
                    'code' => '200',
                    'status' => 'ok',
                    'data' => $result
                ],200,[
                    'Content-Type' => 'application/json'
                ]);
            case 'business':
            case 'entertainment':
            case 'health':
            case 'science':
            case 'sports':
            case 'technology':
                $result = $this->newsModel::select(
                    DB::raw('
                    tbl_news.id,
                    tbl_news.source, 
                    tbl_news.author, 
                    tbl_news.title, 
                    tbl_news.description, 
                    tbl_news.published_at, 
                    tbl_news.content, 
                    tbl_news.url_image, 
                    tbl_news.category, 
                    tbl_news.is_headline, 
                    tbl_news.total_love, count(tbl_comments.id_news) as total_comment')
                )
                ->leftJoin('tbl_comments','tbl_news.id','=','tbl_comments.id_news')
                ->where('category',$category)
                ->groupBy('tbl_news.id',
                'tbl_news.source', 
                'tbl_news.author', 
                'tbl_news.title', 
                'tbl_news.description', 
                'tbl_news.published_at', 
                'tbl_news.content', 
                'tbl_news.url_image', 
                'tbl_news.category', 
                'tbl_news.is_headline', 
                'tbl_news.total_love')
                ->paginate(5);
                return response()->json([
                    'code' => '200',
                    'status' => 'ok',
                    'data' => $result
                ],200,[
                    'Content-Type' => 'application/json'
                ]);
            break;
            
            default:
            return response()->json([
                'code' => '400',
                'status' => 'failed',
                'data' => []
            ],400,[
                'Content-Type' => 'application/json'
            ]);
                break;
        }
       } catch (Exception $th) {
        return response()->json([
            'code' => '500',
            'status' => 'failed',
            'message' => $th->getMessage(),
            'data' => []
        ],500,[
            'Content-Type' => 'application/json'
        ]);
       }
    }
}
