This repo contains a set of scripts and instructions to setup a minimally functional server/database for uploading ODB2 data logged from your car in real-time using the [Torque Pro](https://play.google.com/store/apps/details?id=org.prowl.torque) app for Android.

### SETUP ###

  * Hadoop
  * Apache Webserver
  * PHP

Everything was tested on Mint 16, and PHP 5.3.  I'm sure other configs will work you'd just need to know how to set them up :)

### CREATE THE TEMP FILE SYSETM ###
Hive doesn't support INSERT INTO like other database system's do.  To get around this and save unnecessary IO, I created a temp file system 
in memory.  Since we only need to store one JSON object at a time it doesn't need to be very large.

First create the folder

```
sudo mkdir /mnt/ramdisk
```

Then change the ownership

```
sudo chown hduser:hadoop /mnt/ramdisk
```

Finally, mount the filesystem

```
sudo mount -t ramfs -o size=20m ramfs /mnt/ramdisk
```

### CREATE THE EMPTY JSON FILE ###

For this to work right you'll need to create an empty JSON file.  Create a file called torque.json in your document root.  For testing I've chmoded the file to 777 to make sure the script can write to it.

```
touch /mnt/ramdisk/torque.json
chmod 777 /mnt/ramdisk/torque.json
```

### torque-json.php ###
First copy the folder thrift from this project to your document root.  Then copy torque-json.php to the document root.

### CREATE THE HIVE DB ###
For this I'm using Hive 0.14.0 along with [hive-json-serde](https://code.google.com/p/hive-json-serde/wiki/GettingStarted).  First you need to create the table.  I've provided my HQL inside of [HQL for Torque DB](HQL%20for%20Torque%20DB.txt).  Open up hive and paste this HQL in there making the required changes for your build.

Next you'll need to tell Hive where to find the SerDe jar using a command like this

```
ADD JAR /path/to/jar/hive-json-serde-0.2.jar
```

### Start hiveserver2 ###
Before torque-json.php can write to the hive table you'll need to start hiveserver2.

```
hiveserver2 --service hiveserver
```

### CONFIGURE THE TORQUE SETTINGS ###


To use your database/server with Torque, open the app on your phone and navigate to:

```
Settings -> Data Logging & Upload -> Webserver URL
```

Enter the URL to your **torque-json.php** script and press `OK`. Test that it works by clicking `Test settings` and you should see a success message like the image on the right:

<div align="center" style="padding-bottom:15px;"><img src="http://i63.photobucket.com/albums/h148/kristopher_clark1/Work/Screenshot_2014-04-25-10-08-47_zps642b4f91.png" width="49%" align="left"></img><img src="https://storage.googleapis.com/torque_github/torque_test_passed.png" width="49%" align="right"></img></div>

The final thing you'll want to do before going for a drive is to check the appropriate boxes on the `Data Logging & Upload` page under the `REALTIME WEB UPLOAD` section. Personally, I have both **Upload to webserver** and **Only when ODB connected** checked.

At this point, you should be all setup. The next time you connect to Torque in your car, data will begin saving all your data to torque.json

### IMPORT JSON INTO HIVE ###
Now that you've got data coming in and the table setup you just need to issue one command from Hive to load the JSON.

```
LOAD DATA LOCAL INPATH '/var/www/torque.json' OVERWRITE INTO TABLE torque_db;
```

And then test it with some HQL like this

```
select eml from torque_db;
```

### Coming Soon ###
  * Auto import the JSON into Hive
  * Visualizations and data comparison using BIRT 4.3.2 and BIRT Viewer Toolkit
  * MapReduce of torque.json
  * Screenshots of webapp using BIRT 4.3.2 and BIRT Viewer Toolkit (Both Hadoop from the JSON and MySQL as seen in orginal project I forked this from)
