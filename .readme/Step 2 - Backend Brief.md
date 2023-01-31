# Social Places
## Step 2 - Backend Brief
### What to do?
1. Create, Read, Update and Delete (CRUD) via API
   1. Within the project there is a controller called the `ContactController.php` with the necessary function stubs for creating, reading, updating and deleting.
      1. Create: Accepting a POST request on the route provided, create the necessary record for the Entity\Model returning the necessary information, alert and status code. 
        Important: Validation 
      2. Read: Accepting a GET request with an identifier and handling any issues there with, return the expected data of the record.
      3. Update: Accepting a PUT\POST request on the route, update the record and handle the errors as required.
      4. Delete: Accepting a DELETE request, remove the record from the database.
   
      Please note: There are unit tests to assist you with expectations.
2. Implement a unit test for the `index` function of the `ContactController.php`, validating the information that is returned.
3. For those applying for senior roles or otherwise seeking a challenge, complete the `import` function of the `StoreController.php` together with the `store-import.xlsx`

[Step 3 - Completion](./Step%203%20-%20Completion.md)

### Testing
#### Docker
To run the tests required please using Docker, connect to the Docker container using `docker compose exec application bash` or 
`docker-compose exec application bash` in the project directory this will load you into the Docker environment. 

Once loaded into the Docker environment, run `php bin\phpunit` to run the tests

#### Native
If you have chosen to not use docker, you can run the tests simply by being in the project directory and running `php bin\phpunit`.
