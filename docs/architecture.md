# Arsitektur dan ERD - IAE-LMS

## 📐 Arsitektur / Workflow Aplikasi

### System Architecture Overview

```mermaid
graph TB
    subgraph "Client Layer"
        Browser[Web Browser]
        User[User Interface<br/>Blade Templates]
    end
    
    subgraph "Application Layer - Laravel 11"
        Routes[Routes<br/>web.php, auth.php]
        Middleware[Middleware<br/>Auth, Role-based]
        Controllers[Controllers]
        
        subgraph "Controllers"
            AuthC[Auth Controller]
            AdminC[Admin Controller]
            TeacherC[Teacher Controller]
            StudentC[Student Controller]
            DashC[Dashboard Controller]
        end
        
        Models[Eloquent Models]
        Policies[Authorization Policies]
        GraphQL[GraphQL API<br/>Lighthouse]
    end
    
    subgraph "Data Layer - Multi Database Architecture"
        MainDB[(Main Database<br/>lms_main<br/>Users, Sessions)]
        GuruDB[(Guru Database<br/>lms_guru<br/>Courses, Materials,<br/>Assignments, Quizzes)]
        SiswaDB[(Siswa Database<br/>lms_siswa<br/>Enrollments,<br/>Submissions,<br/>Quiz Attempts)]
        AdminDB[(Admin Database<br/>lms_admin<br/>Logs, Settings,<br/>Audit)]
    end
    
    subgraph "Storage"
        FileStorage[File Storage<br/>storage/app/public]
    end
    
    Browser --> User
    User --> Routes
    Routes --> Middleware
    Middleware --> Controllers
    Controllers --> Models
    Controllers --> Policies
    Models --> MainDB
    Models --> GuruDB
    Models --> SiswaDB
    Models --> AdminDB
    Controllers --> FileStorage
    
    GraphQL --> Models
    Browser -.GraphQL API.-> GraphQL
    
    classDef client fill:#60a5fa,stroke:#3b82f6,stroke-width:2px,color:#000
    classDef app fill:#a78bfa,stroke:#8b5cf6,stroke-width:2px,color:#000
    classDef data fill:#34d399,stroke:#10b981,stroke-width:2px,color:#000
    classDef storage fill:#fbbf24,stroke:#f59e0b,stroke-width:2px,color:#000
    
    class Browser,User client
    class Routes,Middleware,Controllers,AuthC,AdminC,TeacherC,StudentC,DashC,Models,Policies,GraphQL app
    class MainDB,GuruDB,SiswaDB,AdminDB data
    class FileStorage storage
```

---

## 🔄 Application Workflow by Role

### Admin Workflow

```mermaid
flowchart LR
    AdminLogin[Admin Login] --> AdminDash[Admin Dashboard]
    AdminDash --> UserMgmt[User Management<br/>CRUD Users]
    AdminDash --> System[System Monitoring]
    AdminDash --> Settings[Settings Config]
    
    UserMgmt --> MainDB[(Main DB)]
    System --> AdminDB[(Admin DB)]
    Settings --> AdminDB
    
    classDef admin fill:#ef4444,stroke:#dc2626,stroke-width:2px,color:#fff
    classDef db fill:#34d399,stroke:#10b981,stroke-width:2px,color:#000
    
    class AdminLogin,AdminDash,UserMgmt,System,Settings admin
    class MainDB,AdminDB db
```

### Teacher Workflow

```mermaid
flowchart LR
    TeacherLogin[Teacher Login] --> TeacherDash[Teacher Dashboard]
    TeacherDash --> CourseM[Manage Courses]
    TeacherDash --> MaterialM[Upload Materials]
    TeacherDash --> AssignM[Create Assignments]
    TeacherDash --> QuizM[Create Quizzes]
    TeacherDash --> Review[Review Submissions]
    TeacherDash --> Attendance[Attendance Management]
    
    CourseM --> GuruDB[(Guru DB)]
    MaterialM --> GuruDB
    MaterialM --> Storage[File Storage]
    AssignM --> GuruDB
    QuizM --> GuruDB
    Review --> SiswaDB[(Siswa DB)]
    Attendance --> MainDB[(Main DB)]
    
    classDef teacher fill:#8b5cf6,stroke:#7c3aed,stroke-width:2px,color:#fff
    classDef db fill:#34d399,stroke:#10b981,stroke-width:2px,color:#000
    classDef storage fill:#fbbf24,stroke:#f59e0b,stroke-width:2px,color:#000
    
    class TeacherLogin,TeacherDash,CourseM,MaterialM,AssignM,QuizM,Review,Attendance teacher
    class MainDB,GuruDB,SiswaDB db
    class Storage storage
```

### Student Workflow

