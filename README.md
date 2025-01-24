# Chit Software

Base of Admin template functionable with JavaScript

## Installation

1. Fork this repository to your Github account and change the name if you want.
2. Clone the repository to your local system using Github Desktop.
3. Use the directory where you localhost placed i.e., C:xampp/htdocs/
4. The application will run in the path <http://localhost/template/>
5. If changing directory name, change in `config-file.php` and other places for perfect output.

## Requirements

1. Windows 10 - 8GB RAM - 64bit (For better perfomance)
2. Microsoft .Net Framework 4.5 or above
3. xampp version 8 or above
4. PHP version 8 or above
5. Bootstrap 4 and jQuery 3 (already included)

## Configuration

1. Create a database in phpmyadmin.
2. Base SQL file has been placed inside database folder, import it using phpmyadmin.
3. Update the Database connection string in `ajaxconfig.php`.
4. Update the `menu_list` and `sub_menu_list` table manually or using procedures to get the sidebar menus.  

## Key Features

1. Add new screens by adding them to the database and specifying the icon and base view file. The screen will be automatically added to the sidebar and accessible when the URL is triggered.
2. `link` column in the `sub_menu_list` table, should contain the file name without extension. **This should be the view file name and js file name.**
3. Sidebar updates based on user screen access after login.
4. Prevents redirection to inaccessible screens if the user enters unaccessible URLs.
5. Fully functional search bar to search for screens.
6. Tracks sidebar menu based on the user's current screen.
7. Displays a 404 page for unaccessed or incorrect URLs.
8. Includes built-in SweetAlert functions: `swalSuccess()`, `swalError()`, and `swalInfo()` for easy alerts (pass title and text in parameter). `swalConfirm(title, text, functionname, values)` send title, text, functionname to call after accept the delete and value if you want to pass value in function pass it or else pass it as empty ''
9. Built-in DataTable initialization. Use `dtable` to convert a table into a DataTable.
10. Automatic display of basic JavaScript alert functions like toast messages.
11. Loader is automatically added when an AJAX call is initiated.
12. Use 'table-responsive' class for container responsiveness (for table containers).
13. Features 'toggle-container' and 'radio-container' classes for well-designed radio button functionality.

## Directories

1. **api**
    - **api_classes**: Place to mention all AJAX files with separate folders.
    - **base_api**: Important APIs for initializing the template. Avoid editing or adding new files here.

2. **css**
    - Important CSS files like Bootstrap, sidebar, and main body CSS files. Additional CSS files can be added here.

3. **cssd**
    - Files to include Datatable styles for table stylings.

4. **database**
    - Store SQL files for each commit to update database tables.

5. **fonts**
    - Contains icons and their definitions. Do not add any files here. To view icons imported in this project:
        1. Visit [IcoMoon](https://icomoon.io/app/#/select).
        2. Click the import icon button at the top.
        3. Choose icomoon33b.svg file.
        4. There you get all the icons. Choose one or more to get details.
        5. Obtain the unicode and use it inside `<i>`tags.

6. **img**
    - Add static images for non-dynamic purposes. Important images like fav.png and av1.png should be included here.

7. **include**
    - **common**: Contains necessary files like header, footer, body, and sidebar. Avoid adding new files here.
    - **views**: Main folder for all screens. 404.php is an important file that should not be deleted. All files inside this folder should be unique and listed in database tables.

8. **js**
    - **jsClass**: Contains reusable JavaScript functions written inside classes. JavaScript files for each view should be placed here.

9. **jsd**
    - Contains necessary JavaScript files like main, body, sidebar, Bootstrap, DataTables, jQuery, etc. Do not add any files here.

10. **uploads**
    - Redirects all files uploaded by end-users to separate folders.

11. **vendor**
    - Stores all vendor files like Excel reader, multiselect dropdown, DataTables, and custom CSS for Bootstrap. New vendor files can be added here.

12. **ajaxconfig.php**
    - File for establishing DB connection using PDO. Sets DB and PHP timezone to Asia/Kolkata.

13. **home.php**
    - Main file to load the base of the dashboard. No further work is needed here.

14. **index.php**
    - Defines the login page. After login, redirects to the home page of the template. This screen will not be shown again unless the user logs out.

15. **logout.php**
    - An AJAX called PHP file that ends all active sessions and redirects the user to the login page.
