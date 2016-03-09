<?php

namespace Madlines\Criteria\MappingStrategy;

class DefaultMappingStrategy
{
    const NAMESPACE_ROOT = '\Madlines\Criteria\Criterion\\';

    /**
     * @param string $operator
     * @return string
     */
    public function __invoke($operator)
    {
        return
            self::NAMESPACE_ROOT .
            str_replace(
                ' ',
                '',
                ucwords(
                    str_replace('_', ' ', $operator)
                )
            );
    }
}
