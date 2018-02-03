<?php
namespace App\Console\Commands\Weibo;

use DB;
use Illuminate\Console\Command;
use SaeTClientV2;

class Publish extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'weibo:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish to Weibo.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // fuck weibo sdk
        $_SERVER['REMOTE_ADDR'] = '233.233.233.233';

        $weiboAccounts = DB::table('weibo')->get();
        foreach ($weiboAccounts as $weiboAccout) {
            $this->comment("Start {$weiboAccout->name} (last from {$weiboAccout->last_id}):");
            $contents = DB::table('publish')
                ->where('id', '>', $weiboAccout->last_id)
                ->where('fansub_id', $weiboAccout->fansub_id)
                ->orderBy('id', 'asc')
                ->get();
            $count = count($contents);
            $this->comment("Find {$count} to publish.");
            $lastID      = $weiboAccout->last_id;
            $appKey      = $weiboAccout->app_key;
            $appSecret   = $weiboAccout->app_secret;
            $accessToken = $weiboAccout->access_token;
            foreach ($contents as $content) {
                $client = new SaeTClientV2($appKey, $appSecret, $accessToken);
                if (mb_strlen($content->title) > 62) {
                    $title = mb_substr($content->title, 0, 60) . '..';
                } else {
                    $title = $content->title;
                }
                $downloadLink = explode('&', $content->download_link)[0];
                $link         = "https://share.dmhy.org{$content->link}";
                $status       = "{$title} {$downloadLink} {$link}";
                $result       = $client->share($status);
                if (
                    ($result && isset($result['id'])) ||
                    (isset($result['error_code']) && $result['error_code'] == 20017)
                ) {
                    // 已发送
                    $this->info("Publish [{$content->title}]({$content->id}) success!");
                    $lastID = $content->id;
                } elseif (
                    ($result && isset($result['id'])) ||
                    (isset($result['error_code']) && $result['error_code'] == 20021)
                ) {
                    // content is illegal!
                    $this->error("Publish [{$content->title}]({$content->id}) fail!{$result['error']}({$result['error_code']}) [SKIP]");
                    $lastID = $content->id;
                } else {
                    $this->error("Publish [{$content->title}]({$content->id}) fail!");
                    if (isset($result['error'])) {
                        $this->error("{$result['error']}({$result['error_code']})");
                    }
                    break;
                }
                sleep(3);
            }
            if ($lastID > $weiboAccout->last_id) {
                DB::table('weibo')->where('id', $weiboAccout->id)
                    ->update(['last_id' => $lastID]);
            }
        }
    }

    public function line($string, $style = null, $verbosity = null)
    {
        $dateTime = date('Y-m-d H:i:s');
        parent::line("[{$dateTime}] {$string}", $style, $verbosity);
    }
}
