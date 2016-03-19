# Madlines Criteria

This library lets you prepare a set of search criteria
in a nice, object-oriented way without having to use
e.g. ORM-specific query builder.

Keep in mind that this lib isn't trying to replace
any query builders (like e.g. the one shipped with
Doctrine 2 ORM).

A Criteria's structure makes it pretty easy to write a
parser which will translate that object into a query
you can send to your DB / SearchEngine / API / whatever.

## Example usage

```php
<?php

use Madlines\Criteria\Criteria;
use Madlines\Criteria\Criterion\Equals;
use Madlines\Criteria\Criterion\StartsWith;

$criteria = new Criteria();
$criteria = $criteria // $criteria is immutable so you have to store result of adding
    ->add(new Equals('foo', 'bar'))
    ->add(new StartsWith('lorem', 'ipsum'));

// later in your code
$criteriaArr = $criteria->getAll(); // returns an array containing all criteria
$keys = $criteria->getKeys(); // returns all keys (foo and lorem in this case)
$foo = $criteria->getFor('foo'); // return criterion object for given key
```

## Criteria Builder

You can automate process of building a `Criteria` object using `CriteriaBuilder`
and one of mapping strategies.

You can create your own strategies or use a built-in `DefaultMappingStrategy` which
maps input to built-in `Criterion` types. You can also create a map manually.

```php
<?php

use Madlines\Criteria\Criteria;
use Madlines\Criteria\Criterion;

$builder = new \Madlines\Criteria\CriteriaBuilder();
$builder->setMappingStrategy(function ($operator) {
    if ('eq' === $operator) {
        return Criterion\Equals::class;
    }

    if ('contains' === $operator) {
        return Criterion\Contains::class;
    }

    return '';
});

// Optionally you can add some mapping manually
$builder->map('starts_with', Criterion\StartsWith::class);
$builder->setDefault('eq');

// e.g. $_GET array
$input = [
    'foo__eq' => 'Cat',
    'bar__contains' => 'Dog',
];

$criteria = $builder->buildFromArray($input, new Criteria());

```

### Allowed keys

If you want to allow creating a criteria only for certain set of fields
you have to extend a Criteria class and override `getAllowedKeys` method

```php
<?php

class MyCriteria extends \Madlines\Criteria\Criteria
{
    public function getAllowedKeys()
    {
        return ['lorem', 'ipsum'];
    }
}
```
