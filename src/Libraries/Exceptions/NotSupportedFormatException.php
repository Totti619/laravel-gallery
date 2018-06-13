<?php
/**
 * Created by PhpStorm.
 * User: Portatil
 * Date: 06/06/2018
 * Time: 16:29
 */

namespace Totti619\Gallery\Libraries\Exceptions;


class NotSupportedFormatException extends \Exception
{

    /**
     * @var string
     */
    protected $format;

    /**
     * NotSupportedFormatException constructor.
     * @param string $format
     */
    public function __construct(string $format)
    {
        parent::__construct(__('The gallery does not support the \':format\' format'), ['format', $format]);
        $this->format = $format;
    }
}