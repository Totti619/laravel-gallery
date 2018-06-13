<?php
/**
 * Created by PhpStorm.
 * User: Portatil
 * Date: 08/06/2018
 * Time: 9:28
 */

namespace Totti619\Gallery\Libraries\Files\Meta;

use Totti619\Gallery\Libraries\Exceptions\MetaIsEmptyException;
use Totti619\Gallery\Libraries\Exceptions\MetaNotFoundException;
use Totti619\Gallery\Libraries\Traits\SingletonTrait;

class JSONMeta extends Meta
{
    use SingletonTrait;
    /**
     * @var array
     */
    protected static $json;

    /**
     * JSONMeta constructor.
     */
    protected function __construct()
    {
        parent::$filename = config('gallery.meta.json_filename');
        self::$json = $this->read();
    }

    /**
     * getInstance() method sugar.
     * @param string|null $relativePath
     * @return array
     * @throws MetaIsEmptyException
     * @throws MetaNotFoundException
     */
    public function get(string $relativePath = null): array
    {
        $instance = self::getInstance();
//        $instance::$json = $this->read();

//        dd($instance::$json);

        if(!is_null($relativePath) && !empty($instance::$json)) {
            try {
                return [$relativePath => $instance::$json[$relativePath]];
            } catch (\Exception $e) {
                throw new MetaNotFoundException($relativePath);
            }
        }

        if(empty($instance::$json))
            throw new MetaIsEmptyException(self::$filename);

        return $instance::$json;
    }

    /**
     * @param array $new
     * @return bool
     */
    public function register(array $new): bool
    {
        self::$json = $this->read();

//        dd(self::$json, $new);

        try {
            $meta = array_merge(self::$json , $new);
        } catch (\Exception $e) {
            return false;
        }

//        $this->meta[$this->relativePath()][] = ['path' => $this->path];
//        $this->meta[$this->relativePath()][] = ['relativePath' => $this->relativePath];
//        $this->meta[$this->relativePath()][] = ['url' => $this->url];
//        $this->meta[$this->relativePath()][] = ['name' => $this->name];
//        $this->meta[$this->relativePath()][] = ['alternateUrl' => $this->alternateUrl];
//        $this->meta[$this->relativePath()][] = ['alternateRelativePath' => $this->alternateRelativePath];

        return $this->write($meta);
    }

    /**
     * @param bool $assoc
     * @return array|mixed
     */
    public function read(bool $assoc = true)
    {
        return json_decode(file_get_contents(config('gallery.meta.path') . '/' . parent::$filename), $assoc) ?? [];
    }

    /**
     * @param array $meta
     * @return bool
     */
    public function write(array $meta):bool
    {
        $fopen = fopen(config('gallery.meta.path') . '/' . config('gallery.meta.json_filename'), 'w');
        fwrite($fopen, json_encode($meta));

        return fclose($fopen);
    }

    /**
     * @param string $relativePath
     * @param bool   $ignoreGeneratedMeta
     * @return string
     * @throws MetaIsEmptyException
     * @throws MetaNotFoundException
     */
    public function toHTMLAttr(string $relativePath, bool $ignoreGeneratedMeta = true): string
    {
        $dataPrefix = config('gallery.meta.data_attribute_prefix');
        $meta = $this->get($relativePath)[$relativePath];
//        dd($meta);
        $return = '';
        foreach ($meta as $key => $value) {
            if($ignoreGeneratedMeta && !$this->isGenerated($key) || !$ignoreGeneratedMeta) {
                $return .= $dataPrefix . $key . '=' . str_replace(' ', config('gallery.alternate_separator'), $value) . ' ';
            }
        }
        return $return;
    }



    /**
     * @param string $key
     * @return bool
     */
    public function isGenerated(string $key): bool
    {
        return strpos($key, config('gallery.meta.generated_meta_prefix')) !== false;
    }
}