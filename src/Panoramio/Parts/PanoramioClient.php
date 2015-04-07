<?php
/**
 * User: Vasiliy Shvakin (orbisnull) zen4dev@gmail.com
 */

namespace Panoramio\Parts;

use Panoramio\Model\Client;

trait PanoramioClient
{
    /**
     * @var Client
     */
    protected $panoramio;

    /**
     * @param \Panoramio\Model\Client $panoramio
     */
    public function setPanoramio(Client $panoramio)
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

}