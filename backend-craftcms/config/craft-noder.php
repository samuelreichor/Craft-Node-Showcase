<?php

return [
    'endpoints' => [
        'api/page/<siteId:\d+>/<slug>' => function($slug, $siteId) {
          return [
              'contentType' => 'application/json',
              'cache' => false,
              'criteria' => [
                  'slug' => $slug,
                  'siteId' => $siteId,
              ],
              'one' => true,
          ];
        },
    ],
];
