<?php

use craft\elements\Entry;
use craft\helpers\UrlHelper;

return [
  'endpoints' => [
    '/api/home' => function() {
      return [
        'elementType' => Entry::class,
        'criteria' => ['section' => 'home'],
        'one' => true,
        'cache' => false,
        'transformer' => function(Entry $entry) {
          return [
            'sectionHandle' => $entry->section->handle,
            'id' => $entry->id,
            'title' => $entry->title,
            'url' => $entry->url,
            'introText' => $entry->text,
            'contentbuilder' => array_map(function($block) {
              $fields = $block->getSerializedFieldValues();
              return [
                  'type' => $block->type->handle,
                  'id' => $block->id,
                  'fields' => $fields,
              ];
            }, $entry->contentbuilder->all()),
            'cta' => [
              'fields' => $entry->cta->one()->getSerializedFieldValues(),
              ]
          ];
        },
      ];
    },
  ]
];
