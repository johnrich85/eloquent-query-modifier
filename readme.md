## Details
A wrapper for common Eloquent Query-builder usage. Supports Field selection, paging, key-value filtering & sorting. It is possible
to extend this further by adding your own modifiers that implement the 'QBModifier' interface.

This is an early version (pre 1.0) so use at your own risk. Also, future updates are likely to include a search modifier.

## Compatibility
Laravel 5+

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
*Sort:* ?sort=-priority,created_at

*Field Filter:* ?fieldName=value

*General Search:* ?q=search term

*Field selection:* ?fields=id,name, description

*Pretty print:* ?pretty=true

