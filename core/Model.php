<?php
/**
 * Created by PhpStorm.
 * User: jonni
 * Date: 01.12.17
 * Time: 20:23
 */

namespace core;


class Model
{
    private $config;
    protected $db;
    public $table_name;

    public function __construct()
    {
        $this->config = include (ROOT.'/config/config_db.php');
        $this->db = DB::connect();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get_by_id($id){
        $db = $this->db;
        $query = $db->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id');
        $query->execute([
            ':id' => $id
        ]);
        return $query->fetch(\PDO::FETCH_ASSOC);
    }
    /**
     * @return mixed
     */
    public function get_all(){
        $db = $this->db;
        $query = $db->prepare('SELECT * FROM ' . $this->table_name);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }


}