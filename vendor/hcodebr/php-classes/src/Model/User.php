<?php
namespace Hcode\Model ;
use \Hcode\DB\Sql ;
use \Hcode\Model;

class User extends Model{

	const SESSION = "User";

	public static function login($login ,$password){

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOG ", array(
			":LOG" => $login
		));

		if(count($results) === 0){
			throw new \Exception("Usuário ou senha inválidos.");	
		}
		

		$data = $results[0];

		$senha = password_hash($data['despassword'] , PASSWORD_DEFAULT);
		


		if (password_verify($password , $senha ) === true){

			$user = new User() ;

			$user->setData($data);

			$_SESSION[User::SESSION] = $user->getValues();

			return $user ;

		}else{
			throw new \Exception("Usuário ou senha inválidos.");
			
		}

	}

	public static function verifyLogin($inadmin = true){

		if (
			!isset($_SESSION[User::SESSION])
			|| 
			!$_SESSION[User::SESSION]
			||
			!(int)$_SESSION[User::SESSION]['iduser'] > 0 
			||(bool)$_SESSION[User::SESSION]['inadmin']  !== $inadmin
		) {
			header("Location: /admin/login");
			exit;
		}
	}

	public function logout(){
		$_SESSION[User::SESSION] = NULL;
	}

}

?>