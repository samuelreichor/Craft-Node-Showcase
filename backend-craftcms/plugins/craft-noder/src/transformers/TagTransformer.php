<?php

namespace samuelreichoer\craftnoder\transformers;

use craft\elements\Tag;

class TagTransformer extends BaseTransformer
{
  private Tag $tag;

  public function __construct(Tag $tag)
  {
    parent::__construct($tag);
    $this->tag = $tag;
  }

  /**
   * @return array
   */
  public function getTransformedData(): array
  {
    return [
        'metadata' => $this->getMetaData(),
        'title' => $this->tag->title,
        'slug' => $this->tag->slug,
    ];
  }

  /**
   * Retrieves metadata from the Tag.
   *
   * @return array
   */
  protected function getMetaData(): array
  {
    return [
        'id' => $this->tag->id,
        'dateCreated' => $this->tag->dateCreated,
        'dateUpdated' => $this->tag->dateUpdated,
    ];
  }
}
