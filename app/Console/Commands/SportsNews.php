<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsService;
use Exception;
use Illuminate\Support\Facades\Log;

class SportsNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsUpdate:sports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'gunakan perintah ini untuk mengupdate/mengambil berita terbaru dari news api yang kategori nya sports';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        try {
            $SportsNews = new NewsService('sports');
            $SportsNews->insertNews();
            Log::info('Get data Sports news berhasil');
        } catch (Exception $th) {
            Log::info($th->getMessage());
        }
    }
}