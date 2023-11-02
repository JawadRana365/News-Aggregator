Download the project keep it in seperate folder and use following commands to run the project

-> Step 1
        Clone the the project from git usig following commands
        git Clone

-> Step 2
        Run the following command to run the docker containers
        
                docker-compose rm -f
                docker-compose pull
                docker-compose up --build -d

-> Step 3
        Open seperate terminal in same folder to run database migrations and cron job

        run following command to go inside laravel docker container
            docker exec -it news-aggregator-backend-1 sh

        run database migrations
            php artisan migrate

        run cron jon and wait for 2 minutes atleast
            php artisan schedule:work

-> Step 4
        Open google chrome and run the following link to get the project
            http://localhost:3000/


