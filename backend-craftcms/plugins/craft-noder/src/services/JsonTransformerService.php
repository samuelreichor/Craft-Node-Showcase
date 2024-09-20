<?php

namespace samuelreichoer\craftnoder\services;

use craft\elements\Address;
use craft\elements\Asset;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\Tag;
use craft\elements\User;
use craft\errors\InvalidFieldException;
use craft\fieldlayoutelements\users\PhotoField;
use craft\fields\data\LinkData;
use samuelreichoer\craftnoder\helpers\Utils;
use yii\base\InvalidConfigException;

class JsonTransformerService
{
  /**
   * Transforms an Entry into an array.
   *
   * @param Entry $entry
   * @return array
   * @throws InvalidFieldException|InvalidConfigException
   */
  public function transformEntry(Entry $entry): array
  {
    $transformedFields = $this->getTransformedFields($entry);

    return array_merge([
        'metadata' => $this->getMetadata($entry),
        'sectionHandle' => $entry->section->handle,
        'title' => $entry->title,
    ], $transformedFields);
  }

  /**
   * Retrieves and transforms custom fields from the context.
   *
   * @param Entry|Category|User|Asset $context
   * @return array
   * @throws InvalidFieldException|InvalidConfigException
   */
  private function getTransformedFields(Entry|Category|User|Asset $context): array
  {
    $fieldLayout = $context->getFieldLayout();
    $fields = $fieldLayout ? $fieldLayout->getCustomFields() : [];
    $nativeFields = $fieldLayout ? $fieldLayout->getAvailableNativeFields() : [];
    $transformedFields = [];

    $filteredOutClasses = [
        'nystudio107\seomatic\fields\SeoSettings',
    ];

    // TODO: Add settings to add additional filter out classes

    // Get native fields and there transformed values
    if ($nativeFields) {
      foreach ($nativeFields as $nativeField) {
        $fieldHandle = $nativeField->attribute; //native fields have no handles
        $fieldValue = $context->$fieldHandle;
        $fieldClass = get_class($nativeField);

        if (in_array($fieldClass, $filteredOutClasses, true)) {
          continue;
        }

        $transformedFields[$fieldHandle] = $this->transformNativeField($fieldValue, $fieldClass);
      }
    }

    // Get custom fields and there transformed values
    foreach ($fields as $field) {
      $fieldHandle = $field->handle;
      $fieldValue = $context->getFieldValue($fieldHandle);
      $fieldClass = get_class($field);

      if (in_array($fieldClass, $filteredOutClasses, true)) {
        continue;
      }

      $transformedFields[$fieldHandle] = $this->transformCustomField($fieldValue, $fieldClass);
    }

    return $transformedFields;
  }


  /**
   * Transforms a custom field based on its class.
   *
   * @param mixed $fieldValue
   * @param string $fieldClass
   * @return mixed
   * @throws InvalidFieldException|InvalidConfigException
   */
  private function transformCustomField(mixed $fieldValue, string $fieldClass): mixed
  {
    if (!$fieldValue || !$fieldClass) {
      return [];
    }

    return match ($fieldClass) {
      'craft\fields\Assets' => $this->transformAssets($fieldValue->all()),
      'craft\fields\Matrix' => $this->transformMatrixField($fieldValue->all()),
      'craft\fields\Entries' => $this->transformEntries($fieldValue->all()),
      'craft\fields\Users' => $this->transformUsers($fieldValue->all()),
      'craft\fields\Addresses' => $this->transformAddresses($fieldValue->all()),
      'craft\fields\Categories' => $this->transformCategories($fieldValue->all()),
      'craft\fields\Tags' => $this->transformTags($fieldValue->all()),
      'craft\fields\Link' => $this->transformLinks($fieldValue),
      default => $fieldValue,
    };
  }

  /**
   * Transforms a native field based on its class. This is needed because native fields have
   * different field classes.
   *
   * @param mixed $fieldValue
   * @param string $fieldClass
   * @return mixed
   */
  private function transformNativeField(mixed $fieldValue, string $fieldClass): mixed
  {
    if (!$fieldValue || !$fieldClass) {
      return [];
    }

    return match ($fieldClass) {
      'craft\fieldlayoutelements\users\PhotoField' => $this->transformAssets([$fieldValue]),
      default => $fieldValue,
    };
  }

