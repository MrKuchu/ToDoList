## ToDo List
It's a little project made with PHP to practice my programming skills.

 - Create a To Do list
 
![](https://i.imgur.com/REYdDkY.gif)

 - Mark items as done

![](https://i.imgur.com/k8wqqvO.gif)

 - Delete items

![enter image description here](https://i.imgur.com/mQKIoEU.gif)

## How to test it
You can test it on a server running PHP and SQL, create a database called "todo", import tasks.sql into it and run composer. <br />
Or you can also follow the instructions below:
 1. Download and install [XAMPP](https://www.apachefriends.org/)
 2. Open XAMPP and Run Apache & MySQL
 3. Create a folder in C:\xampp\htdocs
 4. Clone this repository in your folder
 5. Run composer
	 1. Download [composer.phar](https://getcomposer.org/composer.phar) in your folder
	 2. Open the console and type:
	 3. `$ cd C:\xampp\htdocs\<your folder>`
	 4. `$ php composer.phar install`
 6. Import tasks.sql
	1. Open [localhost/phpmyadmin/](http://localhost/phpmyadmin/)
	2. Create a new database called "todo"
	3. Import tasks.sql
 7. That's it!.. Open: localhost/&lt;your folder&gt;
