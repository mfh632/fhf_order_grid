<?php

namespace FHF\OrderGrid;

/**
 * Class BaseComponent
 * @package FHF\OrderGrid
 */
abstract class BaseComponent
{
    public static function create_instance()
    {
        return (new static());
    }
}