```php
<?php

error_reporting(-1);

$dotenv = new DotEnv(EXAMPLE_DIR);

?>
<!doctype html>
<html>
<head>
    <title>Usage Example</title>
</head>
<body>
    <h1>Getting environment variables</h1>

    <h2>Object Access</h2>
    <pre>
        $dotenv->{variable}
        $dotenv->{section}->{variable}
    </pre>
    <strong>Example:</strong>
    <pre>
        $dotenv->API->apiUser
    </pre>
    <p>
        User: <?=$dotenv->API->apiUser?><br>
        Password: <?=$dotenv->API->apiKey?>
    </p>


    <h2>ENV Access ($_ENV)</h2>
    <pre>
        $_ENV['{variable}']
        $_ENV['{section}']['{variable}']
    </pre>
    <strong>Example:</strong>
    <pre>
        $_ENV['API_apiUser']
    </pre>
    <p>
        User: <?=$_ENV['API_apiUser']?><br>
        Password: <?=$_ENV['API_apiKey']?>
    </p>

    <h2>ENV Access (getenv)</h2>
    <pre>
        getenv['variable']
        getenv=('section_variable')
    </pre>
    <strong>Example:</strong>
    <pre>
        $_ENV['API_apiUser']
    </pre>
    <p>
        User: <?=getenv('API_apiUser')?><br>
        Password: <?=getenv('API_apiKey')?>
    </p>


    <h2>Array Access</h2>
    <pre>
        $dotenv[{variable}]
        $dotenv[{section}][{variable}]
    </pre>
    <strong>Example:</strong>
    <pre>
        $dotenv['API']['apiUser']
    </pre>
    <p>
        API User: <?=$dotenv['API']['apiUser']?><br>
        API Key: <?=$dotenv['API']['apiKey']?>
    </p>

</body>
</html>
```