<?php
declare(strict_types=1);

namespace Neos\ContentRepository\Domain\ValueObject;

/*
 * This file is part of the Neos.ContentRepository package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;

/**
 * The Node Name is the "path part" of the node; i.e. when accessing the node "/foo" via path,
 * the node name is "foo".
 *
 * @Flow\Proxy(false)
 * @api
 */
final class NodeName implements \JsonSerializable
{

    /**
     * @var NodeName
     */
    private static $rootNodeName;

    /**
     * @var NodeName
     */
    private static $unnamedNodeName;

    /**
     * @var string
     */
    private $value;

    private function __construct(string $value)
    {
        if (!is_string($value) || preg_match(NodeInterface::MATCH_PATTERN_NAME, $value) !== 1) {
            throw new \InvalidArgumentException('Invalid node name "' . $value . '" (a node name must only contain lowercase characters, numbers and the "-" sign).', 1364290748);
        }

        $this->value = $value;
    }

    public static function fromString(string $value): self
    {
        return new static($value);
    }

    /**
     * This constructor creates an empty NodeName that can be used to create "unnamed" nodes
     * @see isUnnamed()
     *
     * @return NodeName
     */
    public static function unnamed(): NodeName
    {
        if (!self::$unnamedNodeName) {
            self::$unnamedNodeName = new static('-');
            self::$unnamedNodeName->value = '';
        }
        return self::$unnamedNodeName;
    }


    public function isUnnamed(): bool
    {
        return $this === self::$unnamedNodeName;
    }

    /**
     * the Root node does not have a name; so it is assigned this special NodeName
     *
     * @return NodeName
     */
    public static function root(): NodeName
    {
        if (!self::$rootNodeName) {
            self::$rootNodeName = new static('-');
        }
        return self::$rootNodeName;
    }

    public function isRoot(): bool
    {
        return $this === self::$rootNodeName;
    }

    /**
     * @return string
     */
    public function jsonSerialize(): string
    {
        return $this->value ?? '';
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value ?? '';
    }
}
