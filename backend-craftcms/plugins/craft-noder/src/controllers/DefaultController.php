<?php

namespace samuelreichoer\craftnoder\controllers;

use craft\errors\InvalidFieldException;
use craft\web\Controller;
use samuelreichoer\craftnoder\services\ElementService;
use yii\base\InvalidConfigException;
use yii\web\Response;

class DefaultController extends Controller
{
  protected array|bool|int $allowAnonymous = true;

  /**
   * Get data in json of an entry based on siteId and slug
   *
   * @throws InvalidFieldException
   * @throws InvalidConfigException
   */
  public function actionGetEntry(int $siteId = 1, string $slug = 'home'): Response
  {
    $elementService = new ElementService();
    return $this->asJson($elementService->getElement($siteId, $slug));
  }
  /* TODO: Add endpoint for seo stuff / add seo Settings to the action get page controller */
  /* Should be extensible with custom endpoints but how? */
}
