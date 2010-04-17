<?php

final class XOAD_EventObject
{
    private $result = false;
    private $cancelled = false;

    public function set_result($result)
    {
        $this->result = $result;
    }

    public function get_result()
    {
        return $this->result;
    }

    public function stop()
    {
        $this->cancelled = true;
    }

    public function is_cancelled()
    {
        return $this->cancelled;
    }
}

class XOAD_EventEmitter
{
    private $listeners = array();

    public function add_listener($name, $callback)
    {
        if ( ! is_callable($callback)) {
            throw new XOAD_ArgumentException(__CLASS__ . '::' . __FUNCTION__ . " expects 'callback' to be a function.", 1271520377);
        }

        $this->listeners[$name][] = $callback;
        return sizeof ($this->listeners[$name]);
    }

    public function remove_listener($name, $callback)
    {
        if ( ! is_callable($callback)) {
            throw new XOAD_ArgumentException(__CLASS__ . '::' . __FUNCTION__ . " expects 'callback' to be a function.", 1271520377);
        }

        if (isset ($this->listeners[$name])) {

            $index = array_search($callback, $this->listeners[$name]);
            if ($index !== false) {
                array_splice($this->listeners[$name], $index, 1);
            }

            return sizeof ($this->listeners[$name]);
        }

        return false;
    }

    public function remove_listeners($name)
    {
        if (isset ($this->listeners[$name])) {

            unset ($this->listeners[$name]);
            return true;
        }

        return false;
    }

    /** @return  XOAD_EventObject */
    protected function fire_event($name)
    {
        $event = new XOAD_EventObject();
        if (isset ($this->listeners[$name])) {

            $callback_args = array($event);
            for ($i = 1, $func_args = func_get_args(), $length = sizeof ($func_args); $i < $length; $i ++) {
                $callback_args[] = $func_args[$i];
            }

            foreach ($this->listeners[$name] as $callback) {
                call_user_func_array($callback, $callback_args);
                if ($event->is_cancelled()) {
                    break;
                }
            }
        }

        return $event;
    }
}
