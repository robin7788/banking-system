# Banking system

## Project Info
1. Laravel: v11
2. PHP: v8.2
3. Inertia
4. Vue: v3.4
5. Tailwind css: v3
6. MySQL

## Installation Steps

**Project Requirements**
1. Lando + Docker (Install this if you want to remove manual docker setup).
   - Find documentation in https://docs.lando.dev/install/macos.html
2. Git
3. Composer

**Steps**
1. Clone the project in your local system and do run:
   - `cd banking-system`

2. Configure basic settings:

   - Open project in any IDE and copy `.env.example` file to `.env` in same location.
   - Update configuration setting as per your requirement. Some basic setup for DB and mailhog are:
   
   ```
   DB_CONNECTION=mysql
   DB_HOST=database
   DB_PORT=3306
   DB_DATABASE=laravel
   DB_USERNAME=laravel
   DB_PASSWORD=laravel
   
   MAIL_MAILER=smtp
   MAIL_HOST=mailhog
   MAIL_PORT=1025
   ```

3. Start Server:  
   - `lando start`
   - This will setup all required resources in docker container.
   - Wait for completion of the project.

4. Install other resources. Run:
   - `cd web`
   - `composer install`    # This will install laravel required packages 
   - `lando npm install`   # This will install npm packages
   - `lando artisan migrate` # This will add required tables in database.Add `db:seed` at the end of migration command to add admin user. Details can be found within `DatabaseSeeder` file.
   - If you are importing DB then ignore above migration command and run `lando db:import <file>`.

5. Built npm packages: `lando npm build`

6. The project is ready to go and we can run it with url http://banking-system.lndo.site/.
7. Other services path can be found after completion of `lando start` command.
8. To turn off the service. Run: `lando poweroff`. 

***Note: If you are using docker directly or following manual process, then you will have different process. Along with that, the DB and other services setting in configuration file will be different.**


## Helper documents
- [System Architecture](Documentation/Installation/System Architecture.jpg)
- [Login Flowchart](Documentation/Installation/Login Flow Chart.jpg)