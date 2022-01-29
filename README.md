## How to Initialise the app
- clone the repository
- go to the root directory
- run composer install

## How to use
- initiate the TemplateClient with one of the params [model, migration] or if you added your new cookbook just add it
- create an instance of TemplateContext($nameOfFile, optional $scopeOfTheFile)
- call the method create($templateContext) and your template should be created in the specified path in the recipes
- after calling create($templateContext) you will have all the information about the process into the object $templateContext
- there is a folder called examples contain three files to show you usages examples

## How to run tests
Open the project in Phpstorm and click right-click on the tests folder and choose run from the menu (you need to have a default interpreter configured or just configure new one)

## How to run the examples
Open the project in Phpstorm and open the terminal and run php examples/model_example.php

## How to scale up
Let's say we need to add some additional code/responsibilities to the migration creation process. 
If the developer is creating a migration to create a new table it makes sense to add the create table function call by default to the migration.
If this is the case we should do the following:
- Add new stub (migration_create_table.stub) in (src/TemplateCreator/stubs) directory and add the content of the stub.
- Create a new Recipe and write a code to do the following
- populate the contents of the new stub and append it to the current template in the right position ONLY IF the name of the migration contain for example (create_table_blabla) otherwise we just move to the next recipe by calling parent::prepare($name).
- Open the cookbook file (src/TemplateCreator/Cookbooks/migration_cookbook.php) and add the new recipe class to the array.
- you are done.

## How to scale out
Let's say we need to support new template.  
If the developer wants to support a template for (seed) we do the following:
- Add a new stub (seed.stub) in (src/TemplateCreator/stubs) directory and add the content of the stub.
- Create new recipes to handle the new template population/creation.
- create a new cookbook file in (src/TemplateCreator/Cookbooks) with the name (seed_cookbook.php) and return an array contains all the recipes you created in the order that make sense for you (do not forget to add NullRecipe at the end of the list)
- you are done.