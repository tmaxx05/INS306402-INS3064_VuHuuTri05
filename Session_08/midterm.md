# MIDTERM EXAM - PHP WEB APPLICATION WITH DATABASE

**Duration: 60 minutes | Total: 100 points**

***

## **EXAM 3: LIBRARY BOOK BORROWING MANAGEMENT SYSTEM**

### **REAL-WORLD PROBLEM STATEMENT**

A university library needs a simple system to manage **books** and **borrow transactions**. The system will be used to demo how librarians can track available books and record borrowing activity for students.

**Business Requirements:**

- Manage a list of **books** in the library.
- Record **borrow transactions** for each book.
- Show a dashboard to **add, edit, delete, and view** records.
- Use only **one main entity** for CRUD operations.

***

### **PART 1: DATABASE DESIGN (20 points)**

Create **2 related tables** with proper keys and constraints:

**1. books**

- `id` (INT, Primary Key, Auto Increment): Book ID.
- `isbn` (VARCHAR(20), Unique, Not Null): International Standard Book Number.
- `title` (VARCHAR(150), Not Null): Book title.
- `author` (VARCHAR(100), Not Null): Author name.
- `publisher` (VARCHAR(100)): Publisher name.
- `publication_year` (INT): Year of publication.
- `available_copies` (INT, Not Null): Number of copies currently available.

**2. borrow_transactions**

- `id` (INT, Primary Key, Auto Increment): Transaction ID.
- `book_id` (INT, Foreign Key → books.id, Not Null): Related book.
- `borrower_name` (VARCHAR(100), Not Null): Name of the borrower.
- `borrow_date` (DATE, Not Null): Date of borrowing.
- `due_date` (DATE, Not Null): Return deadline.
- `return_date` (DATE, Nullable): Actual return date.
- `status` (ENUM('Borrowed', 'Returned', 'Overdue')): Borrow status.

**Sample Data Requirement:**

- Insert approximately **10 sample records** into the database.
- The data must reflect a real library borrowing scenario.

***

### **PART 2: PHP + PDO APPLICATION (30 points)**

Create a PHP application with the following features:

- Connect to the database using **PDO**.
- Build a **dashboard** to manage **books**.
- Implement **CRUD** operations for the `books` table only.
- Display book records in a clear HTML table.
- Provide forms to add and edit book information.
- Include delete confirmation before removing data.
- Validate required fields before saving records.

**Technical Requirements:**

- Use **prepared statements**.
- Handle database errors properly.
- Keep the code clean and readable.
- Make the interface responsive for mobile and desktop.

***

### **PART 3: VIVA QUESTIONS (50 points)**

***

## **EXAM 4: HOSPITAL PATIENT APPOINTMENT MANAGEMENT SYSTEM**

### **REAL-WORLD PROBLEM STATEMENT**

A small hospital wants a web system to manage **patients** and their **appointments** with doctors. The system should support a demo for reception staff to handle patient records and appointment scheduling.

**Business Requirements:**

- Manage **patient information**.
- Record **appointment history** for each patient.
- Provide a dashboard for **CRUD operations**.
- Apply CRUD to only **one main entity**.

***

### **PART 1: DATABASE DESIGN (20 points)**

Create **2 related tables** with proper database structure:

**1. patients**

- `id` (INT, Primary Key, Auto Increment): Patient ID.
- `patient_code` (VARCHAR(20), Unique, Not Null): Patient identification code.
- `full_name` (VARCHAR(100), Not Null): Patient full name.
- `date_of_birth` (DATE): Date of birth.
- `gender` (ENUM('Male', 'Female', 'Other')): Gender.
- `phone` (VARCHAR(20)): Phone number.
- `address` (VARCHAR(200)): Home address.

**2. appointments**

- `id` (INT, Primary Key, Auto Increment): Appointment ID.
- `patient_id` (INT, Foreign Key → patients.id, Not Null): Related patient.
- `doctor_name` (VARCHAR(100), Not Null): Doctor in charge.
- `appointment_date` (DATETIME, Not Null): Appointment schedule.
- `department` (VARCHAR(100), Not Null): Medical department.
- `reason` (TEXT): Reason for visit.
- `status` (ENUM('Scheduled', 'Completed', 'Cancelled')): Appointment status.

**Sample Data Requirement:**

- Insert approximately **10 sample records** into the database.
- Include realistic patients and appointment entries.

***

### **PART 2: PHP + PDO APPLICATION (30 points)**

Create a PHP-based application with these functions:

- Connect to the database using **PDO**.
- Display a **dashboard** for managing **patients**.
- Implement **CRUD** operations for the `patients` table only.
- Show patient data in a table with action buttons.
- Allow adding, editing, and deleting patient records.
- Ensure form inputs are validated before submission.

**Technical Requirements:**

- Use **prepared statements**.
- Apply proper error handling.
- Keep the code simple, clear, and maintainable.
- Design a basic responsive interface.

***

### **PART 3: VIVA QUESTIONS (50 points)**

***

## **GRADING CRITERIA**

| Criteria | Points | Description |
| :-- | --: | :-- |
| Source Code | 10 | Clear, standard, and readable code |
| Functionality | 15 | Complete and correct CRUD operations |
| User Interface | 15 | Basic but good-looking, responsive design |
| Data Validation | 10 | Ensures valid data during Add/Edit actions |
| Database Design | 10 | Proper tables, keys, and sample data |
| Viva Questions | 40 | Correct and clear oral explanations |

**Total: 100 points**

***

## **SUBMISSION REQUIREMENTS**

- One PHP file for the dashboard.
- SQL file for database creation and sample data.
- The code must run correctly in a local XAMPP environment.
- No frameworks are allowed.
- Use **PDO only**, not MySQLi.

***


