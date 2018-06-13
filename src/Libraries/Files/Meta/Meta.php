<?php
namespace Totti619\Gallery\Libraries\Files\Meta;

use Totti619\Gallery\Libraries\Traits\SingletonTrait;

abstract class Meta
{
    /**
     * @var string
     */
    protected static $filename;

    /**
     * Meta constructor.
     */
    protected abstract function __construct();

    /**
     * @param string|null $relativePath
     * @return \stdClass|array
     */
    public abstract function get(string $relativePath = null);

    /**
     * @param array $new
     * @return bool
     */
    public abstract function register(array $new): bool;

    /**
     * @param bool $assoc
     * @return mixed
     */
    public abstract function read(bool $assoc = true);

    /**
     * @param array $meta
     * @return mixed
     */
    public abstract function write(array $meta);

    /**
     * @param string $relativePath
     * @param bool   $ignoreGeneratedMeta
     * @return string
     */
    public abstract function toHTMLAttr(string $relativePath, bool $ignoreGeneratedMeta = true): string;
}