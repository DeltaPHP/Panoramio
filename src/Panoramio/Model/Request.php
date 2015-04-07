<?php
/**
 * User: Vasiliy Shvakin (orbisnull) zen4dev@gmail.com
 */

namespace Panoramio\Model;


class Request
{
    protected $lat;
    protected $lon;
    protected $distance = 0.02;
    protected $set = "public";

    protected $maxLat;
    protected $maxLon;
    protected $minLat;
    protected $minLon;

    protected $earthRadius = 6371;
    protected $minBearing = 255;
    protected $maxBearing = 45;


    /**
     * @param mixed $distance in km
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;
        $this->maxLat = null;
        $this->minLat = null;
        $this->maxLon = null;
        $this->minLon = null;
    }

    public function setDistanceInMetres($distance)
    {
        $this->setDistance($distance * 0.001);
    }

    /**
     * @return mixed
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @param mixed $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
        $this->maxLat = null;
        $this->minLat = null;
    }

    /**
     * @return mixed
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param mixed $lon
     */
    public function setLon($lon)
    {
        $this->lon = $lon;
        $this->maxLon = null;
        $this->minLon = null;
    }

    /**
     * @return mixed
     */
    public function getLon()
    {
        return $this->lon;
    }

    /**
     * @param string $set
     */
    public function setSet($set)
    {
        $this->set = $set;
    }

    /**
     * @return string
     */
    public function getSet()
    {
        return $this->set;
    }

    protected function moveLat($lat, $distance, $directionBearing)
    {
        $newLat = rad2deg(asin(sin(deg2rad($lat)) * cos($distance / $this->earthRadius) + cos(deg2rad($lat)) * sin($distance / $this->earthRadius) * cos(deg2rad($directionBearing))));
        return $newLat;
    }

    protected function moveLon($lon, $lat, $newLat, $distance, $directionBearing)
    {
        $newLon = rad2deg(deg2rad($lon) + atan2(sin(deg2rad($directionBearing)) * sin($distance / $this->earthRadius) * cos(deg2rad($lat)), cos($this->distance / $this->earthRadius) - sin(deg2rad($lat)) * sin(deg2rad($newLat))));
        return $newLon;
    }


    /**
     * @param mixed $maxLat
     */
    public function setMaxLat($maxLat)
    {
        $this->maxLat = $maxLat;
    }

    /**
     * @return mixed
     */
    public function getMaxLat()
    {
        if (is_null($this->maxLat)) {
            $this->maxLat = $this->moveLat($this->getLat(), $this->getDistance(), $this->maxBearing);
        }
        return $this->maxLat;
    }

    /**
     * @param mixed $maxLon
     */
    public function setMaxLon($maxLon)
    {
        $this->maxLon = $maxLon;
    }

    /**
     * @return mixed
     */
    public function getMaxLon()
    {
        if (is_null($this->maxLon)) {
            $this->maxLon = $this->moveLon($this->getLon(), $this->getLat(), $this->getMaxLat(), $this->getDistance(), $this->maxBearing);
        }
        return $this->maxLon;
    }

    /**
     * @param mixed $minLat
     */
    public function setMinLat($minLat)
    {
        $this->minLat = $minLat;
    }

    /**
     * @return mixed
     */
    public function getMinLat()
    {
        if (is_null($this->minLat)) {
            $this->minLat = $this->moveLat($this->getLat(), $this->getDistance(), $this->minBearing);
        }
        return $this->minLat;
    }

    /**
     * @param mixed $minLon
     */
    public function setMinLon($minLon)
    {
        $this->minLon = $minLon;
    }

    /**
     * @return mixed
     */
    public function getMinLon()
    {
        if (is_null($this->minLon)) {
            $this->minLon = $this->moveLon($this->getLon(), $this->getLat(), $this->getMinLat(), $this->getDistance(), $this->minBearing);
        }
        return $this->minLon;
    }

} 