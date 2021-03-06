<?php
/**
 * Created by PhpStorm.
 * User: jonni
 * Date: 01.12.17
 * Time: 20:29
 */

namespace application\models;


use core\Model;

class News extends Model
{
    public $table_name = 'news';
    public $id;
    public $user_id;
    public $title;
    public $annotate;
    public $body;

    /**
     * @param $user_id
     * @return mixed
     */
    public function get_by_user($user_id){
        $db = $this->db;
        $query = $db->prepare('SELECT * FROM news WHERE user_id = :user_id');
        $query->execute([
            ':user_id' => $user_id
        ]);
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function get_title(){
        return $this->title;
    }


    /**
     * @param $args
     * @return mixed
     * @throws \Exception
     */
    public function save($args){
        $user_id = $args['user_id'];
        $title = trim($args['title']);
        $annotate = trim($args['annotate']);
        $body = trim($args['body']);
        if($title == '' || $annotate == '')
            throw new \Exception('Поля Название и Краткое описание должны быть заполнены!');
        $db = $this->db;
        $query = $db->prepare('INSERT INTO news(user_id, title, annotate, body) VALUES (:user_id, :title, :annotate, :body)');
        $query->execute([
            ':user_id' => $user_id,
            ':title' => $title,
            ':annotate' => $annotate,
            ':body' => $body
        ]);
        $id = $db->lastInsertId();
        return $id;
    }

    /**
     * @param $args
     * @return mixed
     * @throws \Exception
     */
    public function update($args){
        $id = $args['id'];
        $title = trim($args['title']);
        $annotate = trim($args['annotate']);
        $body = trim($args['body']);
        if($title == '' || $annotate == '')
            throw new \Exception('Поля Название и Краткое описание должны быть заполнены!');
        $db = $this->db;
        $query = $db->prepare('UPDATE news SET title = :title, annotate = :annotate, body = :body WHERE id = :id');
        $query->execute([
            ':id' => $id,
            ':title' => $title,
            ':annotate' => $annotate,
            ':body' => $body
        ]);
        return $id;
    }

    /**
     * @param $id
     */
    public function delete($id){
        $db = $this->db;
        $query = $db->prepare('DELETE FROM news WHERE id = :id LIMIT 1');
        $query->execute([
            ':id' => $id
        ]);
    }

    public function get_annotate_list(){
        $this
            ->select('n.id,n.title,n.annotate,u.login','n')
            ->set_join(['table_name'=>'user','table_alias'=>'u','field'=>'n.user_id','compare'=>'=','value'=>'u.id']);
        return $this->set_get_all();
    }
}