<?php

namespace WebIt4Me\Parser;

/**
 * Class AbstractColumn
 *
 * @author Ali Bahman <abn@webit4.me>
 */
class AbstractColumn implements ColumnInterface
{
    use IndexableTrait;

    /** @var string */
    private $value;

    /** @var string */
    private $name;

    /**
     * @param int $index
     * @param string $value
     * @param null $name
     */
    public function __construct($index, $value, $name = null)
    {
        $this->setIndex($index);

        $this->value = $value;

        $this->setName($name);
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        if (is_null($name)) {
            $name = $this->makeUpName();
        }

        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return string
     */
    private function makeUpName()
    {
        return sprintf('Column %s', $this->index + 1);
    }
}
