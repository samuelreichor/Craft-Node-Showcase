<?php

namespace samuelreichoer\craftnoder\models;

use craft\base\Model;

class Settings extends Model
{
    /**
     * @var callable|array The default endpoint configuration.
     */
    public $defaults = [];

    /**
     * @var array The endpoint configurations.
     */
    public array $endpoints = [];

    /**
     * Returns the default endpoint configuration.
     *
     * @return array The default endpoint configuration.
     */
    public function getDefaults(): array
    {
        return is_callable($this->defaults) ? call_user_func($this->defaults) : $this->defaults;
    }
}
