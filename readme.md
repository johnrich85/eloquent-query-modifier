## Details
A wrapper for common Eloquent Query-builder usage. Supports Field selection, paging, key-value filtering & sorting. It is possible
to extend this further by adding your own modifiers that implement the 'QBModifier' interface.

This is an early version (pre 1.0) so use at your own risk. Also, future updates are likely to include a search modifier.

## Compatibility
PHP          5.4+
Laravel      5+

## Installation
Currently the package is only available via Git, although it will be added to Packagist in future. To install via composer, follow
the steps below.

Add the repo to your composer.json
```
"repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/johnrich85/EloquentQueryModifier"
    }
  ]
```

Add the package to your composer.json
```
"require": [
    "johnrich85/EloquentQueryModifier/": "dev-master"
  ]
```

##Usage

In a nut-shell, you simply pass Input:all() to the modifier & it handles the rest. Code example provided below:

Instantiate config:
```
$config = new Johnrich85\EloquentQueryModifier\InputConfig();
```

Instantiate Modifier:
```
$modifier = Johnrich85\EloquentQueryModifier\EloquentQueryModifier($config);
```

Call the modify method, passing input & a \Illuminate\Database\Eloquent\Builder instance.
```
$product = new Product();
$builder = $product->newQuery();
$modifier->modify($builder, Input::all());
$results = $product->get();
```

##Natively supports the following filters/modifiers
**Sort:** ?sort=-priority,created_at

**Field Filter:** ?fieldName=value

**General Search:** ?q=search term

**Field selection:** ?fields=id,name, description

**Pretty print:** ?pretty=true

