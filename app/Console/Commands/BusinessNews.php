<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsService;

class BusinessNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsUpdate:business';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'gunakan perintah ini untuk mengupdate/mengambil berita terbaru dari news api yang kategori nya business';

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
        
        $businessNews = new NewsService('business');
        $businessNews->insertNews();
    }
}