<?php

final class XOAD_Inspector extends XOAD_EventEmitter
{
    private function __construct()
    {}

    /** @return  XOAD_Inspector */
    static public function instance()
    {
        static $inspector = null;

        if (isset ($inspector)) {
            return $inspector;
        }

        $inspector = new XOAD_Inspector();
        return $inspector;
    }

    /** @return  XOAD_Node */
    public function inspect($object)
    {
        if (is_object($object)) {
            return $this->inspect_object($object);
        } else if (class_exists($object)) {
            return $this->inspect_class($object);
        } else if (is_callable($object)) {
            return $this->inspect_function($object);
        }

        $event_result = $this->fire_event('inspect_unknown', array($object))->get_result();
        if ($event_result !== false) {
            return $event_result;
        }

        throw new XOAD_UnsupportedException(__CLASS__ . '::' . __FUNCTION__ . " expects 'name' of type [class | function]", 1271520108);
    }

    private function inspect_object()
    {
        // TODO: __set_state(..) in inspect_class(..)
    }

    private function inspect_class($name)
    {
        $class_reflection = new ReflectionClass($name);
        $extends = null;

        $parent_reflection = $class_reflection->getParentClass();
        if ($parent_reflection === false) {
            $nodes = array();
        } else {
            $extends = $parent_reflection->getName();
            $nodes = $this->inspect_class($extends);
        }

        $top_node = $class_node = null;
        if (method_exists($class_reflection, 'getNamespaceName')) {

            $class_namespace = $class_reflection->getNamespaceName();
            if (strlen($class_namespace)) {
                $top_node = XOAD_NamespaceNode::instance($class_namespace);
            }
        }

        $class_node = XOAD_ClassNode::instance($class_reflection->getName(), $extends);
        if (isset ($top_node)) {
            $top_node->add_child($class_node);
        } else {
            $top_node = $class_node;
        }

        if ( ! in_array($top_node, $nodes)) {
            $nodes[] = $top_node;
        }

        $class_node->empty_children();

        $class_methods = $class_reflection->getMethods();
        foreach ($class_methods as $method) if ($method->getDeclaringClass()->getName() === $class_reflection->getName()) {

            if ($method->isPublic() && ! ($method->isAbstract() || $method->isDestructor())) {
                if (method_exists($method, 'getShortName')) {
                    $method_name = $method->getShortName();
                } else {
                    $method_name = $method->getName();
                }
                $method_node = $class_node->add_child(XOAD_MethodNode::instance($method_name, $method->isStatic()));
                $method_parameters = $method->getParameters();
                foreach ($method_parameters as $parameter) {
                    $method_node->add_child(XOAD_ArgumentNode::instance($parameter->getName()));
                }
            }
        }

        $class_properties = $class_reflection->getProperties();
        foreach ($class_properties as $property) if ($property->getDeclaringClass()->getName() === $class_reflection->getName()) {
            if ($property->isPublic()) {
                $class_node->add_child(XOAD_PropertyNode::instance($property->getName(), null, $property->isStatic()));
            }
        }

        $parent_constants = ($parent_reflection === false ? array() : $parent_reflection->getConstants());
        $class_constants = array_diff($class_reflection->getConstants(), $parent_constants);
        foreach ($class_constants as $constant_name => $constant_value) {
            $class_node->add_child(XOAD_PropertyNode::instance($constant_name, $constant_value));
        }

        return $nodes;
    }

    private function inspect_function()
    {
        // TODO:
    }
}
