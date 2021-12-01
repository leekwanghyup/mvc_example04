<?php 
namespace app\core\form;

use app\models\Model;

class Form 
{

    public static function begin($action, $method)
    {
        echo "<form action='$action' method='$method'>";
        return new Form(); 
    }

    public static function end()
    {
        return"</form>";
    }

    public function field($model,$attribute,$filedname)
    {
        return new Field($model,$attribute,$filedname); 
    }
}

?>