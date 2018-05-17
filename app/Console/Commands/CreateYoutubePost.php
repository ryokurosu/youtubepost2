<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Word;
use Google_Client;
use Google_Service_YouTube;
use Mail;

class CreateYoutubePost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'youtube:post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'youtube post description. from word, auto popular post';

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

     $word = Word::where('done',0)->first();

     /* ない場合は強制終了 */
     if($word){
        $word->fill(['done' => 1])->save();
     }else{
       return false;
   }
   $title = $word->text;
   $author = "";
   $movie_author = [];
   $title_author = [];

   $auth_key = 'AIzaSyByklL-qD8ZM0d85xhSymawrPQHDvCM670';
   $baseurl = 'https://www.youtube.com/watch?v=';

   $client = new Google_Client();
   $client->setApplicationName('YouTube');
   $client->setDeveloperKey($auth_key);
   $youtube = new Google_Service_YouTube($client);

   $searchResponse = $youtube->search->listSearch('id,snippet', array(
    'q' => $title,
    'maxResults' => 10,
    'order' => 'viewCount'
));

   $ret_data = "";

   foreach($searchResponse['items'] as $index => $item){
    try{
        $movie_code = $item['id']['videoId'];
        $movie_title = $item['snippet']['title'];
        $movie_description = $item['snippet']['description'];
        $movie_author[] = $item['snippet']['channelTitle'];
        if($index < 3){
            $title_author[] = $item['snippet']['channelTitle'];
        }
        $movie_url = $baseurl.$movie_code;
        $rank = $index + 1;
        $ret_data .= "<h2>{$rank}位.{$movie_title}</h2>" . PHP_EOL. PHP_EOL;
        $ret_data .= '<iframe width="728" height="410" src="https://www.youtube.com/embed/'.$movie_code.'" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen=""></iframe>' . PHP_EOL. PHP_EOL;
        $ret_data .= "<p>{$movie_description}</p>" . PHP_EOL. PHP_EOL;

    }catch(Exception $e){
            // echo $e->getMessage();
    }
}

$author = implode('、',$movie_author);
$title_author = implode('、',$title_author);

$post_description = "今、{$title}というワードが話題です。今回は{$title}に関する人気のYoutubeトップニュースをランキング形式でまとめてみました！" . PHP_EOL . PHP_EOL;
$post_description .= "{$author}の動画を一挙に紹介していきます。！" . PHP_EOL;
$post_title = "今話題の{$title}の動画TOP10を紹介。{$title_author}...";

$content = $post_description.$ret_data; 
$mail = [
    'post_title' => $post_title,
    'content' => $content
];
Mail::send('emails.welcome', array('content' => $content), function($message) use ($post_title,$content){
    $message->to('knowrop1208.ymatome2@gmail.com')
    ->subject($post_title);
});

}
}
