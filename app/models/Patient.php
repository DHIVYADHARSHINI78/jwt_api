<?php
// app/models/Patient.php

class Patient {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

   
// app/models/Patient.php
public function create($name, $age, $gender, $contact, $disease, $address) {

    $query = "INSERT INTO patients (name, age, gender, contact, disease, address) 
              VALUES (:name, :age, :gender, :contact, :disease, :address)";
    
    $stmt = $this->db->prepare($query);
    
    return $stmt->execute([
        ':name' => $name,
        ':age' => $age,
        ':gender' => $gender,
        ':contact' => $contact,
        ':disease' => $disease,
        ':address' => $address
    ]);
}

public function update($id, $name, $age, $gender, $disease, $contact) {
  
    $query = "UPDATE patients SET name = :name, age = :age, gender = :gender, disease = :disease, contact = :contact WHERE id = :id";
    $stmt = $this->db->prepare($query);
    
   
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':age', $age);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':disease', $disease);
    $stmt->bindParam(':contact', $contact);
    $stmt->bindParam(':id', $id);
    
    return $stmt->execute();
}
public function patchUpdate($id, $data) {
    if (empty($data)) return false; //

    $fields = "";
    foreach ($data as $key => $value) {
        $fields .= "$key = :$key, ";
    }
    $fields = rtrim($fields, ", ");

  
    $query = "UPDATE patients SET $fields WHERE id = :id";
    $stmt = $this->db->prepare($query);
    
    foreach ($data as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }
    $stmt->bindValue(":id", $id);
    
    return $stmt->execute();
}
public function delete($id) {
    
    $query = "DELETE FROM patients WHERE id = :id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM patients");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}