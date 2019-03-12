# ShopInventory
This is a simple but well structured Inventory System for shops of any category.


INSTALLATION PROCEDURE
1. Having downloaded the zip file, extract it to your webserver.
2. Create a folder of your choice and effect the following changes
	a. Open the file - /WEBDIRECTORY/system/config/config.php
		Line 26 - change the details to your webserver url
		Line 337 - change the encryption key to your prefered code.
		Line 393 - Set it to a valid path outside of your public_html folder
		Lines 535 downwards - Change to suite your choice.
	
	b. Open the file /WEBDIRECTORY/system/config/constants.php
		Set your database information.
		- host name
		- database name 
		- user password 
		- user name
	
	c. Create a database with the name which you have set above and import the file inventory.sql
	
	d. When you are done, you can then delete the inventory.sql file

ADMIN DETAILS
	username - Admin
	password  - tRandom29
	
YOU ARE GOOD TO GO!!!
