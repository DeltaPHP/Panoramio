<?php
/**
 * User: Vasiliy Shvakin (orbisnull) zen4dev@gmail.com
 */

namespace Panoramio\Parts;


use Panoramio\Model\Client;

interface PanaramioClientInterface
{

    /**
     * @param Client $panoramio
     */
    public function setPanoramio(Client $panoramio);

    /**
     * @return \Panoramio\Model\Client
     */
    public function getPanoramio();
}