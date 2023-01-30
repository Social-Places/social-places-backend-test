# Social Places
## Step 1 - Installation and setup
Here we outline how to get your application up and running as well as provide any insight that you might need.

1. Fork the repository to your own account
2. Clone the forked repository using your preferred git client
   1. While it is not imperative that a git client such as [GitHub Desktop](https://desktop.github.com) or [Fork](https://git-fork.com/) is used we highly,
      highly recommend it.
3. Using [Docker](https://www.docker.com/get-started/) spin up your application
    1. We use docker as our primary tool for running our application in house and during development. While there are
    alternative ways of getting the code to work, at this time, Docker is primarily supported - this is included in our "test"
    as it is a required skill. We provide an alternative as not all CPU's and versions of windows are supported with Docker.
    2. Install Docker Desktop - this should install Docker Compose as well as all the necessary Docker runtimes.
    3. Within the cloned project directory run `docker compose up` (if `docker compose` is not found, please try `docker-compose`).
    4. Your application should now initialize itself, setting up all the necessary directories and internally run `composer install` 
    for all the necessary packages.
4. The application should now be served on [localhost](http://localhost)
5. The backend portion of the test is strictly API based, as such a tool like [Postman](https://postman.com) or [Insomnia](https://insomnia.rest) is required


[Step 2](./Step%202%20-%20Backend%20Brief.md)

### Troubleshooting
1. `docker compose` not found: try `docker-compose` instead
2. If you have issues with the port already in use. Feel free to change line 20 in the `docker-compose.yml` file to be something like `80:81`
    