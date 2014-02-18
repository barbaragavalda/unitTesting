<?php

class Users
{
    private $errors = array();

    private $db = null;

    private $id = '';

    public function __construct( $id = '' )
    {
        $this->id = $id;

        $dsn = 'mysql:dbname=user;hostlocalhost';
        $db_user = 'root';
        $db_password = '';

        try {
            $this->db = new PDO($dsn, $db_user, $db_password);
        } catch (PDOException $e) {
            echo 'BD connection failed: ' . $e->getMessage();
        }
    }

    public function newUser()
    {
        if (!empty($_GET['user_name']) && !empty($_GET['password'])) {
            $this->insertUser($_GET['user_name'], $_GET['password']);
        } else {
            if (empty($_GET['user_name'])) {
                $this->errors[] = 'Invalid User name';
            }

            if (empty($_GET['password'])) {
                $this->errors[] = 'Invalid Password';
            }
        }
    }

    /**
     * Retorna la información de un usuario guardado en la base de datos. Si no existe lanza una excepción.
     * @throws InvalidArgumentException
     */
    public function getUserData()
    {
        $sql = 'SELECT id, username, password, num_actions FROM user WHERE id = '.$this->id.' LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if( !count($user) )
            throw new InvalidArgumentException('No user');
        else{
            unset($user[0]['id']);
            return $user[0];
        }
    }

    public function getID()
    {
        return $this->id;
    }

    /**
     * Inserta un usuario en la base de datos.
     * @param $name
     * @param $password
     * @throws Exception
     */
    public function insertUser($name, $password)
    {
        if( $name == 'NULL' ) $name = 'NULL';
        else $name = '"'.$name.'"';

        $sql = 'INSERT INTO user SET username = '.$name.', password = "'.$password.'"';
        $stmt = $this->db->prepare($sql);
        $ok = $stmt->execute();
        if( !$ok )
            throw new PDOException('Insert error');
        else{
            $this->id = $this->db->lastInsertId();
        }
    }

    /**
     * Inserta una acción en base de datos.
     * @throws InvalidArgumentException
     */
    public function insertUserAction()
    {
        $num_actions = $this->getActions($this->id);
        $sql = 'UPDATE user SET num_actions = '.($num_actions+1).' WHERE id = '.$this->id;
        $stmt = $this->db->prepare($sql);
        $ok = $stmt->execute();
        if( !$ok )
            throw new InvalidArgumentException('Error insert');
    }

    /**
     * Retorna un array de acciones. Si el usuario no tiene acciones retorna vacío.
     * @return num_actions
     * @throws PDOException
     */
    public function getActions()
    {
        $sql = 'SELECT num_actions FROM user WHERE id = '.$this->id.' LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $actions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if( count($actions) )
        {
            $num_actions = $actions[0]['num_actions'];
            if( $num_actions == '' ) $num_actions = 0;
            return $num_actions;
        }
        else
            throw new PDOException('No user');
    }

    /**
     * Nos devuelve el karma del usuario en función del número de acciones realizadas.
     * - Entre 0 y 10 -> devuelve 1
     * - Mayor que 10 y menor 100 -> devuelve 2
     * - Mayor de 100 y menor de 500 -> devuelve 3
     * - Mayor de 500 -> devuelve número de acciones entre 100
     */
    public function getUserKarma( $num_actions )
    {
        if( $num_actions <= 10 )
            return 1;
        elseif( $num_actions <= 100 )
            return 2;
        elseif( $num_actions <= 500 )
            return 3;
        else if( $num_actions > 500 )
            return $num_actions / 100;
    }
}
