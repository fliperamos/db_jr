<?php


namespace Source\Models;


use Source\Controller\Model;

/**
 * Class Client
 * @package Source\Models
 */
class Client extends Model
{
    /** @var array $safe no update or create */
    protected static $safe = ['id', 'created_at', 'updated_at'];
    /** @var  string $entity database table */
    protected static $entity = "client";


    /**
     *  Create db data
     * @param string $f_name
     * @param string $l_name
     * @param string $email
     * @param string $birth_d
     * @param string $rg
     * @param string $cpf
     * @param string $tel
     * @param string $cel
     * @param string $street
     * @param string $number
     * @param string $city
     * @param string $state
     * @param string $district
     * @param string $zip
     * @return $this|null
     */
    public function createClient(
        string $f_name,
        string $l_name,
        string $email,
        string $birth_d,
        string $rg,
        string $cpf,
        string $tel,
        string $cel,
        string $street = null,
        $number = null,
        $city = null,
        $state = null,
        $district = null,
        $zip = null
    ): ?Client
    {
        $this->f_name = $f_name;
        $this->l_name = $l_name;
        $this->email = $email;
        $this->birth_d = $birth_d;
        $this->rg = $rg;
        $this->cpf = $cpf;
        $this->tel = $tel;
        $this->cel = $cel;
        $this->street = $street;
        $this->number = $number;
        $this->city = $city;
        $this->state = $state;
        $this->district = $district;
        $this->zip = $zip;
        return $this;
    }


    /**
     * @param int $id
     * @param string $columns
     * @return User|null
     */
    public function loadClient(int $id, string $columns = "*"): ?User
    {
        $loadClient = $this->read("SELECT {$columns} FROM " . self::$entity . " WHERE id = :id", "id={$id}");
        if ($this->fail || !$loadClient->rowCount()) {
            $this->message = "Usuario não encontrado para o id informado";
            return null;
        }

        return $loadClient->fetchObject(__CLASS__);
    }

    /**
     * @param $cpf
     * @param string $columns
     * @return mixed|null
     */
    public function findClient($cpf, string $columns = "*")
    {
        $findClient = $this->read("SELECT {$columns} FROM " . self::$entity . " WHERE cpf = :cpf", "cpf={$cpf}");
        if ($this->fail || !$findClient->rowCount()) {
            $this->message = "Usuario não encontrado para o id informado";
            return null;
        }
        return $findClient->fetchObject(__CLASS__);
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

    /** @return $this|null */
    public function saveClient()
    {

        if (!$this->requiredClient()) {
            return null;
        }
        /** User Update */
        if (empty($this->id)) {
            $userID = $this->id;
            $cpf = $this->read("SELECT id FROM client WHERE cpf = :cpf AND id != :id",
                "cpf={$this->cpf}&id={$userID}");
        }
        if ($cpf->rowCount()) {
            $this->message = "O CPF cadastrado já está em uso!";
            return null;
        }

        if (empty($this->id)) {
            $userID = $this->id;
            $rg = $this->read("SELECT id FROM client WHERE rg = :rg AND id != :id",
                "rg={$this->rg}&id={$userID}");
        }

        if ($rg->rowCount()) {
            $this->message = "O Rg cadastrado já está em uso!";
            return null;
        }

        $this->update(self::$entity, $this->safe(), "id = :id", "id={$userID}");
        if ($this->fail) {
            $this->message = "Erro ao atualizar, verifique os dados";
        }

        $this->message = "Dados atualizados com sucesso";


        /** User Create */
        if (empty($this->id)) {
            if ($this->findClient($this->cpf)) {
                $this->message = "O CPF ja esta cadastrado";
                return null;
            }


            $userID = $this->create(self::$entity, $this->safe());
            if ($this->fail()) {
                $this->message = "Erro ao cadastra, verifique os dados";
            }

            $this->message = "Cadastro realizado com sucesso";
        }
        $this->data = $this->read("SELECT * FROM client WHERE id = :id", "id={$userID}")->fetch();
        return $this;
    }

    /**
     * @return $this|null
     */
    public function destroyClient(): ?Client
    {
        if (!empty($this->id)) {
            $this->delete(self::$entity, "id=:id", "id={$this->id}");
        }

        if ($this->fail) {
            $this->message = "Não foi possível remover o usuário";
            return null;
        }

        $this->message = "O usuario foi removido com sucesso";
        $this->data = null;
        return $this;
    }


    /**
     * @return bool
     */
    private function requiredClient()
    {
        if (empty($this->f_name) ||
            empty($this->l_name) ||
            empty($this->email) ||
            empty($this->birth_d) ||
            empty($this->rg) ||
            empty($this->cpf) ||
            empty($this->rg) ||
            empty($this->tel) ||
            empty($this->cel)) {
            $this->message = "Por favor preencha os dados obrigatorios";
            return false;
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->message = "O e-mail informado não parece válido";
            return false;
        }
        return true;
    }

}