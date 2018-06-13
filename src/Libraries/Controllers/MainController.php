<?php

namespace Totti619\Gallery\Libraries\Controllers;

use App\Http\Controllers\Controller;
use Totti619\Gallery\Libraries\Files\Folder;
use Totti619\Gallery\Libraries\Files\Root;

/**
 * Class MainController
 * @package Totti619\Gallery\Libraries\Controllers
 */
class MainController extends Controller
{
    /**
     * @param string|null $path
     * @return \Illuminate\View\View
     */
    public function index(string $path = null)
    {
        $root = Root::getInstance()->folder();

        $root = is_null($path) ? $root
            :   $root = $root->get($path);

//        dd($root); // debug purposes

        return $this->view('index')
            ->with([
                'root' => $root,
                'folders' => $root instanceof Folder ? $root->folders() : [],
                'images' => $root instanceof Folder ? $root->images() : [$root],
            ]);
    }

    /**
     * @param $view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($view)
    {

        return view('gallery::' . $view);
    }


}
