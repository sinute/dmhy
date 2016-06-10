<?php
namespace App\Models\DMHY;

use App\Models\Curl;
use PHPHtmlParser\Dom;

class DMHY
{
    protected $options;

    public function __construct(array $options = [])
    {
        $this->options = array_merge([
            'timeout' => 5,
            'url'     => 'http://share.nichijou.me/topics/list/sort_id/2/page/{page}',
        ], array_filter($options));
    }

    public function fetch($page = null)
    {
        $dom = new Dom;
        $dom->loadFromUrl($this->pageUrl($page), [], new Curl([
            'timeout' => $this->options['timeout'],
        ]));
        return new Page($dom);
    }

    protected function pageUrl($page = null)
    {
        if (!$page) {
            $page = 1;
        }
        return str_replace('{page}', $page, $this->options['url']);
    }
}
