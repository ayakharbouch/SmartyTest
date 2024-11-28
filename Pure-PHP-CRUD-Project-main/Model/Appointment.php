<?php
// models/Appointment.php

class Appointment {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Create appointment
    public function create($name, $mob, $date, $doctor, $department) {
        $query = 'INSERT INTO appointments (name, mob, date, doctor, department) 
                  VALUES (:name, :mob, :date, :doctor, :department)';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':mob', $mob);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':doctor', $doctor);
        $stmt->bindParam(':department', $department);
        
        return $stmt->execute();
    }

    // Get all appointments
    public function getAll() {
        $query = 'SELECT * FROM appointments';
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get appointment by ID
    public function getById($id) {
        $query = 'SELECT * FROM appointments WHERE id = :id';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update appointment by ID
    public function update($id, $name, $mob, $date, $doctor, $department) {
        $query = 'UPDATE appointments SET name = :name, mob = :mob, date = :date, 
                  doctor = :doctor, department = :department WHERE id = :id';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':mob', $mob);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':doctor', $doctor);
        $stmt->bindParam(':department', $department);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    // Delete appointment by ID
    public function delete($id) {
        $query = 'DELETE FROM appointments WHERE id = :id';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
}
?>
