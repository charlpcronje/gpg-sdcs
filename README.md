# GPG Encrypted - Secure Data Capture & Storage

```sh
_____/\\\\\\\\\\\\__/\\\\\\\\\\\\\_______/\\\\\\\\\\\\___________________/\\\\\\\\\\\____/\\\\\\\\\\\\___________/\\\\\\\\\_____/\\\\\\\\\\\___        
 ___/\\\//////////__\/\\\/////////\\\___/\\\//////////__________________/\\\/////////\\\_\/\\\////////\\\______/\\\////////____/\\\/////////\\\_       
  __/\\\_____________\/\\\_______\/\\\__/\\\____________________________\//\\\______\///__\/\\\______\//\\\___/\\\/____________\//\\\______\///__      
   _\/\\\____/\\\\\\\_\/\\\\\\\\\\\\\/__\/\\\____/\\\\\\\__/\\\\\\\\\\\___\////\\\_________\/\\\_______\/\\\__/\\\_______________\////\\\_________     
    _\/\\\___\/////\\\_\/\\\/////////____\/\\\___\/////\\\_\///////////_______\////\\\______\/\\\_______\/\\\_\/\\\__________________\////\\\______    
     _\/\\\_______\/\\\_\/\\\_____________\/\\\_______\/\\\_______________________\////\\\___\/\\\_______\/\\\_\//\\\____________________\////\\\___   
      _\/\\\_______\/\\\_\/\\\_____________\/\\\_______\/\\\________________/\\\______\//\\\__\/\\\_______/\\\___\///\\\___________/\\\______\//\\\__  
       _\//\\\\\\\\\\\\/__\/\\\_____________\//\\\\\\\\\\\\/________________\///\\\\\\\\\\\/___\/\\\\\\\\\\\\/______\////\\\\\\\\\_\///\\\\\\\\\\\/___ 
        __\////////////____\///_______________\////////////____________________\///////////_____\////////////___________\/////////____\///////////_____
01000111   01010000   01000111   01000101   01101110   01100011   01110010   01111001   01110000   01110100   01100101   01100100   00101101   01010011
01100101   01100011   01110101   01110010   01100101   01000100   01100001   01110100   01100001   01000011   01100001   01110000   01110100   01110101
01110010   01100101   00100110   01010011   01110100   01101111   01110010   01100001   01100111   01100101 
```

For usage and development notes: 

# [Usage & DEV](./docs/README.md) <-CLICK THIS LINK

## Original product discussion

- If I look at the PDF form you sent me as an example for data to expect and building the script dynamic to accept any form submissions in the future
- Then I will have to done and working by tomorrow at 14:00 your time.
- I have created many similar solutions in the past so I won't have to start from scratch. I also made sure I made some time for sleeping.
- I will hand you the code and I'll delete the working directory when I'm done, guaranteed, the IP is yours.

### So here is what I am saying I will do:

- Create a class that will capture and store any fields you throw at it including
  - File uploads
  - Text inputs,
  - Text area inputs,
  - Select fields,
  - Check boxes,
  - Radio buttons,
  - Grouped inputs,
  - Array of inputs (Inputs with the same name becomes an array)

### Validation

- I will validate each submission on each field to what is expected.

### Storage

- As the script captures each field the data will be saved in files instead of a database, I will make it a setting to either save XML or JSON,
- I will then write all the files into a folder that will have the same name as the medical ID, (So the medical ID will he compulsory for each submission.
- The uploaded files will also be moved to the folder.

### Encryption

- I will then encrypt the folder with GPG encryption using a key that I will be setting in a config file.

### Logging

- I will use `try {} catch() {}` all errors and log them.
- All the `HTTP` responses will contain all the expected `HTTP` headers like `code 200` for okay or `404` if someone goes to a strange unknown location.
Lastly, I will write every submission to logs with timestamps, fields received and if it validated correctly but the actual data will not be added to the logs.

### Updates and file versions

- When I client updates his data then I will archive the old files in a dated folder inside the clients folder and save the newest files in their place.

### Backups

- I am not going to think about things like backups, that can be done very easily by one of may outsource solutions of which I can suggest some if you'd like.


## Things I will need to get this done on time:

- SSH or ftp access to the server we will be testing on.
- SimpleXML extension for PHP must be installed
- PHP JSON encode and decode extensions
- The folder I work in must allow me to set rules in a .htaccess file. This is so that I can redirect all incoming requests to the index.php file
- Write access to a folder where the data will be stored
- HTML form to test with will be nice, otherwise I will create one by not styled.

---

__For this I would like to charge you $170__

_Please let me know, I am going to eat lunch quickly, and then Ill be back to begin if it is fine with you._