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
    protected $db;
    protected $query_string;
    protected $row_data = array();
    public $table_name;
    public $alias;

    public function __construct()
    {
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
        $this->row_data = $query->fetchAll(\PDO::FETCH_ASSOC);
        return $this;
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

    /**
     * @param string $field_list
     * @param string $alias
     * @return $this
     */
    public function select($field_list='*', $alias=''){
        $this->query_string = 'SELECT ' . $field_list . ' FROM ' . $this->table_name . ' ' . $alias;
        return $this;
    }

    /**
     * @param array $where
     * @return $this
     */
    public function set_where(array $where){
        $this->query_string .= " WHERE {$where['field']} {$where['compare']} {$where['value']}";
        return $this;
    }

    /**
     * @param array $where
     * @return $this
     */
    public function set_and_where(array $where){
        $this->query_string .= " AND {$where['field']} {$where['compare']} {$where['value']}";
        return $this;
    }

    /**
     * @param array $where
     * @return $this
     */
    public function set_or_where(array $where){
        $this->query_string .= " OR {$where['field']} {$where['compare']} {$where['value']}";
        return $this;
    }

    /**
     * @param array $join
     * @return $this
     */
    public function set_join(array $join){
        $this->query_string .= " JOIN {$join['table_name']} {$join['table_alias']} ON {$join['field']} {$join['compare']} {$join['value']}";
        return $this;
    }

    /**
     * @param array $join
     * @return $this
     */
    public function set_left_join(array $join){
        $this->query_string .= " LEFT JOIN " .$join['table_name']." ".$join['table_alias']." ON ".$join['field']." ".$join['compare']." ".$join['value'];
        return $this;
    }

    /**
     * @return $this
     */
    public function set_get_all(){
        $db = $this->db;
        $query = $db->prepare($this->query_string);
        $query->execute();
        $this->row_data = $query->fetchAll(\PDO::FETCH_ASSOC);
        return $this;
    }

    /**
     * @return array
     */
    public function as_array(){
        return $this->row_data;
    }

    /**
     * @param array $query_rows //$query->fetchAll(\PDO::FETCH_ASSOC)!!!
     * @return array
     * Принимает массив строк выборки типа get_all, Возвращает массив экземпляров текущей модели
     */
    public function as_collection(){
        $query_rows = $this->row_data;
        if(!$query_rows)
            return array();
        $result = array();
        foreach ($query_rows as $row) {
            foreach ($row as $key=>$val){
                $this->$key = $val;
            }
            $result[] = $this;
        }
        return $result;
    }

    /**
     * @param array $query_rows
     * @param string $field
     * @return array
     *
     */
    public function as_ids($field='id'){
        $query_rows = $this->row_data;
        if(!$query_rows)
            return array();
        $result = array();
        foreach ($query_rows as $row) {
            $result[] = $row[$field];
        }
        return array_unique($result);
    }

    /**
     * @param array $query_rows
     * @param string $field
     * @param string $separator
     * @return string
     */
    public function as_group_concat($field='id', $separator=','){
        $query_rows = $this->row_data;
        if(!$query_rows)
            return '';
        $result = array_shift($query_rows)[$field];
        foreach ($query_rows as $row) {
            $result .= $separator.$row[$field];
        }
        return $result;
    }

}