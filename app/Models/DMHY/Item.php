<?php
namespace App\Models\DMHY;

use App\Contracts\Item as ItemContract;
use Illuminate\Support\Fluent;

class Item extends Fluent implements ItemContract
{
    public function publishTime()
    {
        return $this->publishTime;
    }

    public function categoryID()
    {
        return $this->categoryID;
    }

    public function categoryName()
    {
        return $this->categoryName;
    }

    public function fansubID()
    {
        return $this->fansubID;
    }

    public function fansubName()
    {
        return $this->fansubName;
    }

    public function title()
    {
        return $this->title;
    }

    public function link()
    {
        return $this->link;
    }

    public function downloadLink()
    {
        return $this->downloadLink;
    }

    public function fileSize()
    {
        return $this->fileSize;
    }

    public function publisherID()
    {
        return $this->publisherID;
    }

    public function publisherName()
    {
        return $this->publisherName;
    }
}
