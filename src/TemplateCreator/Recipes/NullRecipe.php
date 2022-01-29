<?php

namespace TemplateCreator\Recipes;

use TemplateCreator\TemplateContext;

class NullRecipe extends AbstractRecipe
{

    /**
     * @param TemplateContext $templateContext
     * @return RecipeInterface
     */
    public function assertIngredientsComplete(TemplateContext $templateContext): RecipeInterface
    {
        return $this;
    }

    public function prepare(TemplateContext $templateContext): RecipeInterface
    {
        return $this;
    }
}