
CREATE DATABASE IF NOT EXISTS hospital_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE hospital_db;

DROP TABLE IF EXISTS appointments;
DROP TABLE IF EXISTS patients;

CREATE TABLE patients (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    patient_code    VARCHAR(20)  UNIQUE NOT NULL,
    full_name       VARCHAR(100) NOT NULL,
    date_of_birth   DATE,
    gender          ENUM('Male', 'Female', 'Other'),
    phone           VARCHAR(20),
    address         VARCHAR(200)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE appointments (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    patient_id       INT          NOT NULL,
    doctor_name      VARCHAR(100) NOT NULL,
    appointment_date DATETIME     NOT NULL,
    department       VARCHAR(100) NOT NULL,
    reason           TEXT,
    status           ENUM('Scheduled', 'Completed', 'Cancelled') DEFAULT 'Scheduled',
    CONSTRAINT fk_patient
        FOREIGN KEY (patient_id) REFERENCES patients(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO patients (patient_code, full_name, date_of_birth, gender, phone, address) VALUES
('PT-2024-001', 'Nguyen Van An',    '1985-03-12', 'Male',   '0912345678', '15 Le Loi, Hoan Kiem, Ha Noi'),
('PT-2024-002', 'Tran Thi Bich',    '1992-07-25', 'Female', '0987654321', '42 Nguyen Hue, District 1, Ho Chi Minh'),
('PT-2024-003', 'Le Minh Cuong',    '1978-11-05', 'Male',   '0934567890', '8 Tran Phu, Hai Chau, Da Nang'),
('PT-2024-004', 'Pham Thi Dung',    '2000-01-30', 'Female', '0901234567', '77 Hung Vuong, Hue City'),
('PT-2024-005', 'Hoang Van Em',     '1965-09-18', 'Male',   '0978123456', '23 Phan Dinh Phung, Can Tho'),
('PT-2024-006', 'Do Thi Phuong',    '1990-05-22', 'Female', '0956789012', '101 Bach Dang, Hai Phong'),
('PT-2024-007', 'Vu Thanh Giang',   '1988-12-09', 'Male',   '0923456789', '56 Ly Thuong Kiet, Ha Noi'),
('PT-2024-008', 'Bui Thi Hoa',      '1975-04-14', 'Female', '0945678901', '30 Dien Bien Phu, Da Nang'),
('PT-2024-009', 'Nguyen Duc Hung',  '1995-08-03', 'Male',   '0967890123', '18 CMT8, Bien Hoa, Dong Nai'),
('PT-2024-010', 'Cao Thi Mai',      '2003-06-27', 'Female', '0912098765', '9 Nguyen Trai, Thanh Xuan, Ha Noi');

INSERT INTO appointments (patient_id, doctor_name, appointment_date, department, reason, status) VALUES
(1,  'Dr. Tran Quoc Bao',   '2025-05-10 09:00:00', 'Cardiology',  'Chest pain and shortness of breath',     'Completed'),
(2,  'Dr. Le Thi Lan',      '2025-05-12 10:30:00', 'Obstetrics',  'Routine prenatal check-up',              'Completed'),
(3,  'Dr. Nguyen Huu Duc',  '2025-05-15 08:00:00', 'Orthopedics', 'Knee pain after sports injury',          'Completed'),
(4,  'Dr. Pham Van Khanh',  '2025-05-20 14:00:00', 'Dermatology', 'Skin rash and itching for 2 weeks',      'Cancelled'),
(5,  'Dr. Tran Quoc Bao',   '2025-05-22 11:00:00', 'Cardiology',  'Hypertension follow-up',                 'Completed'),
(6,  'Dr. Ho Thi Ngoc',     '2025-06-01 09:30:00', 'Neurology',   'Frequent headaches and dizziness',       'Scheduled'),
(7,  'Dr. Vuong Minh Thai', '2025-06-03 15:00:00', 'General',     'Annual health check-up',                 'Scheduled'),
(8,  'Dr. Le Thi Lan',      '2025-06-05 10:00:00', 'Obstetrics',  'Menstrual irregularities',               'Scheduled'),
(9,  'Dr. Nguyen Huu Duc',  '2025-06-08 13:00:00', 'Orthopedics', 'Lower back pain, possible slipped disc', 'Scheduled'),
(10, 'Dr. Pham Van Khanh',  '2025-06-10 08:30:00', 'Dermatology', 'Acne treatment consultation',            'Scheduled');
