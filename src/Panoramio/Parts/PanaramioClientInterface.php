<?php
/**
 * User: Vasiliy Shvakin (orbisnull) zen4dev@gmail.com
 */

namespace Panoramio\Parts;


interface PanaramioClientInterface
{

    public function setPanoramio($panoramio);

    /**
     * @return \Panoramio\Model\Client
     */
    public function getPanoramio();

    public function getPanoramioImages($lat, $lon, $distance = 20, $size = "medium", $order = "upload_date", $limit = 10, $offset = 0);
} 