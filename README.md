# ShopInventory

DEVELOPER
Emmanuel Obeng - emmallob14@gmail.com
https://github.com/emmallob14

CODEBASE STRUCTURE
The developer has included some of the codebase of the CodeIgniter Framework into the Source Code of this inventory system. This includes libraries for Session Management, Emails, Form Validations, Sanitizing of user inputs and the likes.

MODIFICATIONS
You are free to modify the source code of this system and likewise also forward any suggestions to the Developer via email or add a comments to the github repository - https://github.com/emmallob14/ShopInventory

INVENTORY SYSTEM
This is a simple but well structured Inventory System for shops of any category. The developers will from time to time update the system and include several functionalities that will make the usage of the system much more complete.

INSTALLATION PROCEDURE
1. Having downloaded the zip file, extract it to your webserver.
2. In the Assets Folder there is a ZIP FILE, extract assets.zip into the same place.
3. Create a folder of your choice and effect the following changes
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
