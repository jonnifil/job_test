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
     * @param array $data
     * @return $this
     */
    public function load(array $data){
        foreach ($data as $key=>$val){
            if (property_exists($this, $key))
                $this->$key = $val;
        }
        return $this;
    }

    /**
     * @return array
     */
    protected function get_column_names(){
        $db = $this->db;
        $query = $db->prepare("SELECT COLUMN_NAME FROM information_schema.columns WHERE table_name = :table_name AND table_schema = database()");
        $query->execute([
            ':table_name' => $this->table_name
        ]);
        $column_names = [];
        foreach ($query as $row){
            $column_names[] = $row['COLUMN_NAME'];
        }
        return $column_names;
    }

    /**
     *
     */
    public function save_obj(){
        if (isset($this->id) && (int)$this->id > 0)
            $this->update();
        else $this->insert();
    }

    /**
     * @return array
     */
    protected function prepare_save(){
        $column_names = $this->get_column_names();
        $data = (array)$this;
        $rows_str = '(';
        $prepare_str = '(';
        $update_str = '(';
        $execute_arr = [];
        foreach ($column_names as $name){
            if ($name != 'id'){
                $_name = ':' . $name;
                $execute_arr[$_name] = $data[$name];
                $rows_str .= count($execute_arr) == 1 ? $name : ',' . $name;
                $prepare_str .= count($execute_arr) == 1 ? $_name : ',' . $_name;
                $update_str .= count($execute_arr) == 1 ? $name . '=' . $_name : ',' . $name . '=' . $_name;
            }
        }
        return [
            'execute_arr' => $execute_arr,
            'rows_str' => $rows_str . ')',
            'prepare_str' => $prepare_str . ')',
            'update_str' => $update_str . ')',
        ];
    }

    /**
     * @return $this
     */
    protected function insert(){
        $prepare_data = $this->prepare_save();
        $rows_str = $prepare_data['rows_str'];
        $prepare_str = $prepare_data['prepare_str'];
        $execute_arr = $prepare_data['execute_arr'];
        $db = $this->db;
        $query = $db->prepare("INSERT INTO {$this->table_name} {$rows_str} VALUES {$prepare_str}");
        $query->execute($execute_arr);
        $id = $db->lastInsertId();
        $this->id = $id;
        return $this;
    }

    /**
     * @return $this
     */
    protected function update(){
        $prepare_data = $this->prepare_save();
        $update_str = $prepare_data['update_str'];
        $execute_arr = $prepare_data['execute_arr'];
        $execute_arr['id'] = $this->id;
        $db = $this->db;
        $query = $db->prepare("UPDATE {$this->table_name} SET {$update_str} WHERE id = :id");
        $query->execute($execute_arr);
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