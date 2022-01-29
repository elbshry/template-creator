<?php
namespace TemplateCreator;

use TemplateCreator\Exceptions\CookbookNotFoundException;
use TemplateCreator\Recipes\RecipeInterface;

class TemplateClient
{

    private ?RecipeInterface $cookbook = null;

    private string $cookbookName;

    public function __construct(string $cookbookName)
    {
        $this->cookbookName = $cookbookName;
    }

    /**
     * @throws CookbookNotFoundException
     */
    public function create(TemplateContext $templateContext): TemplateContext
    {
        // initiate the cookbook if not yet initiated
        // early fail if the cookbook does not exist
        if(is_null($this->cookbook)) {
            $this->init($this->cookbookName);
        }
        // assert that input is valid
        $this->cookbook->assertIngredientsComplete($templateContext);
        // early exist if there is an error
        if(count($templateContext->getErrors())) {
            return $templateContext;
        }
        // generate the template according to its recipes
        $this->cookbook->prepare($templateContext);
        // $templateContext contain all the necessary information regarding the process
        return $templateContext;
    }

    /**
     * @throws CookbookNotFoundException
     */
    private function init($cookbook)
    {
        $path = $this->assertCookbookExists($cookbook);
        $recipes = require $path;
        $this->cookbook = $this->initCookbook($recipes);
    }

    private function initCookbook(array $recipes): RecipeInterface
    {
        // initiate the first recipe in the list
        $firstRecipeCls = array_shift($recipes);
        $firstRecipe = $prevRecipe = new $firstRecipeCls();

        // initiate the rest of the recipes and attach them to the chain
        foreach ($recipes as $recipe) {
            $curRecipe = new $recipe();
            $prevRecipe = $prevRecipe->setNext($curRecipe);
        }

        // return the first recipe in the chain
        return $firstRecipe;
    }

    /**
     * @throws CookbookNotFoundException
     */
    private function assertCookbookExists($cookbook): string
    {
        $path = sprintf('%s/Cookbooks/%s_cookbook.php', dirname(__FILE__), $cookbook);
        // early exist if the cookbook does not exist
        // this exception should be handled in the client code according to the developer needs
        if(!file_exists($path)) {
            throw new CookbookNotFoundException(sprintf('Can not find the cookbook %s in the path %s', $cookbook, $path));
        }
        return $path;
    }
}