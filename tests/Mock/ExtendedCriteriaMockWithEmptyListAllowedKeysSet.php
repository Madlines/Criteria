<?php

namespace Madlines\Criteria\Tests\Mock;

use Madlines\Criteria\Criteria;
use Madlines\Criteria\Criterion;

class ExtendedCriteriaMockWithEmptyListAllowedKeysSet extends Criteria
{
    public function getAllowedKeys()
    {
        return []; // just like default
    }
}
