<?php

final class XOAD_JavascriptSerializer extends XOAD_Serializer
{
    public function stringify($object, XOAD_Context $context = null)
    {
        $nodes = XOAD_Inspector::instance()->inspect($object);
        if ($context === null) {
            $context = new XOAD_JavascriptContext();
        }
        $this->stringify_nodes($nodes, $context);

        if (func_num_args() > 2) {
            $indent = func_get_arg(2);
        } else {
            $indent = '  ';
        }

        if ( ! $context->scope->temporaries->is_empty()) {
            $context->output->unshift('var ' . implode(', ', $context->scope->temporaries->get_sorted()) . ";\n");
        }
        $context->output->unshift("(function() {\n");
        if ( ! $context->scope->variables->is_empty()) {
            $context->output->unshift('var ' . implode(', ', $context->scope->variables->get_sorted()) . ";\n");
        }
        $context->output->push('}).call(this);');

        return $context->output->join($indent);
    }

    private function stringify_nodes(array $nodes, XOAD_JavascriptContext $context)
    {
        foreach ($nodes as $child) {
            if ($child instanceof XOAD_NamespaceNode) {
                $this->stringify_namespace($child, $context);
            } else if ($child instanceof XOAD_ClassNode) {
                $this->stringify_class($child, $context);
            } else if ($child instanceof XOAD_MethodNode) {
                $this->stringify_method($child, $context);
            } else if ($child instanceof XOAD_PropertyNode) {
                $this->stringify_property($child, $context);
            } else {
                throw new XOAD_UnsupportedException(__CLASS__ . '::' . __FUNCTION__ . " cannot serialize '" . get_class($child) . "'.", 1271527616);
            }
        }
    }

    private function stringify_namespace(XOAD_NamespaceNode $node, XOAD_JavascriptContext $context)
    {
        $namespace_parts = $node->get_parts();
        foreach ($namespace_parts as $part) {

            $context_namespace = $context->namespace->get_current();

            $context->output->push("$context_namespace.$part = $context_namespace.$part || {};\n");

            $context->namespace->push($part);
        }

        $this->stringify_nodes($node->get_children(), $context);
    }

    private function stringify_class(XOAD_ClassNode $node, XOAD_JavascriptContext $context)
    {
        $class_name = $node->get_name();
        $context_namespace = $context->namespace->get_current();

        if ($context->namespace->is_global()) {
            $context->scope->variables->add($class_name);
        } else {
            $context->scope->temporaries->add($class_name);
            $context->output->push("$context_namespace.$class_name = (function() {\n")->descend();
        }

        $constructor_arguments_list = array();
        foreach ($node->get_children() as $child) {
            if ($child instanceof XOAD_MethodNode && $child->is_constructor()) {
                foreach ($child->get_children() as $arg) {
                    $constructor_arguments_list[] = $arg->get_name();
                }
            }
        }
        $constructor_arguments = implode(', ', $constructor_arguments_list);

        $context->output->push("$class_name = function $class_name($constructor_arguments) {")->descend();
        $context->output->push('// TODO: invoke constructor');
        $context->output->ascend()->push("};\n");

        if ($node->has_parent()) {

            if ($context->namespace->is_global()) {
                $parent_class = end($node->get_parent_parts());
            } else {
                $parent_class = $context->namespace->get_global() . '.' . implode('.', $node->get_parent_parts());
            }

            $context->scope->temporaries->add('__constructor');

            $context->output->push('__constructor = function() {};');
            $context->output->push("__constructor.prototype = $parent_class.prototype;");
            $context->output->push("$class_name.prototype = new __constructor();");
            $context->output->push("$class_name.prototype.constructor = $class_name;\n");
        }

        $context->replace_namespace(new XOAD_JavascriptNamespace("$class_name.prototype"));
        $this->stringify_nodes($node->get_children(), $context);
        $context->restore_namespace();

        if ( ! $context->namespace->is_global()) {
            $context->output->push("return $class_name;");
            $context->output->ascend()->push("})();\n");
        }
    }