```mermaid
flowchart LR
    StudentLogin[Student Login] --> StudentDash[Student Dashboard]
    StudentDash --> Browse[Browse Courses]
    StudentDash --> Enroll[Enroll Course]
    StudentDash --> ViewMat[View Materials]
    StudentDash --> SubAssign[Submit Assignment]
    StudentDash --> TakeQuiz[Take Quiz]
    StudentDash --> CheckGrade[Check Grades]
    
    Browse --> GuruDB[(Guru DB)]
    Enroll --> SiswaDB[(Siswa DB)]
    ViewMat --> GuruDB
    ViewMat --> Storage[File Storage]
    SubAssign --> SiswaDB
    SubAssign --> Storage
    TakeQuiz --> GuruDB
    TakeQuiz --> SiswaDB
    CheckGrade --> SiswaDB
    
    classDef student fill:#3b82f6,stroke:#2563eb,stroke-width:2px,color:#fff
    classDef db fill:#34d399,stroke:#10b981,stroke-width:2px,color:#000
    classDef storage fill:#fbbf24,stroke:#f59e0b,stroke-width:2px,color:#000
    
    class StudentLogin,StudentDash,Browse,Enroll,ViewMat,SubAssign,TakeQuiz,CheckGrade student
    class GuruDB,SiswaDB db
    class Storage storage
```

---

## 🗄️ ERD / Database Schema

### Entity Relationship Diagram

```mermaid
erDiagram
    USERS ||--o{ COURSES : "teacher creates"
    USERS ||--o{ ENROLLMENTS : "student enrolls"
    USERS ||--o{ QUIZZES : "teacher creates"
    USERS ||--o{ ATTENDANCE_SESSIONS : "teacher creates"
    USERS ||--o{ ATTENDANCE_RECORDS : "student attends"
    
    COURSES ||--o{ MATERIALS : "has"
    COURSES ||--o{ ASSIGNMENTS : "has"
    COURSES ||--o{ QUIZZES : "has"
    COURSES ||--o{ ENROLLMENTS : "has"
    COURSES ||--o{ ATTENDANCE_SESSIONS : "has"
    
    ENROLLMENTS ||--o{ SUBMISSIONS : "student submits"
    
    ASSIGNMENTS ||--o{ SUBMISSIONS : "has"
    
    QUIZZES ||--o{ QUIZ_QUESTIONS : "has"
    QUIZZES ||--o{ QUIZ_ATTEMPTS : "has"
    
    QUIZ_QUESTIONS ||--o{ QUIZ_OPTIONS : "has"
    QUIZ_QUESTIONS ||--o{ QUIZ_ANSWERS : "has"
    
    QUIZ_ATTEMPTS ||--o{ QUIZ_ANSWERS : "has"
    
    QUIZ_OPTIONS ||--o{ QUIZ_ANSWERS : "selected as"
    
    ATTENDANCE_SESSIONS ||--o{ ATTENDANCE_RECORDS : "has"
    
    USERS {
        bigint id PK
        string name
        string email UK
        string password
        enum role "admin, teacher, student"
        enum status "active, inactive"
        timestamp created_at
        timestamp updated_at
    }
    
    COURSES {
        bigint id PK
        bigint teacher_id FK
        string title
        string code UK
        enum status "draft, published, archived"
        timestamp created_at
        timestamp updated_at
    }
    
    MATERIALS {
        bigint id PK
        bigint course_id FK
        string title
        text description
        string file_path
        enum type "pdf, video, doc"
        timestamp created_at
        timestamp updated_at
    }
    
    ASSIGNMENTS {
        bigint id PK
        bigint course_id FK
        string title
        text description
        datetime deadline
        integer max_score
        timestamp created_at
        timestamp updated_at
    }
    
    ENROLLMENTS {
        bigint id PK
        bigint course_id FK
        bigint student_id FK
        enum status "pending, enrolled, rejected"
        timestamp created_at
        timestamp updated_at
    }
    
    SUBMISSIONS {
        bigint id PK
        bigint enrollment_id FK
        bigint assignment_id FK
        text content
        string file_path
        integer score
        text feedback
        timestamp created_at
        timestamp updated_at
    }
    
    QUIZZES {
        bigint id PK
        bigint course_id FK
        bigint created_by FK
        string title
        tinyint max_attempt
        integer duration
        boolean show_review
        boolean is_published
        datetime published_at
        datetime deadline
        timestamp created_at
        timestamp updated_at
    }
    
    QUIZ_QUESTIONS {
        bigint id PK
        bigint quiz_id FK
        text question_text
        enum type "multiple_choice, true_false, essay"
        integer points
        integer order
        timestamp created_at
        timestamp updated_at
    }
    
    QUIZ_OPTIONS {
        bigint id PK
        bigint question_id FK
        text option_text
        boolean is_correct
        timestamp created_at
        timestamp updated_at
    }
    
    QUIZ_ATTEMPTS {
        bigint id PK
        bigint quiz_id FK
        bigint student_id FK
        integer attempt_number
        datetime started_at
        datetime submitted_at
        integer total_score
        timestamp created_at
        timestamp updated_at
    }
    
    QUIZ_ANSWERS {
        bigint id PK
        bigint attempt_id FK
        bigint question_id FK
        bigint selected_option_id FK
        text answer_text
        integer points_earned
        timestamp created_at
        timestamp updated_at
    }
    
    ATTENDANCE_SESSIONS {
        bigint id PK
        bigint course_id FK
        bigint teacher_id FK
        datetime session_date
        timestamp created_at
        timestamp updated_at
    }
    
    ATTENDANCE_RECORDS {
        bigint id PK
        bigint session_id FK
        bigint student_id FK
        enum status "present, absent, late"
        timestamp created_at
        timestamp updated_at
    }
```

