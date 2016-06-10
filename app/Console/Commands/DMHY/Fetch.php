<?php
namespace App\Console\Commands\DMHY;

use App\Contracts\Item as ItemContract;
use App\Models\DMHY\DMHY;
use DB;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Fetch extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'dmhy:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch from DMHY.';

    protected $site;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $start = $this->option('start');
        $end   = $this->option('end');

        $page = $this->argument('page');

        if (!$start) {
            $start = $end = $page;
        }
        $current = $start;

        $this->site = new DMHY([
            'url'     => $this->option('url'),
            'timeout' => $this->option('timeout'),
        ]);

        if ($end) {
            while ($current <= $end) {
                $this->fetch($current);
                $current++;
            }
        } else {
            do {
                $count = $this->fetch($current);
                $current++;
            } while ($count > 0);
        }
    }

    protected function fetch($current)
    {
        $page    = $this->site->fetch($current);
        $content = (string) $page;
        $count   = $page->items()->count();
        foreach ($page->items() as $item) {
            DB::table('publish')->updateOrInsert(['link' => $item->link()], $this->processPublish($item));
            if ($item->categoryID()) {
                DB::table('category')->updateOrInsert(['id' => $item->categoryID()], $this->processCategory($item));
            }
            if ($item->publisherID()) {
                DB::table('publisher')->updateOrInsert(['id' => $item->publisherID()], $this->processPublisher($item));
            }
            if ($item->fansubID()) {
                DB::table('fansub')->updateOrInsert(['id' => $item->fansubID()], $this->processFansub($item));
            }
        }

        if ($this->option('interval')) {
            sleep($this->option('interval'));
        }

        if (!$content) {
            $this->error("[PAGE {$current}] NO CONTENT, RETRY");
            return $this->fetch($current);
        }

        $this->info("[PAGE {$current}] OK ({$count})");
        return $count;
    }

    protected function processPublish(ItemContract $item)
    {
        $time = time();
        return [
            'category_id'   => $item->categoryID(),
            'fansub_id'     => $item->fansubID(),
            'title'         => $item->title(),
            'link'          => $item->link(),
            'download_link' => $item->downloadLink(),
            'file_size'     => $item->fileSize(),
            'publisher_id'  => $item->publisherID(),
            'publish_time'  => $item->publishTime(),
            'created_at'    => $time,
            'updated_at'    => $time,
            'deleted_at'    => 0,
        ];
    }

    protected function processCategory(ItemContract $item)
    {
        $time = time();
        return [
            'id'         => $item->categoryID(),
            'name'       => $item->categoryName(),
            'created_at' => $time,
            'updated_at' => $time,
            'deleted_at' => 0,
        ];
    }

    protected function processPublisher(ItemContract $item)
    {
        $time = time();
        return [
            'id'         => $item->publisherID(),
            'name'       => $item->publisherName(),
            'created_at' => $time,
            'updated_at' => $time,
            'deleted_at' => 0,
        ];
    }

    protected function processFansub(ItemContract $item)
    {
        $time = time();
        return [
            'id'         => $item->fansubID(),
            'name'       => $item->fansubName(),
            'created_at' => $time,
            'updated_at' => $time,
            'deleted_at' => 0,
        ];
    }

    protected function getOptions()
    {
        return [
            ['start', 's', InputOption::VALUE_OPTIONAL, 'First page to fetch.'],
            ['end', 'e', InputOption::VALUE_OPTIONAL, 'Last page to fetch.'],
            ['url', null, InputOption::VALUE_OPTIONAL, 'Use special url.'],
            ['timeout', null, InputOption::VALUE_OPTIONAL, 'The maximum number of seconds to fetch a page.'],
            ['interval', null, InputOption::VALUE_OPTIONAL, 'Interval per page.', 2],
        ];
    }

    protected function getArguments()
    {
        return [
            ['page', InputArgument::OPTIONAL, 'Which page to fetch.', 1],
        ];
    }
}
