<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Word;
use Mail;

class DeleteDailyWord extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'word:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $list = [];
        foreach(Word::where('done',1)->get() as $word){
            $list[] = $word->text;
            $word->delete();
        }
        noticeDiscord(implode(',',$list));
    }
}
