# Task Management Application (Laravel | Vue.Js)

This Task Management application is built using Laravel for the backend and Vue.js for the frontend, providing a seamless and interactive user experience. It allows users to create, edit, and manage tasks, with features like task categorization, due dates, and status updates. The app leverages Laravel’s powerful routing and database management capabilities, while Vue.js enables real-time updates and a dynamic user interface. Perfect for organizing personal or team tasks with efficiency and ease.

### Key Features:

- Used repository design pattern.
- Reused backend codes for both the web and APIs.
- Generic CRUD operation for all the model using Route model binding.
- Implemented user roles and permission based operation.
- OAuth2 (login with Google) implemented both for stateful and stateless request.
- Managed 50k users in dropdown select list with server side searching.
- Server side pagination with v-data table for better user experience for list view.
- Automatic regenerate access and refresh token along with the token scope using Passport.

### Other Features:

- Make the app responsive.
- Used Vuetify for styling.
- Show proper errors for better UX.
- Used composition API instead of the option API.
- Implemented state management with Pinia instead of Vuex.
- Implemented multiselect and search in dropdown list.
- Codes have been distributed among other classes like repository, service class, and helper functions.

**Follow the steps below to set up and run the project locally.**

## Getting Started

### Prerequisites

Ensure you have the following installed on your local environment:
- PHP (>= 8.2)
- Composer
- Node.js and npm
- MySQL or any other compatible database

### Cloning the Repository

Begin by cloning the repository to your local machine:

```bash
git clone https://github.com/biplob192/task_management.git
```

Navigate into the project directory:

```bash
cd task_management
```

## Backend Setup
The backend is a Laravel application. Follow the steps below to configure it:

### Install Composer Dependencies
Navigate to the backend directory:

```bash
cd backend
```

Install all necessary PHP packages:

```bash
composer install --ignore-platform-reqs
```

### Environment Configuration
Copy the .env.example file to create your .env configuration file:

```bash
copy .env.example .env
```

Note: If you are using Git Bash, the above command may not work. Use Command Prompt or PowerShell instead.

### Database Configuration
Open the .env file and update the database credentials under the following section:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Generate Application Key
Run the following command to generate a unique application key:

```bash
php artisan key:generate
```

### Generate Passport Key
Run the following command to generate Passport requires encryption keys (oauth-private.key and oauth-public.key) to function. These keys are typically stored in the storage directory of your Laravel application.

```bash
php artisan passport:keys
```

### Run Migrations and Seed Database
Set up your database by running migrations and seeding the database:

```bash
php artisan migrate:fresh --seed
```

### Start the Backend Server
Run the following command to start the backend server:

```bash
php artisan serve
```

<!-- ### Install Passport
Generate the Passport keys required for API authentication:

```bash
php artisan passport:install
``` -->

## Frontend Setup
The front end is a Vue.js application. Follow the steps below to configure it:

### Install Node.js Dependencies
Navigate to the frontend directory:

```bash
cd ../frontend
```

Install the required npm packages:

```bash
npm install
```

### Start the Frontend Development Server
Run the following command to start the frontend server:

```bash
npm run dev
```

**Finally, browse the frontend project in a browser like Google Chrome, if not open the browser automatically after run the above command.**

## Credentials for Login
Use the following credentials to log in:

### Super Admin User:
Email: superadmin@gmail.com
Password: password

### Admin User:
Email: admin@gmail.com
Password: password

**Note:** The admin@gmail.com user has restricted permissions and cannot create, update, or delete users. Use the superadmin@gmail.com account for full access and permissions.
