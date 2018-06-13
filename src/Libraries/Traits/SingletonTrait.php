<?php
namespace Totti619\Gallery\Libraries\Traits;

use stdClass;

trait SingletonTrait
{
    /**
     * @var mixed
     */
    protected static $instance;

    /**
     * @return mixed
     */
    public static function getInstance()
    {
        if(!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}