<?php
namespace Totti619\Gallery\Libraries\Files;

use Totti619\Gallery\Libraries\Exceptions\MetaIsEmptyException;
use Totti619\Gallery\Libraries\Exceptions\MetaNotFoundException;
use Totti619\Gallery\Libraries\Files\Meta\JSONMeta;

class File
{
    /**
     * @var JSONMeta
     */
    protected $meta;
    /**
     * @var array
     */
    protected $pathExploded;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $relativePath;

    /**
     * @var string
     */
    protected $alternateRelativePath;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var Folder
     */
    protected $folder;

    /**
     * @var string
     */
    protected $alternateUrl;

    /**
     * File constructor.
     * @param string      $path
     * @param Folder      $folder
     */
    public function __construct(string $path = null, Folder $folder = null)
    {
        $this->path = realpath($path);
        $this->relativePath = $this->relativePath();
        $this->url = $this->url();
        $this->folder = $folder;
        $this->pathExploded = explode(config('gallery.default_separator'), $this->path);
        $this->name = $this->pathExploded[count($this->pathExploded) - 1];
        $this->alternateUrl = $this->alternateUrl();
        $this->alternateRelativePath = $this->alternateRelativePath();
        $this->meta = $this->meta();

//        $this->registerToMeta();
    }

    /**
     * @param string|null $index
     * @return JSONMeta
     */
    public function meta(string $index = null)
    {
        $jsonMeta = JSONMeta::getInstance();

//        $return = $jsonMeta->get($this->relativePath);
        try {
            $return = $jsonMeta->get($this->relativePath);
        } catch (MetaNotFoundException|MetaIsEmptyException $e) {

            $prefix = config('gallery.meta.generated_meta_prefix');

            $return = [$this->relativePath() => [
                $prefix . 'type' => self::class,
                $prefix . 'path' => $this->path,
                $prefix . 'name' => $this->name,
                $prefix . 'url' => $this->url,
                $prefix . 'relativePath' => $this->relativePath,
                $prefix . 'alternateUrl' => $this->alternateUrl,
                $prefix . 'alternateRelativePath' => $this->alternateRelativePath
            ]];
        }

        $jsonMeta->register($return);

        if(!is_null($index)) {
            return $return[$this->relativePath()][$index] ?? null;
        }
        return $return;
    }

    /**
     * @return string
     */
    public function metaHTML() : string
    {
        $return = '';
        $meta = $meta ?? $this->meta();
        foreach($meta as $key => $value) {
            $return .= 'data-' . $key . '="' . str_replace(' ', config('gallery.meta.space_separator'), $value) . '" ';
        }
        return $return;
    }

    /**
     * @param string $path
     * @return string
     */
    public static function type(string $path)
    {
        if(Image::formatSupported(Image::format($path))) {
            return Image::class;
        }
        return File::class;
    }

    /**
     * @return array
     */
    public function getPathExploded(): array
    {
        return $this->pathExploded;
    }

    /**
     * @param array $pathExploded
     */
    public function setPathExploded(array $pathExploded)
    {
        $this->pathExploded = $pathExploded;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getRelativePath(): string
    {
        return $this->relativePath;
    }

    /**
     * @param string $relativePath
     */
    public function setRelativePath(string $relativePath)
    {
        $this->relativePath = $relativePath;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return Folder
     */
    public function getFolder(): Folder
    {
        return $this->folder;
    }

    /**
     * @param Folder $folder
     */
    public function setFolder(Folder $folder)
    {
        $this->folder = $folder;
    }



    /**
     * @param string $path
     */
    public function rename(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function url(): string
    {
        return $this->url ?? config('gallery.gallery_url') . $this->relativePath;
    }

    /**
     * @return string
     */
    public function relativePath(): string
    {
        if(is_null($this->relativePath)) {
            $relative = str_replace(config('gallery.gallery_path'), '', $this->path);
            if(strlen($relative) > 1) {
                $relative = substr($relative, 1);
            }
            if($relative === "") {
                $relative = "/";
                $this->alternateRelativePath = "";
            }
        } else {
            $relative = $this->relativePath;
        }
        return $relative;
    }

    /**
     * @return string
     */
    public function alternateUrl(): string
    {
        return $this->alternateUrl ?? url(config('gallery.route.prefix')) . '/' . $this->alternateRelativePath();
    }

    /**
     * @return string
     */
    public function alternateRelativePath(): string
    {
        return $this->alternateRelativePath ?? str_replace(
            config('gallery.default_separator'),
            config('gallery.alternate_separator'),
            $this->relativePath()
        );
    }

    /**
     * toHTMLAttr method sugar.
     * @param bool $ignoreGeneratedMeta
     * @return string
     */
    public function metaToHTMLAttr(bool $ignoreGeneratedMeta = true): string
    {
        return (JSONMeta::getInstance())->toHTMLAttr($this->relativePath(), $ignoreGeneratedMeta);
    }

//    public function registerToMeta()
//    {
//        $new = [$this->relativePath() => [
//            'path' => $this->path,
//            'name' => $this->name,
//            'url' => $this->url,
//            'relativePath' => $this->relativePath,
//            'alternateUrl' => $this->alternateUrl,
//            'alternateRelativePath' => $this->alternateRelativePath
//        ]];
//        $meta = [];
//        try {
//            $meta = array_merge($this->meta , $new);
//        } catch (\Exception $e) {
////            $meta = array_merge($this->meta , $new);
//        }
//
////        $this->meta[$this->relativePath()][] = ['path' => $this->path];
////        $this->meta[$this->relativePath()][] = ['relativePath' => $this->relativePath];
////        $this->meta[$this->relativePath()][] = ['url' => $this->url];
////        $this->meta[$this->relativePath()][] = ['name' => $this->name];
////        $this->meta[$this->relativePath()][] = ['alternateUrl' => $this->alternateUrl];
////        $this->meta[$this->relativePath()][] = ['alternateRelativePath' => $this->alternateRelativePath];
//
//        $fopen = fopen(config('gallery.meta.path') . '/' . config('gallery.meta.json_filename'), 'w');
//        fwrite($fopen, json_encode($meta));
//        fclose($fopen);
//    }
}