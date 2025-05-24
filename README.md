**Web-Based Nursery Management System:**
A full-stack web application designed to streamline nursery school operations by automating administrative tasks, enhancing communication between staff and parents, and improving the educational experience for young children.

**Overview:**
This system was developed as part of my BSc Computing dissertation project. It targets the inefficiencies of paper-based nursery management, offering tailored digital tools for:

- Attendance tracking.
- Student enrollement and management.
- A built-in messaging feature that enables direct communication between admins and teachers, as well as between parents and teachers.
- Class and subject management.
- Event and noticeboard updates.
- Login/authentication for each user role
- Student progress monitoring.

**Project Aims:**
- Improve administrative efficiency in nursery schools.
- Increase parental engagement and communication.
- Automate routine tasks such as attendance and reports.
- Provide a secure, scalable and user-friendly interface.

**User Roles & Features:**
**Admin Panel:**
- Add, update, or delete users (teachers, parents, students)
- Manage attendance records
- Send notifications & manage school events
- Respond to visitor inquiries
- Role-based login system

**Teacher Panel:**
- Record and view student attendance
- Enter and email mark sheets
- Communicate directly with parents and admin
- View notices and update profile

**Parent Panel:**
- View child’s marks, subjects, and attendance
- Receive announcements and messages
- Update profile and communicate with teachers

**Visitor Panel:**
- Submit inquiries to admin
- Receive responses via email

**Tech Stack:**
- Front-End: HTML, CSS, JavaScript
- Back-End: PHP
- Database: MySQL
- Mailer: PHPMailer

**Testing:**
- Unit testing for each user interface.
- Integration testing with real-time feedback.
- User testing conducted with trainee teachers in real-world nursery environments.
  
**Future Improvements:**
- Mobile-first responsive redesign
- Notifications via SMS or app push
- Advanced reporting & analytics for student development
- Integration with external learning management systems (LMS)

**Demo:**
**To run locally:**
- Clone the repository as shown in the example below;
"git clone https://github.com/alameamal12/Nursery_Management_System.git".
- Place it in your htdocs folder (if using XAMPP).
- Import the SQL file into phpMyAdmin.
- Update db.php with your local database settings.
- Open localhost/yourfoldername in your browser.

**Folder Structure:**

- `Nursery_Management_System/`
  - `Nursery/`
    - `websites/`
      - `default/`
        - `public/`
          - `admin/` – Admin dashboard and management tools  
          - `teacher/` – Teacher dashboard and management features
          - `parent/` – Parent portal dashboard
          - `phpmailer/` – Email handling via PHPMailer  
          - `templates/` – Form templates  
          - `js/` – JavaScript and CSS assets  
          - `uploads/` – Image and file uploads  
          - `db.php` – Database connection  
          - `index.php` – Landing page  
          - `...` – Other PHP support files
- `README.md` – Project overview and documentation

