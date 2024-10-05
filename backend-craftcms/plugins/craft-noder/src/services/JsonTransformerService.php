<?php
namespace samuelreichoer\craftnoder\services;

use Exception;
use samuelreichoer\craftnoder\transformers\TransformerFactory;

class JsonTransformerService
{
  /**
   * Transforms an array of elements using the TransformerFactory.
   *
   * @param array $arrResult
   * @return array
   * @throws Exception
   */
  public function executeTransform(array $arrResult): array
  {
    return array_map(function ($element) {
      if(!$element) {
        return [];
      }
      $transformer = TransformerFactory::create($element);
      return $transformer->getTransformedData();
    }, $arrResult);
  }
}
