<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\News;
use App\Models\LovedNews;
use App\Libraries\Helpers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NewsController extends BaseController
{
    public function __construct(
        private $newsModel = new News(),
        private $lovedNewsModel = new LovedNews()
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
                'tbl_comments.id_news', 
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
                'tbl_comments.id_news',
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

    public function loveNews(Request $request) {

        try {
            $validator = Validator::make($request->all(),[
                'id_news' => 'required|integer|exists:tbl_news,id',
                'id_user' => 'required|integer|exists:tbl_user,id',
            ],[
                'id_news.required' => "ID NEWS tidak boleh kosong",
                'id_user.required' => "ID USER tidak boleh kosong",
                'id_news.integer' => "ID NEWS harus berupa integer",
                'id_user.integer' => "ID USER harus berupa integer",
                'id_news.exists' => "ID NEWS tidak valid/tidak terdaftar",
                'id_user.exists' => "ID USER tidak valid/tidak terdaftar",
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'code' => '406',
                    'status' => 'failed',
                    'message' => $validator->errors()
                ],406);
            }
            if (
                $this->lovedNewsModel::where('id_news','=',$request->input('id_news'))->where('id_user','=',$request->input('id_news'))->get()->count() > 0
            ) {
                return response([
                    'code' => '400',
                    'status' => 'failed',
                    'message' => 'Berita sudah pernah anda sukai'
                ],400);
            }

            $this->newsModel::find($request->input('id_news'))->increment('total_love');
            $this->lovedNewsModel::insert([
                'id_news' => $request->input('id_news'),
                'id_user' => $request->input('id_user'),
            ]);

            return response()->json([
                'code' => '201',
                'status' => 'ok',
                'message' => 'Berhasil menyukai berita'
            ],201);

            
        } catch (Exception $error) {
            return response([
                'code' => '500',
                'status' => 'failed',
                'message' => 'Oops terjadi kesalahan di dalam server, silahkan coba lagi nanti'
            ],500);
        }

    }
}
