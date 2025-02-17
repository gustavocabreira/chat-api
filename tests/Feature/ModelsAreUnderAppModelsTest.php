<?php

use Illuminate\Support\Facades\File;

test('all models are in the App\Models namespace', function () {
    $modelPath = app_path('Models');
    $files = File::allFiles($modelPath);

    $modelsWithoutNamespace = [];

    foreach ($files as $file) {
        $className = $file->getFilenameWithoutExtension();
        $fullClass = "App\\Models\\$className";

        if (! class_exists($fullClass)) {
            $modelsWithoutNamespace[] = $className;
        }
    }

    expect($modelsWithoutNamespace)
        ->toBeEmpty('The following models are not in the App\Models namespace: '.implode(', ', $modelsWithoutNamespace));
});
