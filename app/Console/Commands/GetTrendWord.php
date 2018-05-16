<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte\Client;
use App\Word;

class GetTrendWord extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'word:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'word get from google trend. daily script ? frequentry';

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

        $youtube = "https://trends.google.co.jp/trends/hottrends/atom/feed?pn=p4";

        $client = new Client();
        $crawler = $client->request('GET',$youtube);

        $crawler->filter('item')->each(function($node){
            try{

                $title = $node->filter("title")->text();
                if(!Word::where('text',$title)->first()){
                    Word::create(['text' => $title]);
                    echo $title."done".PHP_EOL;
                }
            }catch(Exception $e){
                echo $e->getMessage();
            }
        });
    }
}
