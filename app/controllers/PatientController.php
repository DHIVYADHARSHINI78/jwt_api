<?php


class PatientController {
    

    public function index() {
        $patientModel = new Patient();
        $patients = $patientModel->getAll();
        
        Response::json([
            "requested_by" => $GLOBALS['user']['email'] ?? 'Unknown User', 
            "data" => $patients
        ]);
    }

    
public function create() {
    $data = $GLOBALS['request_data'];

    // All fields validation
    if (empty($data['name']) || empty($data['contact']) || empty($data['disease'])) {
        Response::json(['error' => 'Name, Contact, and Disease are required'], 400);
        return;
    }

    $patient = new Patient();
    $success = $patient->create(
        $data['name'], 
        $data['age'], 
        $data['gender'], 
        $data['contact'], 
        $data['disease'], 
        $data['address']
    );

    if ($success) {
        Response::json(['message' => 'Patient added successfully'], 201);
    } else {
        Response::json(['error' => 'Failed to add patient'], 500);
    }
}
  
    public function update() {
        $id = $_GET['id'] ?? null;
        $data = $GLOBALS['request_data'];

        if (!$id) {
            Response::json(['error' => 'Patient ID is missing'], 400);
            return;
        }

        $patientModel = new Patient();
        $success = $patientModel->update(
            $id, 
            $data['name'], 
            $data['age'], 
            $data['gender'], 
            $data['disease'], 
            $data['contact']
        );

        if ($success) {
            Response::json(['message' => 'Patient updated successfully']);
        } else {
            Response::json(['error' => 'Update failed'], 500);
        }
    }
   public function patch() {
    $id = $_GET['id'] ?? null;
    
 
    $data = $GLOBALS['request_data'] ?? json_decode(file_get_contents("php://input"), true); 

    if (!$id || empty($data)) {
        Response::json(['error' => 'Patient ID or data is missing'], 400);
        return;
    }

    $patientModel = new Patient();
    $success = $patientModel->patchUpdate($id, $data);

    if ($success) {
        Response::json(['message' => 'Patient partially updated successfully']);
    } else {
        Response::json(['error' => 'Patch update failed'], 500);
    }
}
    // 4. DELETE /api/patients - Delete Patient
    public function delete() {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            Response::json(['error' => 'Patient ID is required'], 400);
            return;
        }

        $patientModel = new Patient();
        if ($patientModel->delete($id)) {
            Response::json(['message' => 'Patient deleted successfully']);
        } else {
            Response::json(['error' => 'Delete failed'], 500);
        }
    }
}