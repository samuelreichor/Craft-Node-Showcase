<?php

namespace samuelreichoer\craftnoder\services;

use Craft;
use craft\elements\Asset;
use craft\errors\ImageTransformException;

class ImageTransformerService
{
  public function defaultImageTransformer(Asset $asset): array
  {
    return $this->getDefaultData($asset);
  }

  public function imagerXTransformer(Asset $asset): array
  {

    $defaults = $this->getDefaultData($asset);

    return [
        ...$defaults,
        'srcSets' => $this->getAllAvailableSrcSets($asset),
    ];
  }

  protected function getDefaultData(Asset $asset): array
  {
    return [
        'metaData' => $this->getMetaData($asset),
        'height' => $asset->height,
        'width' => $asset->width,
        'focalPoint' => $asset->getFocalPoint(),
        'url' => $asset->getUrl(),
    ];
  }

  /**
   * @throws ImageTransformException
   */
  protected function getMetaData(Asset $asset): array
  {
    return [
        'id' => $asset->id,
        'filename' => $asset->filename,
        'kind' => $asset->kind,
        'size' => $asset->size,
        'mimeType' => $asset->getMimeType(),
        'extension' => $asset->extension,
        'cpEditUrl' => $asset->cpEditUrl,
    ];
  }

  public function getAllAvailableSrcSets(Asset $asset): array
  {
    $transforms = $this->getTransformKeys();
    $imagerx = Craft::$app->plugins->getPlugin('imager-x');
    $srcSetArr = [];

    foreach ($transforms as $transform) {
      $transformedImages = $imagerx->imager->transformImage($asset, $transform);
      $srcSetArr[$transform] = $imagerx->imager->srcset($transformedImages);
    }

    return $srcSetArr;
  }

  protected function getTransformKeys(): array|null
  {
    $configPath = Craft::getAlias('@config/imager-x-transforms.php');

    if (!file_exists($configPath)) {
      return null;
    }

    $transforms = include($configPath);

    if (!$transforms) {
      return null;
    }

    return array_keys($transforms);
  }
}
