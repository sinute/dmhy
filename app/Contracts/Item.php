<?php
namespace App\Contracts;

interface Item
{
    /**
     * 发布时间
     *
     * @author Sinute
     * @date   2016-06-11
     * @return integer
     */
    public function publishTime();

    /**
     * 分类ID
     *
     * @author Sinute
     * @date   2016-06-11
     * @return integer
     */
    public function categoryID();

    /**
     * 分类名
     *
     * @author Sinute
     * @date   2016-06-11
     * @return string
     */
    public function categoryName();

    /**
     * 字幕组ID
     *
     * @author Sinute
     * @date   2016-06-11
     * @return integer
     */
    public function fansubID();

    /**
     * 字幕组名
     *
     * @author Sinute
     * @date   2016-06-11
     * @return string
     */
    public function fansubName();

    /**
     * 标题
     *
     * @author Sinute
     * @date   2016-06-11
     * @return string
     */
    public function title();

    /**
     * 链接
     *
     * @author Sinute
     * @date   2016-06-11
     * @return string
     */
    public function link();

    /**
     * 下载链接
     *
     * @author Sinute
     * @date   2016-06-11
     * @return string
     */
    public function downloadLink();

    /**
     * 文件大小
     *
     * @author Sinute
     * @date   2016-06-11
     * @return string
     */
    public function fileSize();

    /**
     * 发布人ID
     *
     * @author Sinute
     * @date   2016-06-11
     * @return integer
     */
    public function publisherID();

    /**
     * 发布人
     *
     * @author Sinute
     * @date   2016-06-11
     * @return string
     */
    public function publisherName();
}
