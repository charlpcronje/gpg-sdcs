# Progress Update

I am 70% finished I would say, because it is such a specific application I didn't want to bloat the whole thing by using a framework like Laravel or slim. I did however make a nice project structure that will be easy to extend if you ever have the need to, it consists out of:
- *Init:* An include that includes all the rest of the files that is critical for the  application to work
- *DotEnv class:* to parse a .env file that contains all the config options
- *Constants:* where I define a few paths
- *Log class:* 
  - This will write log files for everything that the application does, it's got the following log methods: debug, info, notice, warning, error, critical, alert, emergency
  - It either logs to a txt file, this is super fast and uses some built in server functions and won't ever fail, so for critical events this is nice to have, but the file is boring.
  - It can also log to HTML, it is still fast (takes less than 1 / 1000 of second), the output is nicely styled and can be used to present to a client as a report.
- *Autoloader:* To auto load all classes from after initial initialization, so there is no need to include any new files, the application will find the files and include them only when they are needed

## Helpers
- *general:* so far just one function `env($var)` to read settings in the `.env` file
- *log:* functions as shorthand for using the `Log class`, and some others to `debug`, for instance: `dd($var)` simply does a `var_dump` and `dies`, `pd($var)`, does a `print_r()` and `dies`. There are a few more and have comments for usage

## Further Config
- The paths for the logs and where the files will be stored are configurable in the `.env` file.
- The encryption key for GPG encryption is also in the `.env` file

Now the actual work has begun, now that I can easily add more classes to accept `POSTS` and `FILE UPLOADS` and have a way to log what is happening and to `encrypt` files.

Here is how I am planning to go ahead with the last part:

- For each submission the will be an `endpoint`, like a `RESTFull API`, for instance: `https://domain.com/addPatient`, where `addPatient` is the endpoint that defines what the application is expecting.
- There will a `addPatient.json` file that describes what the application should expect when it receives a `POST` with data
- I did not define the structure yet, but it should be something like this:

```json
{
  "call": "add Patient",
  "logs": true,
  "encrypt": true,
  "notify": true,
  "notify_email":"charl@cronje.me; another@someone.com",
  "notify_onerror":"charl@cronje.me",
  "id_field":"client_medical_id",
  "id_field_required": true,
   "fields": [{
        "name": {
        "type": "text",
        "validate": {
           "required": true,
           "min_char": 5,
           "max_char": 10
          }
        }
     }],[{
        "surname":{
              "type": "text",
              "validate":{
                  "required": true,
                  "min_char":5,
                  "max_char":10
              },
              "error_message":"The surname field is required and needs to be 5 characters and less than 10 characters"
         }
        ],[{
         "email": {
              "type":"email",
               "validate": {
                   "required":false,
               },
               "error_message":"Must be a valid email address"
         }
       ]  
  }
```

- Doing checks like this for each field works well for validation and also so that you know what to expect so that people don't submit a string of JavaScript or PHP to try and inject some code onto the server.
- Then you might just want to capture anything that gets submitted to the server in which case there is a `default.json` that won't specify specific field names but only types. So `"inputs":"toJSON"` or `CSV` or `XML`, "files" to client directory. and so on.
- You will notice that in the `JSON` above I added a `id_field` `param`, that is the field name the application will use as the folder name to save all the data under. If there is no id_field specified it will put everything in a default folder which could get confusing quickly
- So no matter what data you want to `accept` in the future, all you need to do is create a new `.json` file for the structure. When the `json` file exists the endpoint will work and the data will get saved.

## Extensibility

- The submission class can easily be extended in the future to allow for database saving of the data etc.

## Next Update

- I will send you another update by 13:00 your time just so that you can know what to expect at 14:00, I might be done early.

Please let me know if you think I am approaching this in a weird wat, or if you have any suggestions.

Regards
Charl