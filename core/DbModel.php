<?php
namespace app\core;
use app\models\Model;

abstract class DbModel extends Model 
{
    abstract public function tableName() : string; 

    abstract public function attribute() : array; //
    
    public function save()
    {
        $tableName = $this->tableName(); // 테이블명 
        $attribute = $this->attribute(); // 모델객체의 멤버변수명
        
        // ex) INSERT INTO user (firstname, lastname, email ) values (:firstname, :lastname, :email ) 
        $column =  implode(",",$attribute); // firstname, lastname, email 
        $values = implode(",", array_map(fn($attr) => ":$attr",$attribute) ) ; // :firstname, :lastname, :email
        $stmt = self::prepare("INSERT INTO $tableName ( $column ) VALUES ( $values ) "); 
        
        foreach ($attribute as $attribute)
        {
            $stmt->bindValue(":$attribute", $this->{$attribute} ); 
        }
        $stmt->execute(); 
        return true; 
    }

    private static function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }
}