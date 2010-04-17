<?php

abstract class XOAD_Serializer extends XOAD_EventEmitter
{
    protected function __construct()
    {}

    abstract public function stringify($object, XOAD_Context $context = null);

    /** @return  XOAD_Serializer */
    static public function instance($type = XOAD_DEFAULT_SERIALIZER)
    {
        static $serializers = array();

        if (isset ($serializers[$type])) {
            return $serializers[$type];
        }

        require_once XOAD_BASE . "serializer/$type.php";
        $type_class = 'XOAD_' . ucfirst($type) . 'Serializer';

        $serializers[$type] = new $type_class();
        return $serializers[$type];
    }
}

abstract class XOAD_Context
{
    /** @var XOAD_ContextBuffer */
    public $output;

    public function __construct()
    {
        $this->output = new XOAD_ContextBuffer();
    }
}

final class XOAD_ContextBuffer
{
    private $level = 0;
    private $lines = array();

    /** @return  XOAD_ContextBuffer */
    public function descend()
    {
        $this->level = $this->level + 1;
        return $this;
    }

    /** @return  XOAD_ContextBuffer */
    public function ascend()
    {
        $this->level = $this->level - 1;
        return $this;
    }

    /** @return  XOAD_ContextBuffer */
    public function push($line)
    {
        $this->lines[] = array($this->level, $line);
        return $this;
    }

    /** @return  XOAD_ContextBuffer */
    public function unshift($line)
    {
        array_unshift($this->lines, array($this->level, $line));
        return $this;
    }

    public function join($indent)
    {
        $code = '';
        foreach ($this->lines as $line) {
            $code = $code . str_repeat($indent, $line[0]) . $line[1] . "\n";
        }
        return $code;
    }
}
