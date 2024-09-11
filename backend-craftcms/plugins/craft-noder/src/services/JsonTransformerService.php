<?php

namespace samuelreichoer\craftnoder\services;

use craft\elements\Asset;
use craft\elements\Entry;
use craft\errors\InvalidFieldException;

class JsonTransformerService
{
  /**
   * @throws InvalidFieldException
   */
  public static function transformEntry(Entry $entry): array
  {

    $fieldLayout = $entry->getFieldLayout();
    $fields = $fieldLayout ? $fieldLayout->getCustomFields() : [];

    $transformedFields = [];

    foreach ($fields as $field) {
      $fieldHandle = $field->handle;
      $fieldValue = $entry->getFieldValue($fieldHandle);
      $fieldClass = get_class($field);
      $transformedFields[$fieldHandle] = self::transformField($fieldValue, $fieldClass);
    }

    return array_merge([
        'metadata' => self::getMetadata($entry),
        'sectionHandle' => $entry->section->handle,
        'title' => $entry->title,
    ], $transformedFields);
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

  private static function transformField($fieldValue, string $fieldClass)
  {
    switch ($fieldClass) {
      case 'craft\fields\Assets':
        return self::transformAssets($fieldValue->all());

      case 'craft\fields\Matrix':
        return self::transformMatrixField($fieldValue);

      case 'craft\fields\Entries':
        return self::transformEntries($fieldValue->all());

      default:
        return $fieldValue;
    }
  }

  private static function transformMatrixField($matrixField): array
  {
    $transformed = [];

    foreach ($matrixField->all() as $block) {
      $blockData = [
          'type' => $block->type->handle,
      ];

      foreach ($block->getFieldValues() as $fieldHandle => $fieldValue) {
        $blockData[$fieldHandle] = self::transformField($fieldValue, get_class($block->getFieldLayout()->getFieldByHandle($fieldHandle)));
      }

      $transformed[] = $blockData;
    }

    return $transformed;
  }

  private static function transformAssets(array $assets): array
  {
    $assetData = [];

    foreach ($assets as $asset) {
      if ($asset instanceof Asset) {
        $assetData[] = [
            'title' => $asset->title,
            'url' => $asset->getUrl(),
            'filename' => $asset->filename,
            'kind' => $asset->kind,
            'size' => $asset->size,
        ];
      }
    }

    return $assetData;
  }

  private static function transformEntries(array $entries): array
  {
    $entryData = [];

    foreach ($entries as $entry) {
      $entryData[] = [
          'title' => $entry->title,
          'slug' => '/' . $entry->slug,
          'url' => $entry->url,
      ];
    }

    return $entryData;
  }
}
