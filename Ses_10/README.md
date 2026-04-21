# OOP Best Practices — English Examples (PHP)

Below are practical, student-friendly examples for four OOP best practices:

- **Single Responsibility Principle (SRP)**
- **Avoid “God Classes”**
- **Type Hinting**
- **Naming Conventions**

---

## 1) Single Responsibility Principle (SRP)

**Rule:** Each class should have only one reason to change.

### Bad example: Mixing database logic with HTML rendering

```php
class UserPage
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function showUsers(): void
    {
        // DB logic
        $stmt = $this->db->query("SELECT id, name, email FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // HTML rendering (mixed responsibilities)
        echo "<h1>Users</h1>";
        echo "<ul>";
        foreach ($users as $u) {
            echo "<li>{$u['name']} ({$u['email']})</li>";
        }
        echo "</ul>";
    }
}
```

### Good example: Separate responsibilities (Repository + View)

```php
class UserRepository
{
    public function __construct(private PDO $db) {}

    /** @return array<int, array{id:int,name:string,email:string}> */
    public function all(): array
    {
        $stmt = $this->db->query("SELECT id, name, email FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

class UserListView
{
    /** @param array<int, array{id:int,name:string,email:string}> $users */
    public function render(array $users): string
    {
        $html = "<h1>Users</h1><ul>";
        foreach ($users as $u) {
            $name = htmlspecialchars($u['name']);
            $email = htmlspecialchars($u['email']);
            $html .= "<li>{$name} ({$email})</li>";
        }
        $html .= "</ul>";
        return $html;
    }
}

// Usage (Controller-like glue)
$repo = new UserRepository($pdo);
$view = new UserListView();

$users = $repo->all();
echo $view->render($users);
```

**Why it’s better:**
- Database changes affect only `UserRepository`.
- UI changes affect only `UserListView`.
- The “glue” code stays small and readable.

---

## 2) Avoid “God Classes”

**Rule:** Don’t create a single class that handles everything.

### Bad example: A “System” class that does too much

```php
class System
{
    public function login(string $email, string $password): bool
    {
        // validation, database query, session, logging...
        return true;
    }

    public function createProduct(array $data): int
    {
        // validation, database insert, file upload, email notification...
        return 123;
    }

    public function renderTemplate(string $template, array $vars): string
    {
        // rendering HTML, escaping, layout...
        return "";
    }

    public function sendEmail(string $to, string $subject, string $body): void
    {
        // SMTP
    }

    // ... 50 more unrelated methods
}
```

### Good example: Split into specialized services

```php
class AuthService
{
    public function __construct(private UserRepository $users) {}

    public function login(string $email, string $password): bool
    {
        // validate, find user, verify password
        return true;
    }
}

class ProductService
{
    public function __construct(private ProductRepository $products) {}

    public function createProduct(string $name, int $price): int
    {
        // validate, save, return new id
        return $this->products->insert($name, $price);
    }
}

class TemplateRenderer
{
    /** @param array<string, mixed> $vars */
    public function render(string $template, array $vars = []): string
    {
        // render HTML output
        return "...";
    }
}
```

**Why it’s better:**
- Smaller classes = easier to test and maintain.
- You can replace or improve one part without touching the entire system.

---

## 3) Type Hinting (Parameters + Return Types)

**Rule:** Always specify data types for parameters and return values.

### Example: Strong method contracts

```php
class Person
{
    private int $age = 0;

    public function setAge(int $age): void
    {
        if ($age < 0) {
            throw new InvalidArgumentException('Age must be >= 0');
        }
        $this->age = $age;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function isAdult(): bool
    {
        return $this->age >= 18;
    }
}
```

### Why it prevents bugs early

Without type hints, this might happen:

```php
$person->setAge("18 years old"); // silently wrong input
```

With `setAge(int $age): void`, PHP will raise a **TypeError** immediately, making the bug easier to catch.

---

## 4) Naming Conventions

**Rule:**
- Use **PascalCase** for class names
- Use **camelCase** for methods and properties

### Good naming example

```php
class ProductController
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function showProductList(): string
    {
        $products = $this->productRepository->all();
        return "Products: " . count($products);
    }
}
```

### Common mistakes to avoid

```php
class product_controller {}       // wrong: class should be PascalCase
public function ShowProductList() {} // wrong: method should be camelCase
private $ProductRepository;       // wrong: properties should be camelCase
```

---

## Quick checklist (students)

- [ ] Does each class have **one clear responsibility**?
- [ ] Did I avoid “God classes” by creating **small services**?
- [ ] Do my methods have **parameter + return type hints**?
- [ ] Are class/method/property names following **PascalCase/camelCase**?

# One-Week Homework Worksheet (MIS-friendly)

**Audience:** MIS (Management Information Systems) students who are not confident programmers

**Goal (1 week):** Practice *analysis + design thinking* using OOP ideas (SRP, naming, basic types) and prepare for **MVC** next week.

> You will write *very little code*. The focus is on planning: identifying responsibilities, designing classes, and mapping those classes into MVC folders (Models / Controllers / Views).

## Scenario

You are asked to design a simple **Campus Service Request** system.

Students submit requests such as:
- “Projector not working in Room A201”
- “Need access to the MIS lab”
- “Broken chair in classroom”

Staff members review requests and update their status.

