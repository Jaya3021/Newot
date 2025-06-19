# Laravel Vimeo Dashboard

## 📌 Project Description
Laravel Vimeo Dashboard is a web-based content management application built with the Laravel PHP framework. The application enables authenticated admin users to upload videos directly to Vimeo, manage uploaded videos, and view them within a personalized dashboard.

This system is ideal for content creators, marketing teams, online educators, or internal video libraries where media content is managed through the Vimeo platform.

## 🚀 Key Features Implemented
*   🔐 **Admin Authentication System**: Secure login and registration using Laravel Breeze (Tailwind CSS and Blade).
*   🎞️ **Video Upload via Vimeo API**: Authenticated users can upload video files from their local machine directly to their Vimeo account. Metadata (title, description) is attached.
*   💾 **Video Metadata Storage**: Uploaded video details (Vimeo URI, link, embed HTML) are stored in the local database.
*   📊 **Video Dashboard Interface**: Authenticated users can view all their uploaded videos in a responsive dashboard. Videos are displayed using Vimeo's embedded player.

## 🧱 Technical Stack
*   ⚙️ **Backend**:
    *   Laravel 12+
    *   Vimeo PHP SDK (`vimeo/vimeo-api`)
    *   Database: SQLite (default), MySQL, or PostgreSQL compatible
    *   Authentication: Laravel Breeze
*   🎨 **Frontend**:
    *   Blade Templates
    *   Tailwind CSS (via Laravel Breeze)
    *   Vite for frontend asset compilation
*   📡 **Vimeo API Integration**: Uses official Vimeo PHP SDK for authentication and video uploads.

## 🛠️ Setup Instructions

1.  **Clone the Repository** (Assuming you have this project in a Git repository)
    ```bash
    # git clone <your-repository-url>
    cd LaravelVimeo # Or your project's root folder name
    ```

2.  **Install PHP Dependencies**
    ```bash
    composer install
    ```

3.  **Create Environment File**
    Copy the example environment file:
    ```bash
    cp .env.example .env
    ```

4.  **Generate Application Key**
    ```bash
    php artisan key:generate
    ```

