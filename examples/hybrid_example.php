<?php

require_once './vendor/autoload.php';

use TemplateCreator\Exceptions\CookbookNotFoundException;
use TemplateCreator\TemplateClient;
use TemplateCreator\TemplateContext;

$models = [
    [
        'cookbook' => 'model',
        'name' => 'meeting-rooms',
        'scope' => [
            'indirect-emissions–owned',
            'electricity'
        ]
    ],
    [
        'cookbook' => 'model',
        'name' => 'customer-service',
        'scope' => [
            'indirect-emissions–owned',
            'electricity'
        ]
    ],
    [
        'cookbook' => 'migration',
        'name' => 'meeting-rooms',
    ],
    [
        'cookbook' => 'migration',
        'name' => 'customer-service',
    ]
];
try {
    $results = [];
    $templateClients = [];
    foreach ($models as $model) {
        if(!isset($templateClients[$model['cookbook']])) {
            $templateClients[$model['cookbook']] = new TemplateClient($model['cookbook']);
        }
        $templateClient = $templateClients[$model['cookbook']];
        $templateContext = new TemplateContext($model['name'], $model['scope'] ?? []);
        $templateClient->create($templateContext);
        $results[] = $templateContext;
    }
    // all information regarding the process will be in this array
    print_r($results);
} catch (CookbookNotFoundException $e) {

} catch (Exception $e) {
    echo $e->getMessage();
}
