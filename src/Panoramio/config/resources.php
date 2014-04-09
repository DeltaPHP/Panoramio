<?php
/**
 * User: Vasiliy Shvakin (orbisnull) zen4dev@gmail.com
 */
return [
    "panoramio" => function ($c) {
            $p = new \Panoramio\Model\Client();
            return $p;
        },
];