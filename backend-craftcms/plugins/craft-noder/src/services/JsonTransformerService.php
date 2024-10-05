<?php
namespace samuelreichoer\craftnoder\services;

use craft\elements\Address;
use craft\elements\Asset;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\Tag;
use craft\elements\User;
use Exception;
use samuelreichoer\craftnoder\transformers\BaseTransformer;
use samuelreichoer\craftnoder\transformers\EntryTransformer;
use samuelreichoer\craftnoder\transformers\AssetTransformer;
use samuelreichoer\craftnoder\transformers\UserTransformer;
use samuelreichoer\craftnoder\transformers\AddressTransformer;
use samuelreichoer\craftnoder\transformers\CategoryTransformer;
use samuelreichoer\craftnoder\transformers\TagTransformer;

class JsonTransformerService
{
  /**
   * Transforms an array of elements using the appropriate transformers.
   *
   * @param array $arrResult
   * @return array
   * @throws Exception
   */
  public function executeTransform(array $arrResult): array
  {
    return array_map(function ($element) {
      if (!$element) {
        return [];
      }
      $transformer = $this->getTransformerForElement($element);
      return $transformer->getTransformedData();
    }, $arrResult);
  }

  /**
   * Determines the appropriate transformer for the given element.
   *
   * @param mixed $element
   * @return BaseTransformer
   * @throws Exception
   */
  private function getTransformerForElement(mixed $element): BaseTransformer
  {
    return match (true) {
      $element instanceof Entry => new EntryTransformer($element),
      $element instanceof Asset => new AssetTransformer($element),
      $element instanceof User => new UserTransformer($element),
      $element instanceof Address => new AddressTransformer($element),
      $element instanceof Category => new CategoryTransformer($element),
      $element instanceof Tag => new TagTransformer($element),
      default => throw new Exception('Unsupported element type'),
    };
  }
}
