<?php

namespace Example;
final class Calculator
{
    public $left;
    public $right;

    public function __construct($left = 0, $right = 0)
    {
        $this->left = $left;
        $this->right = $right;
    }

    public function add()
    {
        return $this->left + $this->right;
    }

    static public function add_static($left = 0, $right = 0)
    {
        return $left + $right;
    }
}

require_once 'lib/xoad.php';

\XOAD_Server::instance()->allow('Example\Calculator')->accept();

?>
<script type="text/javascript">
// <![CDATA[
<?php print \XOAD_Serializer::instance()->stringify('Example\Calculator') ?>

var obj = new Example.Calculator(1, 2);

obj.add(function(err, result) {
  console.log(result);
});

// Example.Calculator.add_static(2, 3, function(err, result) {
//   console.log(result);
// });
// ]]>
</script>