### Core features (keep it small)
1. Submit a request
2. View a list of requests
3. View request details
4. Update request status (Pending → In Progress → Done)

---

## Deliverables (what you must submit)

### A) Analysis (mostly writing)
1. **Requirements list** (10–15 bullet points)
2. **User roles** (at least 2 roles)
3. **Use cases** (at least 4 use cases, one per feature above)

### B) Design (diagrams + tables)
4. **Class Responsibility Table (SRP)** — minimum 6 classes
5. **Simple UML class diagram** (hand-drawn is OK; photo acceptable)
6. **MVC Mapping Table** (what belongs to Model / View / Controller)
7. **Route Table** (URL → Controller@Action) — minimum 6 routes

### C) Minimal code (optional but recommended)
8. Create **only these files** (empty or tiny skeletons are OK):
   - `app/Controllers/RequestController.php`
   - `app/Models/Request.php`
   - `app/Views/requests/index.php`
   - `app/Views/requests/show.php`
   - `public/index.php` (can just echo “Hello MVC”) 

If you are not comfortable coding, you can submit **pseudocode** instead.

---

## Step-by-step plan (7 days)

### Day 1 — Understand the problem (no code)
- Write the **requirements list**.
- Identify user roles:
  - Student (creates requests)
  - Staff (updates status)

### Day 2 — Use cases (no code)
Write 4 short use cases using this template:
- **Use Case Name:**
- **Actor:**
- **Trigger:**
- **Main Flow (3–6 steps):**
- **Alternative Flow (optional):**
- **Success Outcome:**

### Day 3 — SRP Class Responsibility Table (no code)
Create a table like this (example below).

**Example classes (you can reuse these):**
- `Request` (entity/data)
- `RequestRepository` (data access)
- `RequestService` (business rules)
- `RequestController` (coordinates requests)
- `RequestValidator` (validation rules)
- `ViewRenderer` (renders HTML templates)

**Template (copy & fill):**

| Class name | Responsibility (1 sentence) | Reason to change (1 reason only) |
|---|---|---|
| Request | Stores request data (title, description, status) | Data fields change |
| RequestRepository | Reads/writes requests to storage | Storage method changes |
| RequestService | Applies business rules (allowed status changes) | Rules change |
| RequestValidator | Validates input data | Validation rules change |
| RequestController | Calls service and selects views | Routes/actions change |
| ViewRenderer | Loads a view file and passes variables | Rendering method changes |

### Day 4 — UML diagram (no code)
Draw a simple UML diagram showing:
- `Request` has fields
- `RequestService` uses `RequestRepository`
- `RequestController` uses `RequestService`

Keep it simple: boxes + arrows.

### Day 5 — MVC Mapping + Route Table (no code)

#### 1) MVC Mapping Table (template)

| Item | MVC Layer | Why? |
|---|---|---|
| Request (data + status) | Model | Represents application data |
| RequestRepository | Model | Accesses data source |
| RequestController@index | Controller | Chooses which view to show |
| requests/index.php | View | Displays list UI |
| requests/show.php | View | Displays details UI |

#### 2) Route Table (template)

| HTTP | URL | Controller@Action | Purpose |
|---|---|---|---|
| GET | /requests | RequestController@index | List requests |
| GET | /requests/create | RequestController@create | Show create form |
| POST | /requests | RequestController@store | Save new request |
| GET | /requests/{id} | RequestController@show | View details |
| POST | /requests/{id}/status | RequestController@updateStatus | Update status |
| GET | /staff/requests | RequestController@staffIndex | Staff list view |

### Day 6 — Minimal skeleton (tiny code or pseudocode)
Create folders and empty files (or pseudocode). Focus on naming conventions:
- Classes: `RequestController`, `RequestService` (PascalCase)
- Methods: `updateStatus()`, `staffIndex()` (camelCase)

Optional pseudocode example (OK to submit):

```text
RequestController@updateStatus(id):
  - read newStatus from POST
  - service.changeStatus(id, newStatus)
  - redirect to /requests/{id}
```

### Day 7 — Reflection (bridge to next week’s MVC)
Answer these questions (short paragraphs):
1. Which parts of your design are **Model**, **View**, and **Controller**?
2. Where should validation happen, and why?
3. What would break if you put SQL inside a View file?
4. What code do you expect to write next week to make this real?

---

## How this bridges to MVC next week

Next week you will convert your design into a working MVC mini app by:
- Implementing a **Router** that reads the Route Table
- Writing **Controller actions** that call services/models
- Creating **Views** (HTML templates) for list/detail pages
- Using **autoloading** to load classes automatically

If you finish the worksheet well, next week’s coding becomes “fill in the blanks” instead of guessing.

---

## Grading rubric (simple)

| Category | Points | What we check |
|---|---:|---|
| Requirements + roles | 20 | Clear, realistic, not too large |
| Use cases | 20 | Steps make sense, covers the 4 features |
| SRP responsibility table | 25 | Each class has 1 reason to change |
| MVC mapping + routes | 25 | Correct layer assignment, consistent naming |
| Reflection | 10 | Shows understanding, not just copy-paste |

---

## Submission format

Submit a single PDF (or Word) containing:
- Requirements
- Use cases
- Tables (SRP / MVC / Routes)
- UML diagram (photo is OK)
- Reflection answers

Optional: include a zipped folder with your skeleton files.
