<?php

namespace TemplateCreator\Recipes\Migration;

use TemplateCreator\Recipes\CreateFile;
use TemplateCreator\Recipes\RecipeInterface;
use TemplateCreator\TemplateContext;
use function Symfony\Component\String\s;

class CreateMigrationFile extends CreateFile
{

    private const ROOT_DIR = '/migrations/';

    public function assertIngredientsComplete(TemplateContext $templateContext): RecipeInterface
    {
        if(count($templateContext->getErrors())) {
            return parent::assertIngredientsComplete($templateContext);
        }

        // assert model does not exist
        $path = sprintf('%s%s/%s_%s.php', dirname(__DIR__, 4), self::ROOT_DIR, s($templateContext->getName())->snake()->toString(), date('Y_m_d_his'));
        if(file_exists($path)) {
            $templateContext->addToErrors('file', sprintf('File %s already exists!', $path));
        }

        $templateContext->addToStorage('path', $path);

        return parent::assertIngredientsComplete($templateContext);
    }
}