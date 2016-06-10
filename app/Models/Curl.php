<?php
namespace App\Models;

use Log;
use PHPHtmlParser\CurlInterface;
use RuntimeException;

class Curl implements CurlInterface
{
    protected $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * 获取请求内容
     *
     * @author Sinute
     * @date   2016-06-10
     * @param  string     $url url
     * @return string|\RuntimeException
     */
    public function get($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => array_get($this->options, 'timeout', 5),
            CURLOPT_HTTPHEADER     => [
                "user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.110 Safari/537.36",
            ],
        ]);

        $response = curl_exec($curl);
        $err      = curl_error($curl);

        curl_close($curl);

        if ($err) {
            Log::warning('Error retrieving "' . $url . '" (' . $err . ')');
            $response = '';
        }

        return $response;
    }
}
