<?php

namespace samuelreichoer\craftnoder\services;

use craft\elements\Asset;
use craft\elements\Entry;
use craft\errors\InvalidFieldException;
use yii\base\InvalidConfigException;

class JsonTransformerService
{

  /* How can transformers be extensible */
  /**
   * @throws InvalidFieldException
   */
  public static function transformEntry(Entry $entry): array
  {
    /* TODO: Add Caching */
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

  /**
   * @throws InvalidConfigException
   */
  private static function transformField($fieldValue, string $fieldClass): array|string
  {
    /* TODO: Decide what custom fields should be available */
    /* TODO: Add all possible field classes and transformer */

    /* Native Craft Fields: */
    /* Addresses Fields */
    /* Categories Fields */
    /* Link Fields */
    /* Tags Fields */
    /* Users Fields */
    return match ($fieldClass) {
      'craft\fields\Assets' => self::transformAssets($fieldValue->all()),
      'craft\fields\Matrix' => self::transformMatrixField($fieldValue),
      'craft\fields\Entries' => self::transformEntries($fieldValue->all()),
      default => $fieldValue,
    };
  }

  /**
   * @throws InvalidConfigException
   */
  private static function transformMatrixField($matrixField): array
  {
    $transformed = [];

    foreach ($matrixField->all() as $block) {
      $blockData = [
          'type' => $block->type->handle,
      ];

      foreach ($block->getFieldValues() as $fieldHandle => $fieldValue) {
        $class = get_class($block->getFieldLayout()->getFieldByHandle($fieldHandle));
        $blockData[$fieldHandle] = self::transformField($fieldValue, $class);
      }

      $transformed[] = $blockData;
    }

    return $transformed;
  }

  /**
   * @throws InvalidConfigException
   */
  private static function transformAssets(array $assets): array
  {
    $assetData = [];

    /* TODO: Add logic for ImagerX and native Craft images and return it in a flexible format for transformations */
    /* How does craft cms native image transforms work, how do i get different sizes etc. */
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

    /* How to define data that should be returned? */
    /* Maybe through a setting in the cp? */
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
