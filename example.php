<?php

namespace Top\Nested;

class First
{
    public function __construct($hello, $world)
    {
        print "$hello $world!";
    }

    public function just_a_method() {}

    public function one_arg_method($arg1) {}
    public function two_args_method($arg1, $arg2) {}
    public function three_args_one_optional_method($arg1, $arg2, $arg3 = null) {}

    static public function static_method() {}

    private function private_method() {}
    protected function protected_method() {}
}

class Second extends First
{
    const TWENTY = 20;

    public function second_method() {}
}

class Third extends Second
{
    public $first_property;
    public $second_property;
    public $initialized_property = 'to a value';

    static $static_property;

    private $private_property;
    protected $protected_property;

    const THIRTY = 30;
}

require_once 'lib/xoad.php';

print \XOAD_Serializer::instance()->stringify('Top\Nested\Third');

/**

(function() {

var First, Second, Third, __constructor;

this.Top = this.Top || {};

this.Top.Nested = this.Top.Nested || {};

this.Top.Nested.First = (function() {

  First = function First(hello, world) {
    // TODO: invoke constructor
  };

  First.prototype.just_a_method = function just_a_method() {
    // TODO: invoke method
  };

  First.prototype.one_arg_method = function one_arg_method(arg1) {
    // TODO: invoke method
  };

  First.prototype.two_args_method = function two_args_method(arg1, arg2) {
    // TODO: invoke method
  };

  First.prototype.three_args_one_optional_method = function three_args_one_optional_method(arg1, arg2) {
    // TODO: invoke method
  };

  First.static_method = function static_method() {
    // TODO: invoke method
  };

  return First;
})();

this.Top.Nested.Second = (function() {

  Second = function Second() {
    // TODO: invoke constructor
  };

  __constructor = function() {};
  __constructor.prototype = this.Top.Nested.First.prototype;
  Second.prototype = new __constructor();
  Second.prototype.constructor = Second;

  Second.prototype.second_method = function second_method() {
    // TODO: invoke method
  };

  Second.prototype.TWENTY = 20;

  return Second;
})();

this.Top.Nested.Third = (function() {

  Third = function Third() {
    // TODO: invoke constructor
  };

  __constructor = function() {};
  __constructor.prototype = this.Top.Nested.Second.prototype;
  Third.prototype = new __constructor();
  Third.prototype.constructor = Third;

  Third.prototype.first_property = null;

  Third.prototype.second_property = null;

  Third.prototype.initialized_property = null;

  Third.static_property = null;

  Third.prototype.THIRTY = 30;

  return Third;
})();

}).call(this);

*/
