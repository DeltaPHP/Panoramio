<?php
/**
 * User: Vasiliy Shvakin (orbisnull) zen4dev@gmail.com
 */
return [
    "panoramio"              => function ($c) {
            $p = new \Panoramio\Model\Client();
            $am = $c["panaramioAuthorManager"];
            $p->setAuthorManager($am);
            return $p;
        },
    "panaramioAuthorManager" => function ($c) {
            $am = new \Panoramio\Model\AuthorManager();
            return $am;
        },
];