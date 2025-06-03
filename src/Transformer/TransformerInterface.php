<?php

namespace Renfrewshire\Bartec\Transformer;

use Renfrewshire\Bartec\Exception\TransformationException;

interface TransformerInterface
{
    /**
     * @param mixed $value
     * @return string
     * @throws TransformationException
     */
    public function transform($value);
}