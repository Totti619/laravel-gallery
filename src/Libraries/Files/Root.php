<?php

namespace Totti619\Gallery\Libraries\Files;
use Totti619\Gallery\Libraries\Traits\SingletonTrait;

/**
 * Class Root. Singleton. Defines a root folder.
 * @package Totti619\Gallery\Libraries\Files
 */
class Root
{
    use SingletonTrait;
    /**
     * @var Folder
     */
    private $folder;

    /**
     * Root constructor.
     */
    private function __construct()
    {
        $this->folder = new Folder(config('gallery.gallery_path'));
    }

    /**
     * @return Folder
     */
    public function folder()
    {
        return $this->folder;
    }
}