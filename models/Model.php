<?php 
namespace app\models;

abstract class Model
{
    public const RULE_REQUIRED = 'required'; 
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max'; 
    public const RULE_MATCH = 'match';

    public array $errors = [];

    public function dataload($data)
    {
        foreach($data as $key => $value){
            if(property_exists($this,$key)){
                $this->$key = $value; 
            }   
        }
    }

    public function validate()
    {
        foreach ($this->rules() as $attribute => $rules)
        {
            $value = $this->$attribute; // input에 입력한 값 
            foreach ($rules as $rule)
            {
                $ruleName = $rule; //   [ self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password'] ]
                if(is_array($ruleName)) // 배열인 경우 
                {
                    $ruleName = $rule[0]; 
                }
                if($ruleName === self::RULE_REQUIRED && !$value)
                {
                    $this->addError($attribute, self::RULE_REQUIRED);
                }
                if($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL))
                {
                    $this->addError($attribute, self::RULE_EMAIL);
                }
                if($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->addError($attribute, self::RULE_MAX, $rule); 
                }
                if($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->addError($attribute, self::RULE_MIN, $rule) ; 
                }
                if($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}){ // $this->{$rule['match']}
                    $this->addError($attribute, self::RULE_MATCH, $rule);
                }
            }
        } 
        return empty($this->errors);
    }

    public function addError(string $attribute, string $rule, $parma=[])
    {
        $message = $this->errorMessage()[$rule] ?? '';
        foreach ($parma as $key => $value){
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][]= $message; 
    }

    public function errorMessage()
    {
        return
        [
            self::RULE_REQUIRED => 'This filed is required', 
            self::RULE_EMAIL => 'This field must be valid email address', 
            self::RULE_MIN => 'Min length of this field must be {min}', 
            self::RULE_MAX => 'Max length of this field must be {max}', 
            self::RULE_MATCH => 'This field must be the same as {match}', 
        ]; 
    }

    public function hasError($attribute)
    {
        return $this->errors[$attribute] ?? false; 
    }

    public function getFirstError($attribute)
    {
        return $this->errors[$attribute][0] ?? false; 
    }

    abstract public function rules() : array ;
}