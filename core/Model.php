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
    public $class_name;

    public function __construct()
    {
        $this->config = include (ROOT.'/config/config_db.php');
        $this->db = DB::connect();
    }

    /**
     * @param $id
     * @return mixed
     * Возвращает строку таблицы в виде ассоциативного массива
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
     * Возвращает данные таблицы в виде ассоциативного массива
     */
    public function get_all(){
        $db = $this->db;
        $query = $db->prepare('SELECT * FROM ' . $this->table_name);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $id
     * @return $this|null
     * Возвращает экземпляр объекта модели
     */
    public function get($id){
        $values = $this->get_by_id($id);
        if(!$values)
            return null;
        foreach ($values as $key=>$val){
            $this->$key = $val;
        }
        return $this;
    }


}