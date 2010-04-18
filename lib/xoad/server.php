<?php

final class XOAD_Server extends XOAD_EventEmitter
{
    private $allowed_classes = array();

    private function __construct()
    {}

    /** @return  XOAD_Server */
    static public function instance()
    {
        static $server = null;

        if (isset ($server)) {
            return $server;
        }

        $server = new XOAD_Server();
        return $server;
    }

    /** @return  XOAD_Server */
    public function allow($class_name)
    {
        if ( ! in_array($class_name, $this->allowed_classes)) {
            $this->allowed_classes[] = $class_name;
        }
        return $this;
    }

    /** @return  XOAD_Server */
    public function accept()
    {
        if (isset ($_SERVER['HTTP_X_REQUESTED_WITH']) && 'XOAD' === $_SERVER['HTTP_X_REQUESTED_WITH']) {

            header('Content-Type: application/json; charset=utf-8');

            $raw_post_json = '';
            $raw_input_handle = fopen('php://input', 'r');
            while ( ! feof($raw_input_handle)) {
                $raw_post_json = $raw_post_json . fread($raw_input_handle, XOAD_DEFAULT_CHUNK_SIZE);
                if ( ! feof($raw_input_handle)) {
                    usleep(XOAD_DEFAULT_CHUNK_SLEEP);
                }
            }
            fclose($raw_input_handle);

            if (substr($raw_post_json, 0, 1) === '{') {
                $raw_post_array = json_decode($raw_post_json, true);
                if (is_array($raw_post_array)) {

                    // TODO:
                    print json_encode($raw_post_array);
                    exit (0x00);
                }
            }

            header('HTTP/1.1 500 Internal Server Error');
            print json_encode(array('message' => __CLASS__ . '::' . __FUNCTION__ . ' did not understand the request.'));
            exit (0x01);
        }
        return $this;
    }
}
