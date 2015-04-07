<?php
return [
  ["full::/tpanoromio", function() {
      $p = new \Panoramio\Model\Client();
      $r = $p->createRequest(51.974722, 104.104167);
      $r->setDistanceInMetres(50);
      var_dump($p->getUrl($r));
      $images = $p->getImages($r,  $size = "medium", $order = "upload_date", $limit = 10);
      var_dump($images);
  }],
];