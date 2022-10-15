# File Uploads

> PHP Class for uploading file or files to the server - edenreich/PHP-Upload-Class: PHP Class for uploading file or files to the server

PHP Class for uploading file or files to the server

Make sure the form is submitted:

```php
if(Upload::submitted()) {
  // rest of the code goes here
}
```

Make an instance of the class

```php
$upload = new Upload(YOUR-HTML-INPUT-NAME); 
```

Set the directory where you want to upload the files, by default it will upload to your main directory

```php
$upload->setDirectory('img/'); 
```

You may also specify that you want to create this directory if it's not exists

```php
$upload->setDirectory('img/')->create(true); 
```

You can set the rules you want for your upload using the following syntax:

```php
$upload->addRules([
        'size' => 2000,
        'extensions' => 'png|jpg|pdf'
]);
```

or

```php
$upload->addRules([
        'size' => 2000,
        'extensions' => ['png', 'jpg', 'pdf']
]);
```

Set this only if you want to have a encrypt file names(optional for security):

```php
$upload->encryptFileNames(true);
```

You may also specify that you want only certain file type to be encrypted like so:

```php
$upload->encryptFileNames(true)->only(['jpg']); // only jpg files will be encrypted
```

Or also the following syntax:

```php
$upload->encryptFileNames(true)->only('jpg|png|txt'); // only jpg, png and txt files will be encrypted
```

After all is set just run the following command

```php
$upload->start();
```

## Events

Whenever a file has been successfully uploaded.

```php
$upload->success(function($file) {
  // handle the file
});
```

If something went wrong listen to error.

```php
$upload->error(function($file) {
  // handle the file
});
```

## Error Handling

Check wether there are errors and if there arent errors, proccess the upload:

```php
if($upload->unsuccessfulFilesHas()) {
  // display all errors with bootstraps
  $upload->displayErrors();

  // now of course you may formatting it differently like so
  foreach($upload->errorFiles as $file) {
    // do whatever you want with the file object
    // - $file->name
    // - $file->encryptedName *only if you asked to encrypt*
    // - $file->type
    // - $file->extension
    // - $file->size
    // - $file->error
    // - $file->errorMessage
  }
} else if($upload->successfulFilesHas()) {
  $upload->displaySuccess();

  // now of course you may formatting it differently like so
  foreach($upload->successFiles as $file) {
    // do whatever you want with the file object
    // - $file->name
    // - $file->encryptedName *only if you asked to encrypt*
    // - $file->type
    // - $file->extension
    // - $file->size
  }
}
```

#### Here is another method to show you useful errors if something went wrong:

```php
print_r($upload->debug()); // There are some errors only you should look at while setting this up
```