---

## 🏗️ Multi-Database Distribution

### Database: `lms_main`
**Purpose**: User authentication and sessions
- ✅ users
- ✅ password_reset_tokens
- ✅ sessions
- ✅ personal_access_tokens
- ✅ cache
- ✅ jobs

### Database: `lms_guru`
**Purpose**: Teacher-managed content
- ✅ courses
- ✅ materials
- ✅ assignments
- ✅ quizzes
- ✅ quiz_questions
- ✅ quiz_options

### Database: `lms_siswa`
**Purpose**: Student activities and submissions
- ✅ enrollments
- ✅ submissions
- ✅ quiz_attempts
- ✅ quiz_answers

### Database: `lms_admin`
**Purpose**: System administration (reserved for future)
- ✅ logs
- ✅ settings
- ✅ audit_trails

---

## 🔐 Security & Authorization

### Role-based Access Control

```mermaid
graph TD
    User[User] -->|has role| Role{Role Type}
    
    Role -->|Admin| AdminPerm[Admin Permissions]
    Role -->|Teacher| TeacherPerm[Teacher Permissions]
    Role -->|Student| StudentPerm[Student Permissions]
    
    AdminPerm --> A1[Manage All Users]
    AdminPerm --> A2[View All Data]
    AdminPerm --> A3[System Settings]
    
    TeacherPerm --> T1[Manage Own Courses]
    TeacherPerm --> T2[Create Materials/Quizzes]
    TeacherPerm --> T3[Grade Submissions]
    TeacherPerm --> T4[View Enrolled Students]
    
    StudentPerm --> S1[Enroll Courses]
    StudentPerm --> S2[View Materials]
    StudentPerm --> S3[Submit Assignments]
    StudentPerm --> S4[Take Quizzes]
    StudentPerm --> S5[View Own Grades]
    
    classDef admin fill:#ef4444,stroke:#dc2626,stroke-width:2px,color:#fff
    classDef teacher fill:#8b5cf6,stroke:#7c3aed,stroke-width:2px,color:#fff
    classDef student fill:#3b82f6,stroke:#2563eb,stroke-width:2px,color:#fff
    
    class AdminPerm,A1,A2,A3 admin
    class TeacherPerm,T1,T2,T3,T4 teacher
    class StudentPerm,S1,S2,S3,S4,S5 student
```

---

## 🛠️ Technology Stack

| Layer | Technology | Version |
|-------|------------|---------|
| **Frontend** | Blade Templates | Laravel 11 |
| **CSS Framework** | Tailwind CSS | 3.x |
| **Backend** | Laravel | 11.x |
| **Language** | PHP | 8.2+ |
| **Database** | MySQL | 8.0+ |
| **API** | GraphQL (Lighthouse) | 6.x |
| **Authentication** | Laravel Sanctum | 4.x |
| **Asset Building** | Vite | 5.x |

---

## 📊 Key Features by Module

### 📚 Course Management
- ✅ Create, edit, delete courses
- ✅ Publish/archive courses
- ✅ Course enrollment system
- ✅ Unique course codes

### 📖 Materials Management
- ✅ Upload PDF, videos, documents
- ✅ Material descriptions
- ✅ File storage management

### 📝 Assignment System
- ✅ Create assignments with deadlines
- ✅ Student submission system
- ✅ Scoring and feedback
- ✅ File upload support

### 🎯 Quiz System
- ✅ Multiple choice questions
- ✅ True/false questions
- ✅ Essay questions
- ✅ Time-limited quizzes
- ✅ Multiple attempts (configurable)
- ✅ Auto-grading
- ✅ Review mode

### 📋 Attendance System
- ✅ Session-based attendance
- ✅ Present/absent/late status
- ✅ Attendance reporting

---

## 🔄 Data Flow Example: Student Takes Quiz

```mermaid
sequenceDiagram
    participant S as Student
    participant C as Controller
    participant Q as Quiz Model
    participant A as Attempt Model
    participant GuruDB as Guru DB
    participant SiswaDB as Siswa DB
    
    S->>C: Access Quiz Page
    C->>Q: Get Quiz Details
    Q->>GuruDB: SELECT quiz, questions, options
    GuruDB-->>Q: Return data
    Q-->>C: Quiz data
    C-->>S: Display quiz form
    
    S->>C: Start Quiz
    C->>A: Create Quiz Attempt
    A->>SiswaDB: INSERT into quiz_attempts
    SiswaDB-->>A: Attempt created
    A-->>C: Attempt ID
    C-->>S: Quiz started
    
    S->>C: Submit Answers
    C->>A: Save Answers
    A->>SiswaDB: INSERT quiz_answers
    C->>A: Calculate Score
    A->>SiswaDB: UPDATE attempt score
    SiswaDB-->>A: Updated
    A-->>C: Final score
    C-->>S: Show results
```

---

**Platform**: Windows 10/11  
**Laravel Version**: 11.x  
**Last Updated**: 2026-01-06
