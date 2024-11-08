# Parent Folder Project

This repository contains two sub-projects: a Vue.js project and a Laravel project.

## Table of Contents

- [Introduction](#introduction)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
  - [Vue.js Project](#vue-js-project)
  - [Laravel Project](#laravel-project)
- [Usage](#usage)
  - [Vue.js Project](#vue-js-project-1)
  - [Laravel Project](#laravel-project-1)

## Introduction

The Parent Folder Project is a monorepo that houses a Vue.js front-end application and a Laravel back-end application. The goal of this project is to demonstrate how to manage and integrate these two frameworks within a single repository.

## Prerequisites

Before you can get started, make sure you have the following software installed on your system:

- [Git](https://git-scm.com/)
- [Node.js](https://nodejs.org/) (with npm)
- [PHP](https://www.php.net/) (version 7.4 or higher)
- [Composer](https://getcomposer.org/)

## Installation

### Vue.js Project

1. Clone the repository and navigate to the repository:

```bash
git clone https://github.com/Pormons/Task.git 
cd Task
```
2. Inside the repository open 2 command prompts, 1 for the Vue.js and the other is for the Laravel Project:

3. Navigate to the Vue.js project directory and Install dependencies:

```bash
cd task
npm install
```

4. Create a `.env` file with the `.env.example` file and update the `VITE_API_URL` to your laravel URL .
```bash
cp .env.example .env
```

5. Start the development server:
```bash
npm run dev
```

The Vue.js application will be available at `http://localhost:5173/` depends on your configuration now just minimize the command prompt.

### Laravel Project

1. With the second command Prompt Navigate to the Laravel project directory:
```bash
cd task-api
```

2. Install the dependencies:
```bash
composer install
```

3.  Create a `.env` file with the `.env.example` file and update the `DATABASE Configurations` to your database and your `FRONTEND_URL` to be the url from your vue.js to allow cross origin .
```bash
cp .env.example .env
```

4. In your `.env` file update the necessary configurations. you can use SQLite by changing the DB_CONNECTION to sqlite and commenting the other DB_* configurations. and your `FRONTEND_URL` will be the url from your Vue.js Project to allow cross-origin resource sharing
```bash
FRONTEND_URL=

DB_CONNECTION=mysql #change to sqlite when using sqlite
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Laravel
DB_USERNAME=root
DB_PASSWORD=
```

5. Generate the application key:
```bash
php artisan key:generate
```

6. Run the database migrations:
```bash
php artisan migrate
```

7. Start the development server:
```bash
php artisan serve
```

The Laravel application will be available at `http://127.0.0.1:8000` depends on your configuration. you can then minimize the command prompt and navigate to the url of the Vue.js Project in you browser to use the Application.