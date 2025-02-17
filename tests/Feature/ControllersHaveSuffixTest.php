<?php

use Illuminate\Support\Facades\File;

test('all controllers have the suffix "Controller"', function () {
    $controllerPath = app_path('Http/Controllers');
    $files = File::allFiles($controllerPath);

    $controllersWithoutSuffix = [];

    foreach ($files as $file) {
        $className = $file->getFilenameWithoutExtension();
        if (! str_ends_with($className, 'Controller')) {
            $controllersWithoutSuffix[] = $className;
        }
    }

    expect($controllersWithoutSuffix)
        ->toBeEmpty("The following controllers do not have the suffix 'Controller': ".implode(', ', $controllersWithoutSuffix));
});
