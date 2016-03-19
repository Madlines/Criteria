<?php

namespace Madlines\Criteria\Tests\Mock;

use Madlines\Criteria\Criteria;
use Madlines\Criteria\Criterion;

class ExtendedCriteriaMockWithAllowedKeysSet extends Criteria
{
    public function getAllowedKeys()
    {
        return ['foo', 'dolor'];
    }
}
