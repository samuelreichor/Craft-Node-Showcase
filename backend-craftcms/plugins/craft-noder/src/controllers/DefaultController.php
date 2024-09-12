<?php

namespace samuelreichoer\craftnoder\controllers;

use craft\elements\Entry;
use craft\web\Controller;
use samuelreichoer\craftnoder\services\JsonTransformerService;
use yii\web\Response;

class DefaultController extends Controller
{
  protected array|bool|int $allowAnonymous = true;

  public function actionGetPage(int $siteId = 1, string $slug = 'home'): Response
  {
    $entry = Entry::find()
        ->siteId($siteId)
        ->slug($slug)
        ->one();

    $transformedJson = JsonTransformerService::transformEntry($entry);

    return $this->asJson($transformedJson);
  }
  /* TODO: Add endpoint for seo stuff / add seo Settings to the action get page controller */
  /* Should be extensible with custom endpoints but how? */
}
