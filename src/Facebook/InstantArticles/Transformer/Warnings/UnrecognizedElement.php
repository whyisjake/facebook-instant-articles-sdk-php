<?php
/**
 * Copyright (c) 2016-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */
namespace Facebook\InstantArticles\Transformer\Warnings;

use Facebook\InstantArticles\Validators\Type;

class UnrecognizedElement
{
    private $context;
    private $node;

    public function __construct($context, $node)
    {
        $this->context = $context;
        $this->node = $node;
    }

    public function __toString()
    {
        $reflection = new \ReflectionClass(get_class($this->context));
        $className = $reflection->getShortName();
        $nodeName = $this->node->nodeName;
        if (substr($nodeName, 0, 1) === '#') {
            $nodeDescription = '"'.mb_strimwidth($this->node->textContent, 0, 30, '...').'"';
        } else {
            $nodeDescription = '<';
            $nodeDescription .= $nodeName;
            if (Type::is($this->node, 'DOMElement')) {
                $class = $this->node->getAttribute('class');
                if ($class) {
                    $nodeDescription .= ' class="'. $class .'"';
                }
            }
            $nodeDescription .= '>';
        }
        return "No rules defined for {$nodeDescription} in the context of $className";
    }

    public function getContext()
    {
        return $this->context;
    }

    public function getNode()
    {
        return $this->node;
    }
}
