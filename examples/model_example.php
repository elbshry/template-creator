<?php

require_once './vendor/autoload.php';

use TemplateCreator\Exceptions\CookbookNotFoundException;
use TemplateCreator\TemplateClient;
use TemplateCreator\TemplateContext;

$models = [
    [
        'name' => 'meeting-rooms',
        'scope' => [
            'indirect-emissions–owned',
            'electricity'
        ]
    ],
    [
        'name' => 'customer-service',
        'scope' => [
            'indirect-emissions–owned',
            'electricity'
        ]
    ]
];
try {
    $results = [];
    $templateClient = new TemplateClient('model');
    foreach ($models as $model) {
        $templateContext = new TemplateContext($model['name'], $model['scope']);
        $templateClient->create($templateContext);
        $results[] = $templateContext;
    }
    // all information regarding the process will be in this array
    print_r($results);
} catch (CookbookNotFoundException $e) {

} catch (Exception $e) {
    echo $e->getMessage();
}
