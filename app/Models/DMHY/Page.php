<?php
namespace App\Models\DMHY;

use Illuminate\Support\Collection;
use PHPHtmlParser\Dom;

class Page
{
    protected $items;
    protected $page;

    public function __construct(Dom $page)
    {
        $this->page  = $page;
        $this->items = $this->itemParser($page);
    }

    protected function itemParser(Dom $page)
    {
        $items = [];
        $rows  = $page->find('#topic_list tbody tr');
        foreach ($rows as $row) {
            $tag     = $row->find('td', 2)->find('span.tag', 0);
            $items[] = new Item([
                // 发布时间
                'publishTime'   => strtotime(trim($row->find('td', 0)->find('span', 0)->text)),
                // 分类ID
                'categoryID'    => preg_match(
                    '~(?<categoryID>\d+)~',
                    trim($row->find('td', 1)->find('a', 0)->href),
                    $match
                ) ? $match['categoryID'] : 0,
                // 分类名
                'categoryName'  => trim($row->find('td', 1)->find('font', 0)->text),
                // 字幕组ID
                'fansubID'      => preg_match(
                    '~(?<fansubID>\d+)~',
                    trim($tag ? $tag->find('a', 0)->href : ''),
                    $match
                ) ? $match['fansubID'] : 0,
                // 字幕组名
                'fansubName'    => trim($tag ? $tag->find('a', 0)->text : ''),
                // 标题
                'title'         => trim($row->find('td', 2)->find('a', 0)->text),
                // 页面链接
                'link'          => trim($row->find('td', 2)->find('a', 0)->href),
                // 下载链接
                'downloadLink'  => trim($row->find('td', 3)->find('a', 0)->href),
                // 大小
                'fileSize'      => trim($row->find('td', 4)->text),
                // 发布者ID
                'publisherID'   => preg_match(
                    '~(?<publisherID>\d+)~',
                    trim($row->find('td', 8)->find('a', 0)->href),
                    $match
                ) ? $match['publisherID'] : 0,
                // 发布者
                'publisherName' => trim($row->find('td', 8)->find('a', 0)->text),
            ]);
        }
        $this->items = new Collection($items);
        return $this->items;
    }

    public function items()
    {
        return $this->items;
    }

    public function __toString()
    {
        return $this->page->__toString();
    }
}
