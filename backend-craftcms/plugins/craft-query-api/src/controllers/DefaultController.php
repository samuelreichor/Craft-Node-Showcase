<?php

namespace samuelreichoer\queryapi\controllers;

use Craft;
use craft\web\Controller;
use Exception;
use samuelreichoer\queryapi\services\ElementQueryService;
use samuelreichoer\queryapi\services\JsonTransformerService;
use yii\web\Response;

class DefaultController extends Controller
{
  protected array|bool|int $allowAnonymous = true;

  /**
   * @throws Exception
   */
  public function actionGetCustomQueryResult(): Response
  {
    // Get request parameters
    $request = Craft::$app->getRequest();
    $params = $request->getQueryParams();
    $elementType = $request->getParam('elementType') ?? 'entries';

    // Instantiate the Query Service and handle query execution
    $queryService = new ElementQueryService();
    $result = $queryService->executeQuery($elementType, $params);

    // Instantiate the Transform Service and handle transforming different elementTypes
    $transformerService = new JsonTransformerService();
    $transformedData = $transformerService->executeTransform($result);

    $queryOne = isset($params['one']) && $params['one'] === '1';
    if ($queryOne) {
      return $this->asJson($transformedData[0]);
    }
    return $this->asJson($transformedData);
  }
}
