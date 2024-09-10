<?php

namespace samuelreichoer\craftnoder\controllers;

use craft\elements\Entry;
use craft\web\Controller;
use samuelreichoer\craftnoder\services\JsonTransformerService;
use yii\web\Response;

class DefaultController extends Controller
{
  protected array|bool|int $allowAnonymous = true;

  public function actionGetPage(string $slug = 'home'): Response
  {
    $entry = Entry::find()
        ->slug($slug)
        ->one();

    $transformedJson = JsonTransformerService::transformEntry($entry);

    return $this->asJson($transformedJson);
  }
}
