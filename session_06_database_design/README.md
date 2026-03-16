# **Part 1: Normalization Challenge**
## **Task 1 — Identify violations**
- Redundancy:
  
StudentName repeats for every course a student takes.

CourseName repeats for every student enrolled.

ProfessorName and ProfessorEmail repeat for every course section taught.

- Update anomalies:
  
If Dr. Le changes their email, you’d have to update multiple rows.

If a course name changes (e.g., “Database Systems” → “Advanced Databases”), every row with that course must be updated.

- Transitive dependency:
  
CourseID → ProfessorName, ProfessorEmail. Professor info depends on the course, not directly on the student.

This means professor details are transitively dependent on CourseID, not on the primary key of the raw table (StudentID + CourseID).

## **Task 2 — Decompose to 3NF**
We’ll split into four tables: Students, Courses, Professors, Enrollments.
| Table | PK | FK(s) | Non-key columns |
| :--- | :--- | :--- | :--- |
| Students | StudentID | - | StudentName |
| Courses | CourseID | ProfessorID | CourseName |
| Professors | ProfessorID | - | ProfessorName, ProfessorEmail |
| Enrollments | (StudentID, CourseID) | StudentID → Students, CourseID → Courses | Grade |
### Why each table exists:
- Students: Stores unique student info. Prevents repeating names across rows.

- Courses: Defines courses uniquely. Links to professors so course-professor mapping is consistent.

- Professors: Holds professor details once, avoiding email/name duplication.

- Enrollments: Resolves the many-to-many relationship between students and courses, with Grade as an attribute of that relationship.
# **Part 2: Relationship Drills**
1. AUTHOR – BOOK

Relationship Type: One-to-Many (one author can write many books).

FK Location: Book table should have AuthorID as a foreign key.

2. CITIZEN – PASSPORT
   
Relationship Type: One-to-One (a citizen has one passport, and a passport belongs to one citizen).

FK Location: Either side works, but typically Passport holds CitizenID as FK, since the passport is issued to the citizen.

3. CUSTOMER – ORDER
   
Relationship Type: One-to-Many (a customer can place many orders).

FK Location: Order table should have CustomerID as a foreign key.

4. STUDENT – CLASS
   
Relationship Type: Many-to-Many (students can enroll in many classes, and classes have many students).

FK Location: Needs a junction table (e.g., Enrollments) with StudentID and ClassID as foreign keys.

5. TEAM – PLAYER
   
Relationship Type: One-to-Many (a team has many players, but a player belongs to one team).

FK Location: Player table should have TeamID as a foreign key.
