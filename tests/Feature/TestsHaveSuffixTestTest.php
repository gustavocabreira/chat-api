<?php

use Illuminate\Support\Facades\File;

test('all feature tests have the suffix "Test"', function () {
    $testsPath = base_path('tests/Feature');
    $files = File::allFiles($testsPath);

    $testsWithoutSuffix = [];

    foreach ($files as $file) {
        $className = $file->getFilenameWithoutExtension();
        if (! str_ends_with($className, 'Test')) {
            $testsWithoutSuffix[] = $className;
        }
    }

    expect($testsWithoutSuffix)
        ->toBeEmpty("The following tests do not have the suffix 'Test': ".implode(', ', $testsWithoutSuffix));
});

test('all unit tests have the suffix "Test"', function () {
    $testsPath = base_path('tests/Unit');
    $files = File::allFiles($testsPath);

    $testsWithoutSuffix = [];

    foreach ($files as $file) {
        $className = $file->getFilenameWithoutExtension();
        if (! str_ends_with($className, 'Test')) {
            $testsWithoutSuffix[] = $className;
        }
    }

    expect($testsWithoutSuffix)
        ->toBeEmpty("The following tests do not have the suffix 'Test': ".implode(', ', $testsWithoutSuffix));
});
