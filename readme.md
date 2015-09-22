## Details
Automatically generates filters for common Query String parameters such as sort, order, field selection etc.

Currently supports Laravel 5+ only.

##Usage

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

