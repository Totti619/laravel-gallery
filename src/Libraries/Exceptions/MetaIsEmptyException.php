<?php
/**
 * Created by PhpStorm.
 * User: Portatil
 * Date: 11/06/2018
 * Time: 11:54
 */

namespace Totti619\Gallery\Libraries\Exceptions;


class MetaIsEmptyException extends \Exception
{
    /**
     * @var string
     */
    protected $file;

    /**
     * MetaIsEmptyException constructor.
     * @param string $file
     */
    public function __construct(string $file)
    {
        parent::__construct(__('The file \'' . $file . '\' exists but is empty.', ['file', $file]));
    }
}