<?php

namespace WPS\Vendor\DeepCopy\Matcher\Doctrine;

use WPS\Vendor\DeepCopy\Matcher\Matcher;
use WPS\Vendor\Doctrine\Common\Persistence\Proxy;

/**
 * @final
 */
class DoctrineProxyMatcher implements Matcher
{
    /**
     * Matches a Doctrine Proxy class.
     *
     * {@inheritdoc}
     */
    public function matches($object, $property)
    {
        return $object instanceof Proxy;
    }
}
