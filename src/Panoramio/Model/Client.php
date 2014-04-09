<?php
/**
 * Simple class for retreiving images from the Panoramio API
 *
 * @author Anthony Mills
 * @author Vasiliy Shvakin
 * @copyright 2012 Anthony Mills ( anthony-mills.com )
 * @copyright 2014 Vasiliy Shvakin
 * @license GPL V3.0
 * @version 0.1
 */

namespace Panoramio\Model;

class Client
{
    // Specifics for communication with the actual URL itself
    protected $userAgent = 'info@mypanoramiobot.com';
    protected $headers = ['Panoramio-Client-Version: 0.1'];
    protected $apiUrl = 'http://www.panoramio.com/map/get_panoramas.php';

    /**
     * @param float|null $lat
     * @param float|null $lon
     * @return Request
     */
    public function createRequest($lat = null, $lon = null)
    {
        $request = new Request();
        if (!is_null($lat)) {
            $request->setLat($lat);
        }
        if (!is_null($lon)) {
            $request->setLon($lon);
        }
        return $request;
    }

    /**
     * Assemble the request data in preperation for passing to the API
     */
    public function getUrl(Request $request, $size = "medium", $order = "upload_date", $limit = 10, $offset = 0)
    {
        $params = [
            "set"   => $request->getSet(),
            "from"  => $offset,
            "to"    => $limit,
            "minx"  => $request->getMinLon(),
            "miny"  => $request->getMinLat(),
            "maxx"  => $request->getMaxLon(),
            "maxy"  => $request->getMaxLat(),
            "size"  => $size,
            "order" => $order,
        ];
        $params = http_build_query($params);
        $url = $this->apiUrl . "?" . $params;
        return $url;
    }

    /**
     * Send a formatted string of data as a GET to the API and collect the response
     */
    protected function processRequest($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);

        $apiResponse = curl_exec($ch);
        $responseInformation = curl_getinfo($ch);
        curl_close($ch);

        if (intval($responseInformation['http_code']) == 200) {
            return json_decode($apiResponse, true);
        } else {
            return false;
        }
    }

    /**
     * Get a set of images from the Panoramio API
     */
    public function getImages(Request $request, $size = "medium", $order = "upload_date", $limit = 10, $offset = 0)
    {
        $url = $this->getUrl($request, $size, $order, $limit, $offset);
        $response = $this->processRequest($url);
        return $response;
    }

}
