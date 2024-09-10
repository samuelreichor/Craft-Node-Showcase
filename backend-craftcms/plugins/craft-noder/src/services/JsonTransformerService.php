<?php

namespace samuelreichoer\craftnoder\services;

use craft\elements\db\AssetQuery;
use craft\elements\db\EntryQuery;
use craft\elements\Entry;
use craft\fields\Matrix;

class JsonTransformerService
{
  public static function transformEntry(Entry $entry): array
  {
    return [
        'metadata' => self::getMetadata($entry),
        'sectionHandle' => $entry->section->handle,
        'title' => $entry->title,
        'text' => $entry->text,
        'contentbuilder' => self::transformMatrixField($entry->contentbuilder),
        'cta' => self::transformMatrixField($entry->cta),
    ];
  }

  private static function getMetadata(Entry $entry): array
  {
    return [
        'sectionId' => $entry->sectionId,
        'postDate' => $entry->postDate,
        'id' => $entry->id,
        'siteId' => $entry->siteId,
        'slug' => $entry->slug,
        'uri' => $entry->uri,
        'dateCreated' => $entry->dateCreated,
        'dateUpdated' => $entry->dateUpdated,
        'cpEditUrl' => $entry->cpEditUrl,
        'status' => $entry->status,
        'url' => $entry->url,
    ];
  }

  private static function transformMatrixField($matrixField): array
  {
    $transformed = [];

    foreach ($matrixField->all() as $block) {
      $blockData = [
          'type' => $block->type->handle,
      ];

      foreach ($block->getFieldValues() as $fieldHandle => $fieldValue) {
        $blockData[$fieldHandle] = self::transformField($fieldValue);
      }

      $transformed[] = $blockData;
    }

    return $transformed;
  }

  private static function transformField($fieldValue)
  {
    if (is_array($fieldValue)) {
      return array_map([self::class, 'transformField'], $fieldValue);
    } elseif ($fieldValue instanceof AssetQuery) {
      return self::transformAssets($fieldValue->all());
    } elseif ($fieldValue instanceof Matrix) {
      return self::transformMatrixField($fieldValue);
    } elseif ($fieldValue instanceof EntryQuery) {
      return self::transformEntries($fieldValue->all());
    }

    return $fieldValue;
  }

  private static function transformAssets($assets)
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

  private static function transformEntries($entries)
  {
    $entryData = [];
    foreach ($entries as $entry) {
      $entryData[] = [
          'title' => $entry->title,
          'slug' => '/'.$entry->slug,
          'url' => $entry->url,
      ];
    }

    return $entryData;
  }
}

