<?php 
namespace app\core\form;

use app\models\Model;

class Field 
{   
    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';
    public const TYPE_EMAIL = 'email';

    public string $type; 
    public Model $model;
    public string $attribute; 
    public string $fieldname; 

    public function __construct($model, $attribute,$fieldname)
    {
        $this->type = self::TYPE_TEXT;
        $this->model = $model; 
        $this->attribute = $attribute;  
        $this->fieldname = $fieldname;
    }

    public function __toString()
    {
        $value = $this->model->{$this->attribute} ?? '';
        $hasError = $this->model->hasError($this->attribute) ? ' is-invalid' : '';
        $errorMessage = $this->model->getFirstError($this->attribute);
        return 
        "<tr>
            <td> {$this->fieldname}</td>
            <td>
                <input type='{$this->type}' name='{$this->attribute}' value='{$value}' class='$hasError' >
                <span class='invalid-feedback'> $errorMessage </span></td>
            </td>
        </tr>"; 
    }  

    public function passwordField()
    {
        $this->type = self::TYPE_PASSWORD; 
        return $this; 
    }
    public function emailField(){
        $this->type = self::TYPE_EMAIL;
        return $this; 
    }
    
}



?>



