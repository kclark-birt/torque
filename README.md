This repo contains a set of scripts and instructions to setup a minimally functional server/database for uploading ODB2 data logged from your car in real-time using the [Torque Pro](https://play.google.com/store/apps/details?id=org.prowl.torque) app for Android.

### SETUP ###

  * Hadoop
  * Apache Webserver
  * PHP

Everything was tested on Mint 16, and PHP 5.3.  I'm sure other configs will work you'd just need to know how to set them up :)

### CREATE THE "EMPTY" JSON ###

For this to work right you'll need to create an empty JSON file.  Create a file called torque.json in your document root.  For testing I've chmoded the file to 777 to make sure the script can write to it.

I've placed this in the root of apache at 

```
/var/www
```

###torque-json.php###
Copy over torque-json.php to apache.  I also put this in my document root.  By default the script will grab all of the parameters, even the null ones.  Feel free to make any changes and commit them, I'm a Java guy and by no means a PHP expert.

### Configure Torque Settings ###


To use your database/server with Torque, open the app on your phone and navigate to:

```
Settings -> Data Logging & Upload -> Webserver URL
```

Enter the URL to your **torque-json.php** script and press `OK`. Test that it works by clicking `Test settings` and you should see a success message like the image on the right:

<div align="center" style="padding-bottom:15px;"><img src="http://i63.photobucket.com/albums/h148/kristopher_clark1/Work/Screenshot_2014-04-25-10-08-47_zps642b4f91.png" width="49%" align="left"></img><img src="https://storage.googleapis.com/torque_github/torque_test_passed.png" width="49%" align="right"></img></div>

The final thing you'll want to do before going for a drive is to check the appropriate boxes on the `Data Logging & Upload` page under the `REALTIME WEB UPLOAD` section. Personally, I have both **Upload to webserver** and **Only when ODB connected** checked.

At this point, you should be all setup. The next time you connect to Torque in your car, data will begin saving all your data to torque.json

### Coming Soon ###
  * Visualizations and data comparison using BIRT 4.3.2 and BIRT Viewer Toolkit
  * MapReduce of torque.json
  * Screenshots of webapp using BIRT 4.3.2 and BIRT Viewer Toolkit (Both Hadoop from the JSON and MySQL as seen in orginal project I forked this from)
