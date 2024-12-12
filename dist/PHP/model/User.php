<?php
namespace Model\UserModel;
use Pet\Model\Model;
use Pet\Request\Request;


class UserModel extends Model{

    public $table = "user";
    public $insert = true;
    public $join = ['company_id'];

    function index(Request $request){

    }

    function getUserEmail($Email){
       return $this->find(['email'=> $Email]);
    }

    function user($id)
    {
        return $this->find(['id'=>$id],['company_id', 'active', 'job', 'name'])[0];
    }

    function getAllUsers(array $company_id = [], $select = []):array
    {
        return $this->find($company_id, $select);
    }

    function set($data = null):bool|string
    {
        if(count($this->find(['email'=>$data['email']])) == 0){

            $this->insert = true;
            $password = $this->generatePassword();
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            $data['job'] = 'admin';
            $this->insert($data);
            return $password;
        }else{
            return false;
        }

    }

    function updatePass($email):string{
        $this->insert = true;
        $password = $this->generatePassword();
        $this->findUpdate(['email'=>$email], ['password'=>password_hash($password, PASSWORD_DEFAULT), "auth"=>""]);
        return $password;
    }

    private function generatePassword(){
            $length = rand(6, 15);
            $password = '';

            $arr = array(
                'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'm',
                'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
                'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M',
                'N',  'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
                '1', '2', '3', '4', '5', '6', '7', '8', '9',
            );

            for ($i = 0; $i < $length; $i++) {
                $password .= $arr[rand(0, count($arr) - 1)];
            }

            return $password;
        }
}
?>