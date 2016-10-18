# Dilbert3.0

Dilbert is Time-manager & attendance log which helps the Employers/Admin to keep track of the time spent by their fellow employees.

It has an Admin/Employer level interface & User/Employee level interface, wherein Admin can view all the employee logs & details (like @ --:-- time Employee logged on to the System & started his day & @ --:-- time the Employee logged out of the system & left office, track in detail like for how long the employee was working, for how long & how many times he/she took break, etc.).

In User/Employee level, 
* the User can track his/her logs, as in @ what time he/she logged in & @ what time he/she logged out.
* Get summary of logs of past weeks & even months.
* Display hours & minutes user contributed today (also notifications regarding the breaks taken & confirming  whether user had really taken a break, or was at meeting or doing some official work)


The Authentication is currently done through Google account.

This Project is created in 2 phases : Website & Chrome app.

Website:
* Used to display all the logs of the user/s & projects the user's working on.
* Display companies owned by the Admin/Owner (Currently 1st company details is filled when logging in, while other company details are personally added by the owner).
* Localization implemented for the website & even though company selects a default language, users are given freedom to choose the language they want the page to display, whenever the user logs in.

Chrome app -> Trevor:
* Main function is to track current state of the user(whether user is Active, Idle, Offline) & enter logs based on that.
* Displays hours users have contributed today, a donut chart displaying  hours user worked, was idle & offline.
* Logs out all the users from the app when the Organization is deleted.
* Internationalization implemented & displays the language set by the user in his/her profile(Language selection option provided in website).
* Displays the list of projects user's working on & tracks the hours contributed to that project (Note: user has to select the project he/she is currently working on).

Technologies, Frameworks & Packages used used:
* Languages: HTML, CSS, Javascript, PHP
* Database: MySQL
* Frameworks: AngularJS, NodeJS, Laravel
* Packages & Technologies used:
	- Laravel:
		* Socialite(laravel/socialite) - For Google Authentication
		* Redis(predis/predis) - For redis buffer & queue system & for data broadcasting
		* Entrust(zizaco/entrust) - For Role-Permission management
	- NodeJS: (Intercation between Chrome app & PHP)
		* Express
		* SocketIO
		* Redis Queue System
	- AngularJS:
		* Angular-translate(pascalprecht.translate) - for i18n
		* SocketIO(socket-io) - to interact with NodeJS



Issues:
### Those who use a Mac and run MAMP for localhost use the following

In `config/database.php` add the following line under `mysql` after `password`

```
'unix_socket' => '/Applications/MAMP/tmp/mysql/mysql.sock',
```
