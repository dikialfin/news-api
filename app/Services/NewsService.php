<?php

namespace App\Services;

require_once "const.php";

use GuzzleHttp\Client;
use App\Models\News;
use Exception;
use App\Services\HeadlineNews;

class NewsService
{

    private $client = '';
    private $newsModel;

    public function __construct(
        private String $jenisBerita
    ) {
        $this->client = new Client();
        $this->newsModel = new News();
    }

    /*
    
        FUNCTION getNews()
    function ini di gunakan untuk mengambil data berita terbaru dari news api berdasarkan kategory jenis berita nya.
    return data dari getNews() ini berupa array 

    */

    private function getNews()
    {
        try {
            $resource = $this->client->get(BASE_URL . "&category=$this->jenisBerita");

            if ($resource->getStatusCode() == 200) {
                return json_decode($resource->getBody()->getContents())->articles;
            } else if ($resource->getStatusCode() >= 500) {
                throw new Exception("Terjadi kesalahan ketika mengambil data $this->jenisBerita dari news api");
            }
        } catch (Exception $th) {
            throw $th;
        }
    }

    /*
    
        FUNCTION getHeadlineNews()
    function ini di gunakan untuk mengambil data berita headline news terbaru dari news api.
    return data dari getHeadlineNews() ini berupa array 

    */

    private function getHeadlineNews() {
        try {
            $resource = $this->client->get(BASE_URL);
            
            if($resource->getStatusCode() == 200) {
                return json_decode($resource->getBody()->getContents());
            } else if ($resource->getStatusCode() >= 500) {
                throw new Exception("Terjadi kesalahan ketika mengambil data Headline News dari news api");
            }

        } catch (Exception $th) {
            throw $th;
        }
    }

    /*
    
        FUNCTION isHeadlineNews()
    function ini di gunakan untuk memvalidasi apakah data berita yang di ambil dari function getNews() terdapat berita yang termasuk ke dalam headline news atau tidak.
    return data dari isHeadlineNews() ini berupa boolean 

    */

    private function isHeadlineNews($dataNews)
    {
        try {
            $headlineNews = $this->getHeadlineNews();
            if ($headlineNews->totalResults > 0) {
                foreach ($headlineNews->articles as $headlineArticle) {
                    if ($headlineArticle->title == $dataNews->title) {
                        return true;
                    }
                }
            }

            return false;
        } catch (Exception $error) {
            throw $error;
        }
    }

    /*
    
        FUNCTION hasAvailable()
    function ini di gunakan untuk memvalidasi apakah data berita yang akan di inputkan sudah ada di dalam database atau belum.
    return data dari hasAvailable() ini berupa boolean 

    */

    private function hasAvailable($dataNews)
    {
        try {
            $results = $this->newsModel::where('title', $dataNews->title)->get();
            if (count($results) > 0) {
                return true;
            }

            return false;
        } catch (Exception $error) {
            throw $error;
        }
    }

    /*
    
        FUNCTION mappingNews()
    function ini di gunakan untuk membuat format data berita yang siap untuk langsung di insert ke dalam database
    return data dari mappingNews() ini berupa array 

    */

    private function mappingNews($dataNews)
    {
        try {
            $data = [];
            $index = 0;
            foreach ($dataNews as $news) {
                if ($news->content !== null) {
                    if (!$this->hasAvailable($news)) {
                        array_push($data, [
                            'source' => $news->source->name == null ? "unknown" : $news->source->name,
                            'author' => $news->author == null ? "unknown" : $news->author,
                            'title' => $news->title == null ? "unknown" : $news->title,
                            'description' => $news->description == null ? "unknown" : $news->description,
                            'published_at' => strtotime($news->publishedAt),
                            'content' => $news->content == null ? "unknown" : $news->content,
                            'url_image' => $news->urlToImage == null ? "unknown" : $news->urlToImage,
                            'category' => $this->jenisBerita,
                            'is_headline' => false,
                        ]);
                        if ($this->isHeadlineNews($news)) {
                            $data[$index]['is_headline'] = true;
                        }
                    }
                }
            }

            return $data;
        } catch (Exception $error) {
            throw $error;
        }
    }

    /*
    
        FUNCTION insertNews()
    function ini di gunakan untuk memasukan data ke dalam database.
    return data dari insertNews() ini berupa array 

    */

    public function insertNews()
    {
        try {
            $newsData = $this->mappingNews($this->getNews());
            $this->newsModel::insert($newsData);
        } catch (Exception $th) {
            throw $th;
        }
    }
}