  /**
   * Retrieves metadata from an Entry.
   *
   * @param Entry $entry
   * @return array
   */
  private function getMetadata(Entry $entry): array
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
   * Transforms Matrix fields.
   *
   * @param Entry[] $matrixFields
   * @return array
   * @throws InvalidFieldException|InvalidConfigException
   */
  private function transformMatrixField(array $matrixFields): array
  {
    if (!$matrixFields) {
      return [];
    }

    $transformedData = [];

    foreach ($matrixFields as $block) {
      $blockData = [
          'type' => $block->type->handle,
      ];

      foreach ($block->getFieldValues() as $fieldHandle => $fieldValue) {
        $field = $block->getFieldLayout()->getFieldByHandle($fieldHandle);
        $fieldClass = get_class($field);
        $blockData[$fieldHandle] = $this->transformCustomField($fieldValue, $fieldClass);
      }

      $transformedData[] = $blockData;
    }

    return $transformedData;
  }

  /**
   * Transforms an array of Asset elements.
   *
   * @param Asset[] | PhotoField[] $assets
   * @return array
   */
  private function transformAssets(array $assets): array
  {
    if (empty($assets)) {
      return [];
    }

    $imageMode = Utils::isPluginInstalledAndEnabled('imager-x') ? 'imagerx' : 'craft';
    $imageTransformerService = new ImageTransformerService();
    $transformedData = [];

    foreach ($assets as $asset) {
      $generalFieldData = $this->getTransformedFields($asset) ?? [];

      $imageData = $imageMode === 'imagerx'
          ? $imageTransformerService->imagerXTransformer($asset)
          : $imageTransformerService->defaultImageTransformer($asset);

      $transformedData[] = array_merge($generalFieldData, $imageData);
    }

    return $transformedData;
  }

  /**
   * Transforms an array of Entry elements.
   *
   * @param Entry[] $entries
   * @return array
   */
  private function transformEntries(array $entries): array
  {
    if (empty($entries)) {
      return [];
    }

    $transformedData = [];

    foreach ($entries as $entry) {
      $transformedData[] = [
          'title' => $entry->title,
          'slug' => '/' . $entry->slug,
          'url' => $entry->url,
          'id' => $entry->id,
      ];
    }

    return $transformedData;
  }

  /**
   * Transforms an array of User elements.
   *
   * @param User[] $users
   * @return array
   * @throws InvalidFieldException|InvalidConfigException
   */
  private function transformUsers(array $users): array
  {
    if (empty($users)) {
      return [];
    }

    $transformedData = [];

    foreach ($users as $user) {
      $transformedData[] = $this->getTransformedFields($user);
    }

    return $transformedData;
  }

  /**
   * Transforms an array of Address elements.
   *
   * @param Address[] $addresses
   * @return array
   */
  private function transformAddresses(array $addresses): array
  {
    if (empty($addresses)) {
      return [];
    }

    $transformedData = [];

    foreach ($addresses as $address) {
      $transformedData[] = [
          'title' => $address->title ?? '',
          'addressLine1' => $address->addressLine1 ?? '',
          'addressLine2' => $address->addressLine2 ?? '',
          'addressLine3' => $address->addressLine3 ?? '',
          'countryCode' => $address->countryCode ?? '',
          'locality' => $address->locality ?? '',
          'postalCode' => $address->postalCode ?? '',
      ];
    }

    return $transformedData;
  }

  /**
   * Transforms an array of Category elements.
   *
   * @param Category[] $categories
   * @return array
   * @throws InvalidFieldException|InvalidConfigException
   */
  private function transformCategories(array $categories): array
  {
    if (empty($categories)) {
      return [];
    }

    $transformedData = [];

    foreach ($categories as $category) {
      $transformedFields = $this->getTransformedFields($category);

      $transformedData[] = array_merge([
          'slug' => $category->slug,
          'id' => $category->id,
      ], $transformedFields);
    }

    return $transformedData;
  }

  /**
   * Transforms an array of Tag elements.
   *
   * @param Tag[] $tags
   * @return array
   */
  private function transformTags(array $tags): array
  {
    if (empty($tags)) {
      return [];
    }

    $transformedData = [];

    foreach ($tags as $tag) {
      $transformedData[] = [
          'title' => $tag->title,
          'slug' => $tag->slug,
      ];
    }

    return $transformedData;
  }

  /**
   * Transforms an link.
   *
   * @param LinkData $link
   * @return array
   */
  private function transformLinks(LinkData $link): array
  {
    if (empty($link)) {
      return [];
    }

    return [
        'elementType' => $link->type,
        'value' => $link->value,
    ];
  }
}
