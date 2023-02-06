<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsService;
use Exception;
use Illuminate\Support\Facades\Log;

class TechnologyNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsUpdate:technology';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'gunakan perintah ini untuk mengupdate/mengambil berita terbaru dari news api yang kategori nya technology';

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
            $TechnologyNews = new NewsService('technology');
            $TechnologyNews->insertNews();
            Log::info('Get data Technology news berhasil');
        } catch (Exception $th) {
            Log::info($th->getMessage());
        }
    }
}