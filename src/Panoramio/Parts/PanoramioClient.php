<?php
/**
 * User: Vasiliy Shvakin (orbisnull) zen4dev@gmail.com
 */

namespace Panoramio\Parts;


use Panoramio\Model\Client;
use Panoramio\Model\Request;

trait PanoramioClient
{
    /**
     * @var Client
     */
    protected $panoramio;

    /**
     * @param \Panoramio\Model\Client $panoramio
     */
    public function setPanoramio($panoramio)
    {
        $this->panoramio = $panoramio;
    }

    /**
     * @return \Panoramio\Model\Client
     */
    public function getPanoramio()
    {
        return $this->panoramio;
    }

    public function getPanoramioImages($lat, $lon, $distance = 50, $size = "medium", $order = "upload_date", $limit = 10, $offset = 0)
    {
        $request = new Request();
        $request->setLat($lat);
        $request->setLon($lon);
        $request->setDistanceInMetres($distance);

        $data = $this->getPanoramio()->getImages($request, $size, $order, $limit, $offset);
        if (!$data) {
            return [];
        }
//        $photos = $data["photos"];
        return $data;
    }
}