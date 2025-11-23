<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
  protected $table = 'users';
  protected $primaryKey = 'id';
  protected $fillable = ['name', 'email', 'password'];
  protected $hidden = ['password'];

  /**
   * Busca usuário por email
   */
  public function findByEmail($email)
  {
    return $this->findWhere(['email' => $email]);
  }

  /**
   * Cria um novo usuário com senha hash
   */
  public function createUser($data)
  {
    if (isset($data['password'])) {
      $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    }

    return $this->create($data);
  }

  /**
   * Verifica se o email já existe
   */
  public function emailExists($email)
  {
    return $this->findByEmail($email) !== false;
  }
}
