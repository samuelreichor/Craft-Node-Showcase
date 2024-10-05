<?php
namespace samuelreichoer\queryapi\services;

use craft\elements\Address;
use craft\elements\Asset;
use craft\elements\Entry;
use craft\elements\User;
use Exception;

class ElementQueryService
{
  private array $allowedDefaultMethods = ['limit','id', 'status', 'offset', 'orderBy'];

  private array $allowedMethods = [
      'addresses' => ['addressLine1', 'addressLine2', 'addressLine3', 'locality', 'organization', 'fullName'],
      'assets' => ['volume', 'kind', 'filename'],
      'entries' => ['slug', 'section', 'postDate'],
      'users' => ['group', 'groupId', 'authorOf', 'email', 'fullName', 'hasPhoto'],
  ];

  /**
   * Handles the query execution for all element types.
   * @throws Exception
   */
  public function executeQuery(string $elementType, array $params): array
  {
    $query = $this->handleQuery($elementType, $params);

    $queryOne = isset($params['one']) && $params['one'] === '1';
    $queryAll = isset($params['all']) && $params['all'] === '1';

    if (!$queryAll && !$queryOne) {
      throw new Exception('No query was executed. This is usually because .one() or .all() is missing in the query');
    }

    $queriedData = $queryOne ? [$query->one()] : $query->all();

    return $queriedData;
  }

  /**
   * Handles building queries based on element type and parameters.
   * @throws Exception
   */
  public function handleQuery(string $elementType, array $params)
  {
    // Get the query object based on element type
    $query = match ($elementType) {
      'addresses' => Address::find(),
      'assets' => Asset::find(),
      'entries' => Entry::find(),
      'users' => User::find(),
      default => throw new Exception('Query for this element type is not yet implemented'),
    };

    $allowedMethods = $this->getAllowedMethods($elementType);

    return $this->applyParamsToQuery($query, $params, $allowedMethods);
  }

  /**
   * Apply parameters to the query based on allowed methods.
   */
  private function applyParamsToQuery($query, array $params, array $allowedMethods)
  {
    foreach ($params as $key => $value) {
      if (in_array($key, $allowedMethods)) {
        $query->$key($value);
      }
    }

    return $query;
  }

  /**
   * Returns the allowed methods for the given element type.
   *
   * @param string $elementType
   * @return array
   * @throws Exception
   */
  private function getAllowedMethods(string $elementType): array
  {
    if (!isset($this->allowedMethods[$elementType])) {
      throw new Exception('Unknown element type: ' . $elementType);
    }

    return array_merge($this->allowedDefaultMethods, $this->allowedMethods[$elementType]);
  }
}
