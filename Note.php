<?php

include "Connection.php";

class Note extends Connection
{
    public function getNotes()
    {
        $sql = "SELECT * FROM notes";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function searchNotes($search)
    {
        $sql = "SELECT * FROM notes WHERE title LIKE ? OR description LIKE ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array("%$search%", "%$search%"));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function findNote($id)
    {
        $sql = "SELECT * FROM notes WHERE id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($id));
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function addNote($title, $description, $author_id){
        $sql = "INSERT INTO notes(title, description, author_id) VALUES(?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if($stmt->execute(array($title, $description, $author_id))){
            return true;
        }
        return false;
    }

    public function updateNote($title, $description, $author_id, $id){
        $sql = "UPDATE notes SET title=?, description=?, author_id=? WHERE id=?";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute(array($title, $description, $author_id, $id))) {
            return true;
        }
        return false;
    }

    public function deleteNote($id){
        $sql = "DELETE FROM notes WHERE id=?";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute(array($id))) {
            return true;
        }
        return false;
    }
}
