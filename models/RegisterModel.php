<?php 
namespace app\models;

use app\core\DbModel;

class RegisterModel extends DbModel
{
    const STATUS_INATIVE = 0; 
    const STATUS_ACTIVE = 1; 
    const STATUS_DELETED = 2; 

    public string $firstname=''; 
    public string $lastname=''; 
    public string $email='';
    public string $password=''; 
    public string $confirmPassword='';
    public int $status = self::STATUS_INATIVE; 
    
    public function save()
    {
        $this->status = self::STATUS_INATIVE;
        $this->password = password_hash($this->password, PASSWORD_DEFAULT); 
        return parent::save(); 
    }

    public function rules() : array
    {
        return [
            'firstname' => [ self::RULE_REQUIRED,[self::RULE_MIN, 'min'=>6],[self::RULE_MAX, 'max'=>24] ],  
            'lastname' => [ self::RULE_REQUIRED ],
            'email' => [ self::RULE_REQUIRED, self::RULE_EMAIL, 
                [self::RULE_UNIQUE, 'class' => self::class, 'attribute'=> 'email'] 
            ],
            'password' => [ self::RULE_REQUIRED, [self::RULE_MIN, 'min'=> 8 ], [self::RULE_MAX, 'max'=>24] ],
            'confirmPassword' => [ self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password'] ]
        ];
    }

    public function tableName(): string
    {
        return "users";
    }

    public function attribute(): array
    {
        $className = get_class($this);
        $attr_value = get_class_vars($className); 
        $attr = array_keys($attr_value); 
        return array_diff($attr, ['confirmPassword','errors']); 
    }
}