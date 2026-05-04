<?php
require_once 'models/Patient.php';

class PatientController {
    private $patientModel;

    public function __construct($db) {
        $this->patientModel = new Patient($db);
    }

    public function index() {
        $stmt = $this->patientModel->readAll();
        $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require 'views/index.php';
    }

    public function create() {
        require 'views/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validation cơ bản
            if(empty($_POST['patient_code']) || empty($_POST['full_name'])) {
                die("Error: Patient Code and Full Name are required.");
            }

            $data = [
                ':code' => $_POST['patient_code'],
                ':name' => $_POST['full_name'],
                ':dob' => $_POST['date_of_birth'],
                ':gender' => $_POST['gender'],
                ':phone' => $_POST['phone'],
                ':address' => $_POST['address']
            ];
            
            if ($this->patientModel->create($data)) {
                header("Location: index.php");
            }
        }
    }

    public function edit($id) {
        $patient = $this->patientModel->getById($id);
        require 'views/edit.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                ':id' => $_POST['id'],
                ':code' => $_POST['patient_code'],
                ':name' => $_POST['full_name'],
                ':dob' => $_POST['date_of_birth'],
                ':gender' => $_POST['gender'],
                ':phone' => $_POST['phone'],
                ':address' => $_POST['address']
            ];
            
            if ($this->patientModel->update($data)) {
                header("Location: index.php");
            }
        }
    }

    public function delete($id) {
        if ($this->patientModel->delete($id)) {
            header("Location: index.php");
        }
    }
}
?>