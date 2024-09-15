<?php

namespace samuelreichoer\craftnoder\controllers;

use Craft;
use craft\elementapi\DataEvent;
use craft\helpers\ArrayHelper;
use craft\web\Controller;
use ReflectionException;
use ReflectionFunction;
use samuelreichoer\craftnoder\Noder;
use yii\base\InvalidConfigException;
use yii\base\UserException;
use yii\web\HttpException;
use yii\web\JsonResponseFormatter;
use yii\web\Response;

class DefaultController extends Controller
{
  public const EVENT_BEFORE_SEND_DATA = 'beforeSendData';
  protected array|int|bool $allowAnonymous = true;

  public function actionIndex(string $pattern): Response
  {
    $callback = null;
    $jsonOptions = null;
    $pretty = false;
    $cache = true;
    $statusCode = 200;
    $statusText = null;

    try {
      $plugin = Noder::getInstance();
      $config = $plugin->getEndpoint($pattern);

      if (is_callable($config)) {
        $params = Craft::$app->getUrlManager()->getRouteParams();
        $config = $this->_callWithParams($config, $params);
      }

      if ($this->request->getIsOptions()) {
        $this->response->format = Response::FORMAT_RAW;
        return $this->response;
      }

      if (!is_array($config)) {
        throw new InvalidConfigException('The endpoint configuration must be an array.');
      }

      // Prevent API endpoints from getting indexed
      $this->response->getHeaders()->setDefault('X-Robots-Tag', 'none');

      // Before anything else, check the cache
      $cache = ArrayHelper::remove($config, 'cache', true);
      if ($this->request->getIsPreview() || $this->request->getIsLivePreview()) {
        // Ignore config & disable cache for live preview
        $cache = false;
      }

      $cacheKey = ArrayHelper::remove($config, 'cacheKey') ?? implode(':', [
          'craft-noder',
          Craft::$app->getSites()->getCurrentSite()->id,
          $this->request->getPathInfo(),
          $this->request->getQueryStringWithoutPath(),
      ]);

      if ($cache) {
        $cacheService = Craft::$app->getCache();
        if (($cachedContent = $cacheService->get($cacheKey)) !== false) {
          $this->response->format = Response::FORMAT_RAW;
          $this->response->content = $cachedContent;
          return $this->response;
        }
      }

      $elementsService = Craft::$app->getElements();
      $elementsService->startCollectingCacheInfo();

      $data = $config ?? [];

/*      Craft::dd($config);*/
      if ($this->hasEventHandlers(self::EVENT_BEFORE_SEND_DATA)) {
        $this->trigger(self::EVENT_BEFORE_SEND_DATA, new DataEvent(['payload' => $data]));
      }

    } catch (\Throwable $e) {
      $data = [
          'error' => [
              'code' => $e instanceof HttpException ? $e->statusCode : $e->getCode(),
              'message' => $e instanceof UserException ? $e->getMessage() : 'A server error occurred.',
          ],
      ];
      $statusCode = $e instanceof HttpException ? $e->statusCode : 500;
      $statusText = $e instanceof UserException ? preg_split('/[\r\n]/', $e->getMessage(), 2)[0] : 'Server error';

      Craft::error('Error resolving Element API endpoint: ' . $e->getMessage(), __METHOD__);
      Craft::$app->getErrorHandler()->logException($e);
    }

    $formatter = new JsonResponseFormatter([
        'contentType' => null,
        'useJsonp' => $callback !== null,
        'encodeOptions' => $jsonOptions ?? JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
        'prettyPrint' => $pretty,
    ]);

    if ($callback !== null) {
      $this->response->data = ['data' => $data, 'callback' => $callback];
    } else {
      $this->response->data = $data;
    }

    $formatter->format($this->response);
    $this->response->data = null;
    $this->response->format = Response::FORMAT_RAW;
    $this->response->setStatusCode($statusCode, $statusText);
    return $this->response;
  }

  /**
   * @throws ReflectionException
   */
  private function _callWithParams($func, $params)
  {
    if (empty($params)) {
      return $func();
    }

    $ref = new ReflectionFunction($func);
    $args = [];

    foreach ($ref->getParameters() as $param) {
      $name = $param->getName();
      $args[] = $params[$name] ?? ($param->isDefaultValueAvailable() ? $param->getDefaultValue() : null);
    }

    return $ref->invokeArgs($args);
  }
}

