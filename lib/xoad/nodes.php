<?php

abstract class XOAD_Node
{
    private $parent = null;
    private $children = array();

    private function __construct()
    {}

    abstract static public function instance();

    /** @return  XOAD_Node */
    public function add_child(XOAD_Node $child)
    {
        $child_parent = $child->get_parent();
        if (isset ($child_parent)) {
            $child_parent->remove_child($child);
        }

        $child->set_parent($this);
        $this->children[] = $child;

        return $child;
    }

    /** @return  XOAD_Node */
    public function add_children(array $children)
    {
        foreach ($children as $child) {
            $this->add_child($child);
        }
        return $this;
    }

    /** @return  XOAD_Node */
    public function remove_child(XOAD_Node $child)
    {
        $index = array_search($child, $this->children);
        if ($index !== false) {
            array_splice($this->children, $index, 1);
            $child->set_parent(null);
        }

        return $child;
    }

    /** @return  XOAD_Node */
    public function remove_children(array $children)
    {
        foreach ($children as $child) {
            $this->remove_child($child);
        }
        return $this;
    }

    /** @return  XOAD_Node */
    public function empty_children()
    {
        foreach ($this->children as $child) {
            $this->remove_child($child);
        }

        return $this;
    }

    /** @return  XOAD_Node */
    protected function set_parent($parent)
    {
        $this->parent = $parent;
        return $parent;
    }

    /** @return  XOAD_Node */
    public function get_parent()
    {
        return $this->parent;
    }

    /** @return  array[XOAD_Node] */
    public function get_children()
    {
        return $this->children;
    }

    protected function get_title()
    {
        return substr(get_class($this), 5, -4);
    }

    public function stringify($level = 0)
    {
        $value = array(str_repeat(' ', $level * 2) . $this->get_title());
        foreach ($this->children as $child) {
            $value[] = $child->stringify($level + 1);
        }

        return implode("\n", $value);
    }

    public function __toString()
    {
        return $this->stringify(0);
    }
}

final class XOAD_NamespaceNode extends XOAD_Node
{
    private $name;

    private function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param  string  $name
     * @return  XOAD_NamespaceNode
     */
    static public function instance()
    {
        static $nodes = array();

        if (func_num_args() < 1) {
            throw new XOAD_ArgumentException(__CLASS__ . '::' . __FUNCTION__ . " has one required argument 'name'.", 1271525779);
        }

        $name = func_get_arg(0);
        if (isset ($nodes[$name])) {
            return $nodes[$name];
        }

        $nodes[$name] = new XOAD_NamespaceNode($name);
        return $nodes[$name];
    }

    public function get_parts()
    {
        return explode('\\', $this->name);
    }

    /** @return  XOAD_ClassNode */
    public function add_child(XOAD_ClassNode $child)
    {
        return parent::add_child($child);
    }

    protected function get_title()
    {
        return parent::get_title() . " '$this->name'";
    }
}

final class XOAD_ClassNode extends XOAD_Node
{
    private $name;
    private $parent;

    private function __construct($name, $parent = null)
    {
        $this->name = $name;
        $this->parent = $parent;
    }

    /**
     * @param  string  $name
     * @param  string  $parent
     * @return  XOAD_ClassNode
     */
    static public function instance()
    {
        static $nodes = array();

        if (func_num_args() < 1) {
            throw new XOAD_ArgumentException(__CLASS__ . '::' . __FUNCTION__ . " has one required argument 'name'.", 1271525779);
        }

        $name = func_get_arg(0);
        if (isset ($nodes[$name])) {
            return $nodes[$name];
        }

        $parent = null;
        if (func_num_args() > 1) {
            $parent = func_get_arg(1);
        }

        $nodes[$name] = new XOAD_ClassNode($name, $parent);
        return $nodes[$name];
    }

    public function get_name_parts()
    {
        return explode('\\', $this->name);
    }

    public function get_short_name()
    {
        return end($this->get_name_parts());
    }

    public function has_parent()
    {
        return isset ($this->parent);
    }

    public function get_parent_parts()
    {
        return explode('\\', $this->parent);
    }

    /** @return  XOAD_MethodNode */
    public function add_child(XOAD_Node $child)
    {
        if ( ! ($child instanceof XOAD_MethodNode ||
                $child instanceof XOAD_PropertyNode)) {
            throw new XOAD_ArgumentException(__CLASS__ . '::' . __FUNCTION__ . " expects 'child' of type [XOAD_MethodNode | XOAD_PropertyNode]", 1271534768);
        }

        return parent::add_child($child);
    }

    protected function get_title()
    {
        return parent::get_title() . " '$this->name'" . (isset ($this->extends) ? " extends '$this->extends'" : '');
    }
}

final class XOAD_MethodNode extends XOAD_Node
{
    private $name;
    private $static;

    private function __construct($name, $static = false)
    {
        $this->name = $name;
        $this->static = $static;
    }

    /**
     * @param  string  $name
     * @param  bool  $static
     * @return  XOAD_MethodNode
     */
    static public function instance()
    {
        if (func_num_args() < 1) {
            throw new XOAD_ArgumentException(__CLASS__ . '::' . __FUNCTION__ . " has one required argument 'name'.", 1271525779);
        }

        $name = func_get_arg(0);
        $static = (func_num_args() > 1 ? !! func_get_arg(1) : false);

        $node = new XOAD_MethodNode($name, $static);
        return $node;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function is_constructor()
    {
        return ('__construct' === $this->name);
    }

    public function is_static()
    {
        return $this->static;
    }

    protected function get_title()
    {
        return parent::get_title() . " '$this->name', static: " . ($this->static ? 'true' : 'false');
    }
}

final class XOAD_PropertyNode extends XOAD_Node
{
    private $name;
    private $value;
    private $static;

    private function __construct($name, $value, $static = false)
    {
        $this->name = $name;
        $this->value = $value;
        $this->static = $static;
    }

    /**
     * @param  string  $name
     * @param  string  $value
     * @param  bool  $static
     * @return  XOAD_PropertyNode
     */
    static public function instance()
    {
        if (func_num_args() < 2) {
            throw new XOAD_ArgumentException(__CLASS__ . '::' . __FUNCTION__ . " has two required arguments 'name', 'value'.", 1271534838);
        }

        $name = func_get_arg(0);
        $value = func_get_arg(1);
        $static = (func_num_args() > 2 ? !! func_get_arg(2) : false);

        $node = new XOAD_PropertyNode($name, $value, $static);
        return $node;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function get_value()
    {
        return $this->value;
    }

    public function is_static()
    {
        return $this->static;
    }

    protected function get_title()
    {
        return parent::get_title() . " '$this->name': '$this->value', static: " . ($this->static ? 'true' : 'false');
    }
}

final class XOAD_ArgumentNode extends XOAD_Node
{
    private $name;

    private function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param  string  $name
     * @return  XOAD_ArgumentNode
     */
    static public function instance()
    {
        if (func_num_args() < 1) {
            throw new XOAD_ArgumentException(__CLASS__ . '::' . __FUNCTION__ . " has one required argument 'name'.", 1271525779);
        }

        $name = func_get_arg(0);

        $node = new XOAD_ArgumentNode($name);
        return $node;
    }

    public function get_name()
    {
        return $this->name;
    }

    /** @return  void */
    public function add_child(XOAD_Node $child)
    {
        throw new XOAD_UnsupportedException(__CLASS__ . '::' . __FUNCTION__ . ' cannot have children added to it.', 1271524845);
    }

    protected function get_title()
    {
        return parent::get_title() . " '$this->name'";
    }
}
