<?php

namespace samuelreichoer\craftnoder\transformers;

use craft\elements\Address;
use craft\elements\Asset;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\Tag;
use craft\elements\User;
use yii\base\InvalidArgumentException;

class TransformerFactory
{
  /**
   * Creates a transformer instance based on the element type.
   *
   * @param mixed $element
   * @return EntryTransformer|AssetTransformer|UserTransformer|AddressTransformer|CategoryTransformer|TagTransformer
   */
  public static function create(mixed $element): EntryTransformer|AssetTransformer|UserTransformer|AddressTransformer|CategoryTransformer|TagTransformer
  {
    if ($element instanceof Entry) {
      return new EntryTransformer($element);
    } elseif ($element instanceof Asset) {
      return new AssetTransformer($element);
    } elseif ($element instanceof User) {
      return new UserTransformer($element);
    } elseif ($element instanceof Address) {
      return new AddressTransformer($element);
    } elseif ($element instanceof Category) {
      return new CategoryTransformer($element);
    } elseif ($element instanceof Tag) {
      return new TagTransformer($element);
    } else {
      throw new InvalidArgumentException('Unsupported element type');
    }
  }
}