5.  **Configure `.env` File**
    Open the `.env` file and set the following:
    *   **Database Credentials**:
        ```env
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=laravel_vimeo_dashboard
        DB_USERNAME=root
        DB_PASSWORD=
        ```
        (Adjust for your database type, e.g., `sqlite` and remove other DB lines if using SQLite. Ensure `database/database.sqlite` file exists if using SQLite.)
    *   **Vimeo API Credentials**:
        ```env
        VIMEO_CLIENT_ID=your_vimeo_client_id
        VIMEO_CLIENT_SECRET=your_vimeo_client_secret
        VIMEO_ACCESS_TOKEN=your_vimeo_access_token
        ```
        (Replace placeholders with your actual credentials from [developer.vimeo.com/apps](https://developer.vimeo.com/apps))
    *   **Application URL**:
        ```env
        APP_URL=http://localhost:8000 
        ```
        (Or the URL you are using for development)

6.  **Run Database Migrations**
    This will create the `users`, `videos`, and other necessary tables.
    ```bash
    php artisan migrate
    ```

7.  **Install Frontend Dependencies**
    ```bash
    npm install
    ```

8.  **Compile Frontend Assets**
    For development (with hot reloading):
    ```bash
    npm run dev
    ```
    For production:
    ```bash
    npm run build
    ```

9.  **Clear Configuration Cache (Important after .env changes)**
    ```bash
    php artisan config:clear
    php artisan cache:clear
    ```

10. **Run the Development Server**
    ```bash
    php artisan serve
    ```
    The application should now be accessible, typically at `http://127.0.0.1:8000`.

## 🗂️ Folder & File Overview (Key Components)

*   `app/Http/Controllers/VideoController.php`: Handles all logic related to video uploads, interaction with the Vimeo API, and saving video data to the database.
*   `app/Models/Video.php`: The Eloquent model representing the `videos` table in the database. Defines fillable fields.
*   `config/services.php`: Contains configuration for third-party services, including the Vimeo API credentials pulled from the `.env` file.
*   `database/migrations/TIMESTAMP_create_videos_table.php`: The database migration file that defines the schema (columns and types) for the `videos` table.
*   `resources/views/dashboard.blade.php`: The Blade view for the main video dashboard. It lists uploaded videos using embedded Vimeo players.
*   `resources/views/upload.blade.php`: The Blade view containing the form for users to upload new videos, including title and description.
*   `resources/views/layouts/app.blade.php`: The main application layout provided by Laravel Breeze, used by dashboard and upload views.
*   `resources/views/layouts/navigation.blade.php`: The navigation bar component (from Breeze), customized to include links to the Video Dashboard and Upload page.
*   `routes/web.php`: Defines all web routes for the application, including authentication routes (from Breeze) and routes for video-related actions (dashboard, upload form, upload processing).
*   `.env`: The environment configuration file. **Crucial for storing sensitive API keys and database credentials.**
*   `public/build/`: This directory (after running `npm run build` or `npm run dev`) contains the compiled frontend assets (CSS, JS) and the `manifest.json` file used by Vite.

## 💡 How It Works (Simplified Explanation)

1.  **User Authentication**:
    *   Laravel Breeze provides the registration and login system. When a user visits the site, they can register or log in.
    *   Routes like `/dashboard` and `/upload` are protected by `auth` middleware, meaning only logged-in users can access them.

2.  **Video Upload**:
    *   A logged-in user navigates to the "Upload Video" page (`/upload`), which displays a form (`upload.blade.php`).
    *   The user selects a video file, adds a title and description, and submits the form.
    *   The form data (including the video file) is sent to the `store` method in `VideoController.php`.
    *   The `VideoController` then:
        *   Validates the input (e.g., file type, size, required title).
        *   Initializes the Vimeo API client using your `VIMEO_CLIENT_ID`, `VIMEO_CLIENT_SECRET`, and `VIMEO_ACCESS_TOKEN` from the `.env` file (accessed via `config/services.php`).
        *   Uploads the video file directly to your connected Vimeo account.
        *   After successful upload, it asks Vimeo for details about the uploaded video (like its unique Vimeo URI, direct link, and the HTML code to embed the player).
        *   Saves these details (title, description, Vimeo URI, link, embed HTML) into the `videos` table in your local application database using the `Video` model.
        *   Redirects the user to the Video Dashboard with a success message.

3.  **Video Dashboard**:
    *   When a user visits the `/dashboard` page, the `index` method in `VideoController.php` is called.
    *   This method fetches all video records from the `videos` table in the database.
    *   The data is passed to the `dashboard.blade.php` view.
    *   The view loops through each video and displays its title, description, and most importantly, uses the stored `embed_html` to render the Vimeo video player directly on the page.

## 🧪 Troubleshooting Common Issues

*   **`Vite manifest not found...` error on login/register pages**:
    *   This means the frontend assets (CSS/JS) haven't been compiled.
    *   **Fix**: Run `npm install` and then `npm run build` (or `npm run dev` for development) in your project's root directory (`LaravelVimeo/LaravelVimeo`).
*   **Vimeo API Errors (e.g., `TypeError ... client_id must be of type string, null given ...`) during upload**:
    *   This usually means Laravel isn't loading your Vimeo credentials from the `.env` file.
    *   **Fix**:
        1.  Ensure `VIMEO_CLIENT_ID`, `VIMEO_CLIENT_SECRET`, and `VIMEO_ACCESS_TOKEN` are correctly set in your `.env` file in the project root (`LaravelVimeo/LaravelVimeo`).
        2.  **Crucially**, after saving changes to `.env`, clear Laravel's configuration cache by running `php artisan config:clear` and `php artisan cache:clear` in your project root.
*   **`Could not open input file: artisan` error**:
    *   This error means you are trying to run an `php artisan` command from the wrong directory.
    *   **Fix**: Always ensure your terminal is in the root directory of your Laravel project (e.g., `C:\Users\kingv\LaravelVimeo\LaravelVimeo`) before running `php artisan` commands.
*   **`npm install` fails with `EBUSY` errors**:
    *   This often means files in `node_modules` are locked by another process (like your code editor, file explorer, or antivirus).
    *   **Fix**: Try closing other applications, running PowerShell as Administrator, and then running `npm cache clean --force`, deleting `node_modules` and `package-lock.json`, and then `npm install` again.

---

This README provides a good starting point. You can expand it further as you add more features!
