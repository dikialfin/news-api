<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\News;
use App\Libraries\Helpers;
use Exception;

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
                $result = $this->newsModel::where('is_headline',true)->paginate(5);
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
                $result = $this->newsModel::where('category',$category)->paginate(5);
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
            'data' => []
        ],500,[
            'Content-Type' => 'application/json'
        ]);
       }
    }
}
