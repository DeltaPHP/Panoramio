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

use Attach\Model\File;

class Client
{
    // Specifics for communication with the actual URL itself
    protected $userAgent = 'info@mypanoramiobot.com';
    protected $headers = ['Panoramio-Client-Version: 0.1'];
    protected $apiUrl = 'http://www.panoramio.com/map/get_panoramas.php';

    protected $connectionErrors = 0;

    /**
     * @var AuthorManager
     */
    protected $authorManager;

    /**
     * @param \Panoramio\Model\AuthorManager $authorManager
     */
    public function setAuthorManager($authorManager)
    {
        $this->authorManager = $authorManager;
    }

    /**
     * @return \Panoramio\Model\AuthorManager
     */
    public function getAuthorManager()
    {
        return $this->authorManager;
    }


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
        if ($this->connectionErrors > 3) {
            return false;
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        if ($this->connectionErrors > 1) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($ch, CURLOPT_TIMEOUT, 7);
        }
//        curl_setopt($ch, CURLOPT_VERBOSE, 1);
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
            $this->connectionErrors = $this->connectionErrors + 1;
            return false;
        }
    }

    /**
     * Get a set of images from the Panoramio API
     */
    public function getImagesRaw(Request $request, $size = "medium", $order = "upload_date", $limit = 10, $offset = 0)
    {
        $url = $this->getUrl($request, $size, $order, $limit, $offset);
        $response = $this->processRequest($url);
        return $response;
    }

    /**
     * @param array $panoramioData
     * @return ImageFile
     * @deprecated
     */
    public function createImage(array $panoramioData)
    {
        return $this->create($panoramioData);
    }

    public function load(ImageFile $image, $data)
    {
        $am = $this->getAuthorManager();
        if (isset($data["photo_id"])) {
            $image->setId($data["photo_id"]);
            $image->setPath($data["photo_file_url"]);
            $image->setName($data["photo_title"]);
            $authorId = (integer) $data["owner_id"];
            $authorData = [
                "id" => $data["owner_id"],
                "name" => $data["owner_name"],
                "uri" => $data["owner_url"]
            ];
        } else {
            $image->setId($data["id"]);
            $image->setPath($data["path"]);
            $image->setName($data["name"]);
            if (isset($data["author"])) {
                $authorId = (integer) $data["author"]["id"];
                $authorData = $data["author"];
            }
        }
        if (isset($authorId)) {
            $author = $am->getAuthor($authorId);
        }

        if (!$author && isset($authorData)) {
            $author = $am->create($authorData);
        }
        if ($author) {
            $image->setAuthor($author);
        }
        return $image;
    }

    public function create(array $panoramioData = null)
    {
        $image = new ImageFile();
        if (!empty($panoramioData)) {
            $this->load($image, $panoramioData);
        }
        return $image;
    }

    /**
     * @param float $lat
     * @param float $lon
     * @param int $distance
     * @param string $size
     * @param string $order
     * @param int $limit
     * @param int $offset
     * @return ImageFile[]
     */
    public function getImages($lat, $lon, $distance = 50, $size = "medium", $order = "upload_date", $limit = 6, $offset = 0)
    {
        $request = new Request();
        $request->setLat($lat);
        $request->setLon($lon);
        $request->setDistanceInMetres($distance);

        $data = $this->getImagesRaw($request, $size, $order, $limit, $offset);
        if (!$data) {
            return [];
        }
        $images = [];
        $photos = isset($data["photos"]) ? $data["photos"] : [];
        foreach($photos as $imageData) {
            if (($imageData["width"] / $imageData["height"]) > 2.3) {
                continue;
            }
            $images[] = $this->create($imageData);
        }
        return $images;
    }

}
