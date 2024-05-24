# Period-Calculator - Live in sync with your cycle

## Project Overview
"Period-Calculator" is a menstrual cycle calculating application designed to revolutionize menstrual health management for women. The website provides a user-friendly interface that simplifies the calculating of menstrual cycles, offering comprehensive insights and educational resources. With “Period-Calculator,” users can effortlessly calculate their menstrual cycles, recording symptoms, and access educational materials.

## Features
- **Cycle Calculating**: Record and predict menstrual cycles based on user input.
- **Symptom Recording**: Track symptoms experienced during menstrual periods.
- **Educational Resources**: Access educational materials tailored to the needs of Nepali women.
- **User-Friendly Interface**: Simple and intuitive interface for easy navigation and use.

## Technologies Used
- **Frontend**: HTML, CSS, Js, jQuery, Bootstrap
- **Backend**: Core PHP
- **Database**: MySQL

## Installation

### Prerequisites
- Web server (e.g., Apache)
- PHP 8.2 or higher
- MySQL 8.0 or higher

### Steps

1. **Download the Project**
   You can either clone the repository or download the ZIP file.

   **Clone the Repository**
   - Ensure you have a web server (e.g., Apache) installed and running.
   - Navigate to the `htdocs` directory (or the appropriate directory for your web server setup).
   ```sh
   cd /path/to/your/htdocs
   git clone https://github.com/pandeysubash404/period-calculator.git
   cd period-calculator
   ```

   **Download the ZIP File**
   - Download the ZIP file from the GitHub repository: [Download ZIP](https://github.com/pandeysubash404/period-calculator/archive/refs/heads/main.zip)
   - Extract the ZIP file into your `htdocs` directory (or the appropriate directory for your web server setup).
   ```sh
   cd /path/to/your/htdocs
   unzip period-calculator-main.zip
   cd period-calculator-main
   ```

2. **Setup Database**
   - Create a new MySQL database.
   - Import the provided SQL script (`database.sql`) into the newly created database.
   - Update the database configuration in `db_config.php` with your MySQL credentials.

3. **Run the Application**
   - Open a web browser and navigate to the URL where the application is hosted (e.g., `http://localhost/period-calculator`).
   - You can find the credentials to login in the `about.txt` file.

## Usage
1. **Registration**
   - Users can register for an account to start calculating their menstrual cycles and record symptoms.

2. **Login**
   - Registered users can log in to access their dashboard, where they can input cycle details and record symptoms.

3. **Cycle Calculating**
   - Enter the start date and cycle length to predict the next menstrual period.

4. **Symptom Recording**
   - Record symptoms experienced during the menstrual cycle for future reference.

5. **Educational Resources**
   - Access educational articles and blogs.

## Limitations
- **Basic Predictive Algorithms**: The initial implementation uses basic algorithms for cycle prediction, which may not account for irregular cycles.
- **Specialized Medical Advice**: The platform does not offer real-time consultation with gynecologists or healthcare professionals.

## Future Enhancements
- Implement advanced predictive algorithms for more accurate cycle calculating.
- Provide access to real-time medical consultations and support.

## Contributing
We welcome contributions to the "Period-Calculator" project. To contribute, please follow these steps:
1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Commit your changes (`git commit -m 'Add new feature'`).
4. Push to the branch (`git push origin feature-branch`).
5. Create a pull request.

---

Thank you for using "Period-Calculator." I hope this tool empowers women with the knowledge and resources to manage their menstrual health effectively. For any questions or support, please contact [pandeysubash404@gmail.com](mailto:pandeysubash404@gmail.com).