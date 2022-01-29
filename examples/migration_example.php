<?php

require_once './vendor/autoload.php';

use TemplateCreator\Exceptions\CookbookNotFoundException;
use TemplateCreator\TemplateClient;
use TemplateCreator\TemplateContext;

$models = [
    [
        'name' => 'meeting-rooms',
    ],
    [
        'name' => 'customer-service',
    ]
];
try {
    $results = [];
    $templateClient = new TemplateClient('migration');
    foreach ($models as $model) {
        $templateContext = new TemplateContext($model['name']);
        $templateClient->create($templateContext);
        $results[] = $templateContext;
    }
    // all information regarding the process will be in this array
    print_r($results);
} catch (CookbookNotFoundException $e) {

} catch (Exception $e) {
    echo $e->getMessage();
}
