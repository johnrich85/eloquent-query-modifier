## Details
A wrapper for common Eloquent Query-builder usage. Supports Field selection, paging, key-value filtering, search & sorting. It is possibleto extend this further by adding your own modifiers that implement the 'QBModifier' interface.

## Compatibility
PHP          5.4+

Laravel      5.0 - 5.1 (Use version 1.04. **Please note:** does not support free text search)

Laravel      5.2+ (Use latest version)

## Installation
Currently the package is only available via Git, although it will be added to Packagist in future. To install via composer, follow
the steps below.

Add the repo to your composer.json
```
"repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/johnrich85/eloquent-query-modifier"
    }
  ]
```

Add the package to your composer.json
```
"require": [
    "johnrich85/eloquent-query-modifier/": "^1.01"
  ]
```

##Usage

In a nut-shell, you simply pass Input:all() to the modifier & it handles the rest. Code example provided below:

Instantiate config and factory.
```
$config = new Johnrich85\EloquentQueryModifier\InputConfig();
$factory = new Johnrich85\EloquentQueryModifier\Factory\ModifierFactory();
```

Instantiate Modifier:
```
$modifier = Johnrich85\EloquentQueryModifier\EloquentQueryModifier($config, $factory);
```

Call the modify method, passing input & a \Illuminate\Database\Eloquent\Builder instance.
```
$product = new Product();
$builder = $product->newQuery();
$modifier->modify($builder, Input::all());
$results = $product->get();
```

## Enabling search on your models.

This search functionality provided by this package is dependent on 'jarektkaczyk/eloquence'. In a nutshell, to enable search on your models, you need to implement the Eloquence trait as explained on the page below.

https://github.com/jarektkaczyk/eloquence/wiki/Builder-searchable-and-more#searchable

** If you do not implement the Trait & attempt to use the search parameter ('q' by default) an exception will be thrown.

## Disabling search on your models.

You can disable the search facility altogther by removing the modifier from the config. An example has been included below.

```
$config = new Johnrich85\EloquentQueryModifier\InputConfig();
$config->removeModifier('\Johnrich85\EloquentQueryModifier\Modifiers\SearchModifier');

$factory = new Johnrich85\EloquentQueryModifier\Factory\ModifierFactory();

$modifier = Johnrich85\EloquentQueryModifier\EloquentQueryModifier($config, $factory);
```

##Natively supports the following filters/modifiers
**Sort:** ?sort=-priority,created_at

**Field Filter:** ?fieldName=value

**Field selection:** ?fields=id,name, description

**Pretty print:** ?pretty=true

**Basic Search:** ?q=search term

