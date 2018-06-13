<?php
/**
 * Created by PhpStorm.
 * User: Portatil
 * Date: 11/06/2018
 * Time: 11:51
 */

namespace Totti619\Gallery\Libraries\Exceptions;


class MetaNotFoundException extends \Exception
{
    /**
     * @var string
     */
    protected $meta;

    /**
     * MetaNotFoundException constructor.
     * @param string $meta
     */
    public function __construct(string $meta)
    {
        parent::__construct(__('Can not find the metadata of the index \'' . $meta . '\'', ['meta', $meta]));
        $this->meta = $meta;
    }
}