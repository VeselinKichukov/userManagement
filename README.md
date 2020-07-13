<p align="center"><img src="https://static.eleshop.nl/mage/skin/frontend/elebase/eleshop/images/eleshop_logo.svg"></p>


# UserManagementApp

## Steps to reproduce the application

- After cloning the repo, the .env file needs to be created and changed with the provided .env.example
- We need to install our packages: composer install
- App key needs to be generated: php artisan key:generate
- The database needs to be migrated
- The commands need to be executed: npm install && npm run dev
- After the server is started, the database needs to be populated with users and registrations records 
that will be created on the fly by the seeder: php artisan migrate:fresh --seed
- In order to check the graph with registration duration we need to register for access 
and unregister to create at least one record in the database for us. We can do that through the
UI or create records in the database with our id.
Note: When we register for access we need to stay "registered" for at least 1 minute
in order to take effect on the graph. It was my design choice. 

