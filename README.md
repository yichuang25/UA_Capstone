<h1>Setup Instructions</h1>

1. [install docker](https://docs.docker.com/get-docker/)
2. Clone the repository.
3. Build the custom image using ```docker build [path to Dockerfile] -t [name]:[tag]```
4. In ```docker-compose.yaml```, edit the ```image:``` of the wordpress service to match the name & tag given in step 3
5. Delete the ```wp-data/``` folder
6. Inside the root folder of the repository run ```docker-compose up -d```
7. Run ```git stash```
8. You can then open a browser and navigate to [localhost:8080](localhost:8080)
9. To access the admin page go to [localhost:8080/wp-admin](localhost:8080/wp-admin)
10. Go to the ```Apearence->Themes``` section of the wp admin dashboard and enable the Pedal theme.
11. go to the ```Pages``` section of the wp admin dashboard and add the following pages (published)
  > - pedal-home
  > - pedal-employee
  > - pedal-customer
  > - pedal-new-ticket
  > - pedal-db-test
12. Click the ```Quick Edit``` button for each page and set their slugs to match the names in step 11

<h2>Database</h2>

 - On the wordpress container, you can use ```pedal_db``` as the host name. <br>
 - To find the IP A.K.A. Servername of the mssql database container run,<br>
```docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' [container-id]```<br>
 - The directory ```/sql_scripts``` is mounted to the root folder of the container.

<br><h3>Docker Container</h3>
- To access it through the docker container type ```docker exec -it [container-id] "bash"``` Use powershell.
- To exit the container type ```exit```.
- Then run sql commands from the container using ```/opt/mssql-tools/bin/sqlcmd -S localhost -U SA -P 'Passw0rd'```.

<br><h3>Host Machine</h3>
- You can uncomment the port in the compose file to expose it to the host machine.
- To run sql commands from the host machine type ```sqlcmd -S localhost -U SA -P 'Passw0rd'```. 

<br><h3>Transact SQL</h3>
- To Execute your sql cmd type GO.
- To Exit sqlcmd type ```exit``` or ```quit```.
- To run sql scripts append ```-i [filename]``` to ```sqlcmd```.

- Example: <br>
    ```1> Select @@Version```<br>
    ```2> Go ```

