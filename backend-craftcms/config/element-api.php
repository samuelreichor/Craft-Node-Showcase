<?php

use craft\elements\Entry;

return [
    'endpoints' => [
        'v1/entry/<slug:{slug}>' => function($slug) {
            return [
                'contentType' => 'application/json',
                'elementType' => Entry::class,
                'cache' => false,
                'criteria' => [
                  'slug' => $slug,
                ],
                'one' => true,
                'transformer' => function(Entry $entry) {
                    return [
                        'sectionHandle' => $entry->section->handle,
                        'title' => $entry->title,
                        'text' => $entry->text,
                        'contentbuilder' => transformMatrixBlocks($entry->contentbuilder),
                    'cta' => transformMatrixBlocks($entry->cta),
                    ];
                },
            ];
        },
    ],
];

  // TODO: Add new endpoint to get all pages

  
function transformMatrixBlocks($matrixField)
{
    $blocksData = [];
    foreach ($matrixField->all() as $block) {
        $blockData = [
            'type' => $block->type->handle,
        ];

        foreach ($block->getFieldValues() as $fieldHandle => $fieldValue) {
            if ($fieldValue instanceof \craft\elements\db\AssetQuery) {
                $blockData[$fieldHandle] = transformAssets($fieldValue->all());
            } elseif ($fieldValue instanceof \craft\elements\db\EntryQuery) {
                $blockData[$fieldHandle] = transformEntries($fieldValue->all());
            } else {
                $blockData[$fieldHandle] = $fieldValue;
            }
        }

        $blocksData[] = $blockData;
    }

    return $blocksData;
}

function transformAssets($assets)
{
    $assetData = [];
    foreach ($assets as $asset) {
        $assetData[] = [
            'title' => $asset->title,
            'url' => $asset->getUrl(),
            'filename' => $asset->filename,
            'kind' => $asset->kind,
            'size' => $asset->size,
        ];
    }

    return $assetData;
}

function transformEntries($entries)
{
    $entryData = [];
    foreach ($entries as $entry) {
        $entryData[] = [
            'title' => $entry->title,
            'slug' => $entry->slug,
            'url' => $entry->url,
        ];
    }

    return $entryData;
}

  // TODO: Make own plugin and add better transformer for all fields
