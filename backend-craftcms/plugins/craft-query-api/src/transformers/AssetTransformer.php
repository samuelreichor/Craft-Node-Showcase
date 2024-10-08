<?php

namespace samuelreichoer\queryapi\transformers;

use Craft;
use craft\elements\Asset;
use craft\errors\ImageTransformException;
use samuelreichoer\queryapi\helpers\Utils;
use yii\base\InvalidConfigException;

class AssetTransformer extends BaseTransformer
{
  private Asset $asset;

  public function __construct(Asset $asset)
  {
    parent::__construct($asset);
    $this->asset = $asset;
  }

  /**
   * @return array
   * @throws ImageTransformException
   * @throws InvalidConfigException
   */
  public function getTransformedData(): array
  {
    $imageMode = Utils::isPluginInstalledAndEnabled('imager-x') ? 'imagerX' : 'craft';

    if ($imageMode === 'imagerX') {
      return $this->imagerXTransformer();
    }

    return $this->defaultImageTransformer();
  }

  /**
   * @return array
   * @throws InvalidConfigException
   * @throws ImageTransformException
   */
  private function defaultImageTransformer(): array
  {
    $transformedFields = $this->getTransformedFields();
    return array_merge([
        'metadata' => $this->getMetaData(),
        'height' => $this->asset->height,
        'width' => $this->asset->width,
        'focalPoint' => $this->asset->getFocalPoint(),
        'url' => $this->asset->getUrl(),
    ], $transformedFields);
  }

  /**
   * @return array
   * @throws ImageTransformException
   * @throws InvalidConfigException
   */
  private function imagerXTransformer(): array
  {
    $data = $this->defaultImageTransformer();
    $data['srcSets'] = $this->getAllAvailableSrcSets();

    return $data;
  }

  /**
   * @return array
   * @throws ImageTransformException
   */
  protected function getMetaData(): array
  {
    return [
        'id' => $this->asset->id,
        'filename' => $this->asset->filename,
        'kind' => $this->asset->kind,
        'size' => $this->asset->size,
        'mimeType' => $this->asset->getMimeType(),
        'extension' => $this->asset->extension,
        'cpEditUrl' => $this->asset->cpEditUrl,
        'dateCreated' => $this->asset->dateCreated,
        'dateUpdated' => $this->asset->dateUpdated,
    ];
  }

  /**
   * @return array
   */
  private function getAllAvailableSrcSets(): array
  {
    $transforms = $this->getTransformKeys();
    $imagerx = Craft::$app->plugins->getPlugin('imager-x');
    $srcSetArr = [];

    foreach ($transforms as $transform) {
      $transformedImages = $imagerx->imager->transformImage($this->asset, $transform);
      $srcSetArr[$transform] = $imagerx->imager->srcset($transformedImages);
    }

    return $srcSetArr;
  }

  /**
   * @return array
   */
  private function getTransformKeys(): array
  {
    $configPath = Craft::getAlias('@config/imager-x-transforms.php');

    if (!file_exists($configPath)) {
      return [];
    }

    $transforms = include $configPath;

    return $transforms ? array_keys($transforms) : [];
  }
}
