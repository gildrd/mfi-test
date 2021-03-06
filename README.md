# MFI Test

```
The context of this test is to provide a simple web service for storing and retrieving moutain peaks.
 
Using PHP (with or without framework) and a database (MysQL/MariaDB or Postgresql), please implement the following features:
 
- Design database table(s) for storing a peak location and attribute: lat, lon, altitude, name
 
- Provide REST api endpoints to :
    * create/read/update/delete a peak
    * retrieve a list of peaks in a given geographical bounding box (geo area)
 
- add an api/docs url to allow viewing the documentation of the api and send requests on endpoints
 
- add an html/javascript page to view the peaks on a map and interactive menus to edit each peak information (name, location)
 
- deploy all this stack using docker and docker-compose
 
The source code should be delivered using bitbucket or github with detailed explanations on how to deploy and launch the project.
```

# Content

This app is using the latest LTS Symfony framework version. It comes with an Swagger interface allowing you to use the API and a simple html page with a map and a form to edit some data.

# How to install

First, clone the git project:
```
git clone https://github.com/gildrd/mfi-test.git
```

Next, once in your project directory, you have to build and up the docker containers:
```
docker-compose up
```

All the following command lines will have to be typed in the docker container:
```
docker-compose exec web bash
cd /app
```

Now that you are in he container, install all the PHP needed packages:
```
composer install
```

Now it is time to manage the database. Exceptionnaly, the .env.dev is commited, this way you have the necessary credentials:
```
php bin/console d:m:m
```

And finally, build the CSS / Js:
```
yarn install
yarn dev
```

# Browsing through the app

The Swagger interface can be found at ```http://127.0.0.1/api/doc```.

The wab page is located to ```http://127.0.0.1```.

