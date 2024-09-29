<?php

namespace samuelreichoer\craftnoder\services;

use Craft;
use craft\base\FieldInterface;
use craft\elements\Entry;
use craft\errors\InvalidFieldException;
use craft\fields\BaseRelationField;
use craft\fields\Matrix;
use yii\base\InvalidConfigException;

class ElementService
{
  /**
   * Get data in json of an entry based on siteId and slug
   *
   * @throws InvalidFieldException
   * @throws InvalidConfigException
   */
  public function getElement(int $siteId = 1, string $slug = 'home'): array
  {
    $cacheKey = 'cache_key_noder_'.$siteId.'_'.$slug;
    if ($result = Craft::$app->getCache()->get($cacheKey)) {
      return $result;
    }

    Craft::$app->getElements()->startCollectingCacheInfo();

    $eagerLoadingArr = $this->getEagerLoadingMap();
    $entry = Entry::find()
        ->siteId($siteId)
        ->with($eagerLoadingArr)
        ->slug($slug)
        ->one();

    $cacheInfo = Craft::$app->getElements()->stopCollectingCacheInfo();

    $transformer = new JsonTransformerService();
    $transformedData = $transformer->transformEntry($entry);

    Craft::$app->getCache()->set(
        $cacheKey,
        $transformedData,
        3600,
        $cacheInfo[0]
    );

    return $transformedData;
  }

  public function getEagerLoadingMap(): array
  {
    $mapKey = [];

    foreach (Craft::$app->getFields()->getAllFields() as $field) {
      if ($keys = $this->_getEagerLoadingMapForField($field)) {
        $mapKey[] = $keys;
      }
    }

    return array_merge(...$mapKey);
  }

  private function _getEagerLoadingMapForField(FieldInterface $field, ?string $prefix = null, int $iteration = 0): array
  {
    $keys = [];

    if ($field instanceof Matrix) {
      if ($iteration > 5) {
        return [];
      }

      $iteration++;

      // Because Matrix fields can be infinitely nested, we need to short-circuit things to prevent infinite looping.
      $keys[] = $prefix . $field->handle;

      foreach ($field->getEntryTypes() as $entryType) {
        foreach ($entryType->getCustomFields() as $subField) {
          $nestedKeys = $this->_getEagerLoadingMapForField($subField, $prefix . $field->handle . '.' . $entryType->handle . ':', $iteration);

          if ($nestedKeys) {
            $keys = array_merge($keys, $nestedKeys);
          }
        }
      }
    }

    if ($field instanceof BaseRelationField) {
      $keys[] = $prefix . $field->handle;
    }

    return $keys;
  }
}
