<?php

namespace Totti619\Gallery\Libraries\Files;

/**
 * Class Folder. "Everything is a file"
 * @package Totti619\Gallery\Libraries\Files
 */
class Folder extends File implements \Iterator
{
    /**
     * @var array
     */
    protected $files = [];

    protected $position = 0;

    /**
     * Folder constructor.
     * @param string|null     $path
     * @param Folder|null $parent
     */
    public function __construct(string $path = null, Folder $parent = null)
    {
        if(is_null($parent) && is_null($path)) {
            $parent = Root::getInstance()->folder();
            $path = $parent->getPath();
        }
        parent::__construct($path, $parent);
        $this->position = 0;
        $this->retrieveChildren();
    }

    /**
     * Fetch the elements array with files and folders.
     */
    private function retrieveChildren()
    {
        $glob = glob($this->path . '/*');
        $folders = [];
        $files = [];
        foreach ($glob as $filename) { // get the files and folders paths
            $realpath = realpath($filename);
            if(is_dir($realpath)) {
                $folders[] = $realpath;
            } else {
                $files[] = $realpath;
            }
        }

        // Fetch elements with folders first.
        foreach ($folders as $folder)
            $this->files[] = new Folder($folder, $this);

        // Then fetch it with files.
        foreach ($files as $file) {
            $fileType = File::type($file);
            $this->files[] = new $fileType($file, $this);
        }
    }

    /**
     * @param null|string $namespace
     * @return array
     */
    public function children($namespace = null): array
    {
        $return = [];
        if(!is_null($namespace)) {
            foreach ($this->files as $file) {
                if($file instanceof $namespace)
                    $return[] = $file;
            }
            return $return;
        }
        return $this->files;
    }

    /**
     * @return array
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @param array $files
     */
    public function setFiles(array $files)
    {
        $this->files = $files;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition(int $position)
    {
        $this->position = $position;
    }


    /**
     * @param string     $relativePath
     * @param array|null $relativePathExplode
     * @return bool|File
     */
    public function get(string $relativePath, array $relativePathExplode = null)
    {
        $relativePathExplode = $relativePathExplode ?? explode(config('gallery.alternate_separator'),  $relativePath);

//        dd($relativePathExplode);

        foreach ($this->files as $file)
        {
            if($file->getName() === $relativePathExplode[0]) {
                if(count($relativePathExplode) > 1) {
                    return $file->get($relativePath, array_slice($relativePathExplode, 1));
                }
                return $file;
            }
        }
        return false;
    }

    public function folders(): array
    {
        return $this->children(self::class);
    }
    public function images(): array
    {
        return $this->children(Image::class);
    }



    /**
     * @param File|array $elements
     */
    public function add($elements)
    {
        if(is_array($elements))
            array_merge($this->getElements(), $elements);
        elseif ($elements instanceof File)
            $this->getElements()[] = $elements;
    }

    /**
     * @param string|null $namespace
     * @return int
     */
    public function numberOf(string $namespace = null)
    {
        $namespace = 'Totti619\\Gallery\\Libraries\\Files\\' . $namespace;
//        dd($namespace);
        return count($this->children($namespace));
    }

    /**
     * @return int
     */
    public function numberOfElements()
    {
        return count($this->files);
    }

    /**
     * Return the current element
     * @link  http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->files[$this->position];
    }

    /**
     * Move forward to next element
     * @link  http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Return the key of the current element
     * @link  http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Checks if current position is valid
     * @link  http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return $this->position;
    }

    /**
     * Rewind the Iterator to the first element
     * @link  http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->position = 0;
    }
}