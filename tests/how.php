<?php

interface Fitler
{
    public function getFieldName();
    // public function getValue(); // TODO we don't want this method to be required
}

abstract class BaseFilter implements Filter
{
    private $fieldName;
    private $value;

    public function __construct($fieldName, $value)
    {
        $this->fieldName = $fieldName;
        $this->value = $value;
    }

    public function getFieldName()
    {
        return $this->fieldName;
    }

    public function getValue()
    {
        return $this->value;
    }
}

class MyCustomFilter implements Filter
{
    public function __construct($fieldName, array $ids1, array $ids2)
    {
        $this->fieldName = $fieldName;
        $this->ids1 = $ids1;
        $this->ids2 = $ids2;
    }

    public function getFieldName()
    {
        return $this->fieldName;
    }

    // TODO getters for ids1, ids2
    // TODO no getter for value (no prop called value)
}

$criteria = new Criteria();
$criteria->isFieldAllowed('foo'); // returns bool, to be used later with CriteriaBuilder (internally)

$criteria
    ->addFilter(new Equals('foo', 'bar')) // addFilter checks againsts allowed fields
    ->addFilter(new StartsWith('lorem', 'ipsum'))
    ->addFilter(new EndsWith('dolor', 'amet'))
    ->addFilter(new Contains('sit', 'emet'))
    ;

// TODO in near future
$criteria->sortAscBy('dolor');
$criteria->sortDescBy('dolor');
$criteria->setMaxResults(10);
$criteria->setFirstResult(20);

// possible extension
//
class MySearchCriteria extends Criteria
{
    protected function getAllowedFields()
    {
        return [
            'foo',
            'lorem',
            'dolor',
            'sit'
        ];
    }
}

// later...

$queryBuilder = $adapter->adapt($criteria);
$queryBuilder->getResult(); // ...

// ... (do this in compiler pass? make it configurable via config? or tags? we'll start with non-generic compiler pass)
//
$criteriaBuilder = new CriteriaBuilder();
$criteriaBuilder->map('eq', Equals::class);
$criteriaBuilder->map('starts_with', StartWith::class);
$criteriaBuilder->setDefaultFilter('eq');

$criteria = $criteriaBuilder->parseArray(new MySearchCriteria(), $request->query->all()); // OR
$criteria = $criteriaBuilder->parseArray(new MySearchCriteria(), $_GET); // OR
$criteria = $criteriaBuilder->parseString(new MySearchCriteria(), 'foo=bar&lorem__starts_with=ipsum');

$repository->search($criteria);

// test
