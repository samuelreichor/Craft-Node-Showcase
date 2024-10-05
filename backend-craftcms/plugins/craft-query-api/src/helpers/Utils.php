<?php

namespace samuelreichoer\queryapi\helpers;

use Craft;

class Utils
{
  public static function isPluginInstalledAndEnabled(string $pluginHandle): bool
  {
    $plugin = Craft::$app->plugins->getPlugin($pluginHandle, false);

    if ($plugin !== null && $plugin->isInstalled) return true;

    return false;
  }
}