    private function stringify_method(XOAD_MethodNode $node, XOAD_JavascriptContext $context)
    {
        if ( ! $node->is_constructor()) {

            $method_name = $node->get_name();
            if ($node->is_static()) {
                $namespace_part = $context->namespace->pop();
            }
            $context_namespace = $context->namespace->get_current();

            $arguments_list = array();
            foreach ($node->get_children() as $child) {
                $arguments_list[] = $child->get_name();
            }
            $arguments = implode(', ', $arguments_list);

            $context->output->push("$context_namespace.$method_name = function $method_name($arguments) {")->descend();
            $context->output->push('// TODO: invoke method');
            $context->output->ascend()->push("};\n");

            if ($node->is_static()) {
                $context->namespace->push($namespace_part);
            }
        }
    }

    private function stringify_property(XOAD_propertyNode $node, XOAD_JavascriptContext $context)
    {
        $property_name = $node->get_name();
        $property_value = $node->get_value();
        if ($node->is_static()) {
            $namespace_part = $context->namespace->pop();
        }
        $context_namespace = $context->namespace->get_current();

        $context->output->push("$context_namespace.$property_name = " . json_encode($property_value) . ";\n");

        if ($node->is_static()) {
            $context->namespace->push($namespace_part);
        }
    }
}

final class XOAD_JavascriptContext extends XOAD_Context
{
    /** @var  XOAD_JavascriptNamespace */
    public $namespace;
    private $namespace_stack = array();
    /** @var  XOAD_JavascriptScope */
    public $scope;

    public function __construct(XOAD_JavascriptNamespace $namespace = null)
    {
        if ($namespace === null) {
            $namespace = new XOAD_JavascriptNamespace();
        }
        $this->replace_namespace($namespace);

        $this->scope = new XOAD_JavascriptScope();

        parent::__construct();
    }

    /** @return  XOAD_JavascriptContext */
    public function replace_namespace(XOAD_JavascriptNamespace $namespace)
    {
        $this->namespace_stack[] = $this->namespace;
        $this->namespace = $namespace;

        return $this;
    }

    /** @return  XOAD_JavascriptContext */
    public function restore_namespace()
    {
        $this->namespace = array_pop($this->namespace_stack);
        return $this;
    }
}

final class XOAD_JavascriptNamespace
{
    private $global;
    private $current;

    public function __construct($global = 'this')
    {
        $this->global = $global;
        $this->current = explode('.', $global);
    }

    /** @return  XOAD_JavascriptNamespace */
    public function set_global($namespace)
    {
        $this->global = $namespace;
        $this->namespace = array($this->global);

        return $this;
    }

    public function get_global()
    {
        return $this->global;
    }

    /** @return  XOAD_JavascriptNamespace */
    public function push($part)
    {
        $this->current[] = $part;
        return $this;
    }

    public function pop()
    {
        return array_pop($this->current);
    }

    public function get_current()
    {
        return implode('.', $this->current);
    }

    public function is_global()
    {
        return ($this->global === $this->get_current());
    }
}

final class XOAD_JavascriptScope
{
    /** @var  XOAD_JavascriptScopeContainer */
    public $variables;
    /** @var  XOAD_JavascriptScopeContainer */
    public $temporaries;

    public function __construct()
    {
        $this->variables = new XOAD_JavascriptScopeContainer();
        $this->temporaries = new XOAD_JavascriptScopeContainer();
    }
}

final class XOAD_JavascriptScopeContainer
{
    private $bag = array();

    public function add($name)
    {
        if ( ! in_array($name, $this->bag)) {
            $this->bag[] = $name;
        }
        return $this;
    }

    public function is_empty()
    {
        return empty ($this->bag);
    }

    public function get_sorted()
    {
        natcasesort($this->bag);
        return $this->bag;
    }
}
