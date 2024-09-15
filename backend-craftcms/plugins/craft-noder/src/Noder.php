<?php

namespace samuelreichoer\craftnoder;

use Craft;
use craft\base\Plugin;
use craft\events\RegisterCacheOptionsEvent;
use craft\utilities\ClearCaches;
use samuelreichoer\craftnoder\models\Settings;
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

  public function init(): void
  {
    parent::init();

    $this->initLogger();
    $this->attachEventHandlers();
  }

  private function attachEventHandlers(): void
  {
    Event::on(
        UrlManager::class,
        UrlManager::EVENT_REGISTER_SITE_URL_RULES,
        [$this, 'registerUrlRules']
    );

    Event::on(
        ClearCaches::class,
        ClearCaches::EVENT_REGISTER_TAG_OPTIONS,
        function(RegisterCacheOptionsEvent $event) {
          $event->options[] = [
              'tag' => 'craft-noder',
              'label' => Craft::t('craft-noder', 'Noder responses'),
          ];
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

  protected function createSettingsModel(): ?Settings
  {
    return new Settings();
  }

  public function getEndpoint(string $pattern)
  {
    return $this->getSettings()->endpoints[$pattern] ?? null;
  }

  public function registerUrlRules(RegisterUrlRulesEvent $event): void
  {
    foreach ($this->getSettings()->endpoints as $pattern => $config) {
      $event->rules[$pattern] = [
          'route' => 'craft-noder',
          'defaults' => ['pattern' => $pattern],
      ];
    }
  }
}
