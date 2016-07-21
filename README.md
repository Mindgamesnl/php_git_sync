# php_zip_manager
sync you'r git project with a web server :D


# HOW TO INSTALL
 - install the files on a web server
 - install the config (see the config section in the readme for more info)
 - install a web hook in you're github/bitbucket project to call the "index.php" file
 - DONE
 
# Config.php
'REPO_ZIP_URL' is the url of the zip (master) file<br />
'OUTPUTPATH' is the path to where the files will be dumped<br />
'USERNAME' is the username to use for the download if you have a PRIVATE repo (using curl)<br />
'PASSWORD' is the password to use for the download if you have a PRIVATE repo (using curl)<br />
<br /><br />
use the default values for a demo ;)
