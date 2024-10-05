<?php

namespace samuelreichoer\craftnoder;

use Craft;
use craft\base\Plugin;
use yii\log\FileTarget;
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
use yii\base\Event;

/**
 * craft-noder plugin
 *
 * @method static Noder getInstance()
 * @author Samuel Reichör <samuelreichor@gmail.com>
 * @copyright Samuel Reichör
 * @license MIT
 */
class Noder extends Plugin
{
  public string $schemaVersion = '1.0.0';

  public static function config(): array
  {
    return [
        'components' => [
          // Define component configs here...
        ],
    ];
  }

  public function init(): void
  {
    parent::init();

    $this->initLogger();
    $this->attachEventHandlers();

    // Any code that creates an element query or loads Twig should be deferred until
    // after Craft is fully initialized, to avoid conflicts with other plugins/modules
    Craft::$app->onInit(function () {
      // ...
    });
  }

  private function attachEventHandlers(): void
  {
    // Register event handlers here ...
    // (see https://craftcms.com/docs/5.x/extend/events.html to get started)

    Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_SITE_URL_RULES,
        function(RegisterUrlRulesEvent $event) {
          $event->rules = array_merge($event->rules, [
              'GET /v1/api/entry/<siteId:\d+>/<slug>' => 'craft-noder/default/get-entry',
              'GET /v1/api/customQuery' => 'craft-noder/default/get-custom-query-result',
          ]);
        }
    );
  }

  private function initLogger(): void
  {
    $logFileTarget = new FileTarget([
        'logFile' => '@storage/logs/noder.log',
        'maxLogFiles' => 10,
        'categories' => ['noder'],
        'logVars' => [],
    ]);
    Craft::getLogger()->dispatcher->targets[] = $logFileTarget;
  }
}
