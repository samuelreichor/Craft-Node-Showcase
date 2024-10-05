<?php

namespace samuelreichoer\craftnoder\transformers;

use craft\elements\Entry;
use craft\errors\InvalidFieldException;
use yii\base\InvalidConfigException;

class EntryTransformer extends BaseTransformer
{
  protected Entry $entry;

  public function __construct(Entry $entry)
  {
    parent::__construct($entry);
    $this->entry = $entry;
  }

  /**
   * Transforms the Entry element into an array.
   *
   * @return array
   * @throws InvalidFieldException|InvalidConfigException
   */
  public function getTransformedData(): array
  {
    $transformedFields = $this->getTransformedFields();
    $metaData = $this->getMetaData();

    $data = [
        'metadata' => $metaData,
        'title' => $this->entry->title,
    ];

    // Not every entry has a section (matrix blocks)
    if ($this->entry->section && isset($this->entry->section->handle)) {
      $data['sectionHandle'] = $this->entry->section->handle;
    }

    return array_merge($data, $transformedFields);
  }


  /**
   * Retrieves metadata from the Entry.
   *
   * @return array
   */
  protected function getMetaData(): array
  {
    return array_merge(parent::getMetaData(), [
        'sectionId' => $this->entry->sectionId,
        'postDate' => $this->entry->postDate,
        'siteId' => $this->entry->siteId,
        'slug' => $this->entry->slug,
        'uri' => $this->entry->uri,
        'cpEditUrl' => $this->entry->cpEditUrl,
        'status' => $this->entry->status,
        'url' => $this->entry->url,
    ]);
  }
}
