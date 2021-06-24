<?php


namespace Source\Models;


use Source\Controller\Model;

/**
 * Class User
 * @package Source\Models
 */
class User extends Model
{
    /** @var array $safe no update or create */
    protected static $safe = ['id', 'created_at', 'updated_at'];

    /** @var string $entity database table */
    protected static $entity = "user";

    /**
     * @param string $user
     * @param string $passwd
     * @return $this|null
     */
    public function bootstrap(string $user, string $passwd): ?User
    {
        $this->user = $user;
        $this->passwd = $passwd;
        return $this;
    }

    /**
     * @param int $id
     * @param string $columns
     * @return User|null
     */
    public function load(int $id, string $columns = "*"): ?User
    {
        $load = $this->read("SELECT {$columns} FROM " . self::$entity . " WHERE id = :id", "id={$id}");
        if ($this->fail || !$load->rowCount()) {
            $this->message = "Usuario não encontrado para o id informado";
            return null;
        }
        return $load->fetchObject(__CLASS__);
    }


    /**
     * @param $user
     * @param string $columns
     * @return User|null
     */
    public function find($user, string $columns = "*"): ?User
    {
        $find = $this->read("SELECT {$columns} FROM " . self::$entity . " WHERE user = :user", "email={$user}");
        if ($this->fail || !$find->rowCount()) {
            $this->message = "Usuario não encontrado para o e-mail informado";
            return null;
        }
        return $find->fetchObject(__CLASS__);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @param string $columns
     * @return array|null
     */
    public function all(int $limit = 30, int $offset = 0, string $columns = "*"): ?array
    {
        $all = $this->read("SELECT {$columns} FROM " . self::$entity . " LIMIT :l OFFSET :o", "l={$limit}&o={$offset}");
        if ($this->fail || !$all->rowCount()) {
            $this->message = "Sua consulta não retornou usuarios";
            return null;
        }
        return $all->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    /**
     * @return $this|null
     */
    public function save()
    {
        if (empty($this->id)) {
            $userID = $this->id;
            $user = $this->read("SELECT id FROM user WHERE user = :user AND id != :id",
                "user={$this->user}&id={$userID}");
        }
        if ($user->rowCount()) {
            $this->message = "O Usuario cadastrado já está em uso!";
            return null;
        }
        /** User Create */
        if (empty($this->id)) {
            if ($this->find($this->user)) {
                $this->message = "O usuario ja existe";
                return null;
            }
            $userID = $this->create(self::$entity, $this->safe());
            if ($this->fail()) {
                $this->message = "Erro ao cadastrar, verifique os dados";
            }
            $this->message = "Cadastro realizado com sucesso";
        }
        $this->data = $this->read("SELECT * FROM user WHERE id = :id", "id={$userID}");
        return $this;
    }

    /**
     * @return $this|null
     */
    public function destroy(): ?User
    {
        if (!empty($this->id)) {
            $this->delete(self::$entity, "id = :id", "id={$this->id}");
        }
        if ($this->fail()) {
            $this->message = "Não foi possível remover o usuário";
            return null;
        }
        $this->message = "Usuario removido com sucesso";
        $this->data = null;
        return $this;

    }


}