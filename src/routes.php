<?php

Route::prefix(config('gallery.route.prefix'))->namespace(config('gallery.namespace.controllers'))->group(function () {
    Route::get('/{path?}', 'MainController@index')->where('path,' , '(.*)');
});
