# dotData Syntax & Keys

## What is dotData?

In the case of JavaScript you can refer to object in dot-syntax, I find that really nice. But in PHP you can not, and I find that really terrible, to refer to objects with arrows is more to type and when a property does not exist down the path of referencing an object then it breaks.

To fix this I added a few `data` functions to the App.php and because everything should be extending App, everything should have access to the the data.
Also if you refer to an object that does not exist, then it simply doesn't exist. 

Here is an example of the PHP way and the dotData way:

```php
# I want to create an object called objects, within that another object called tags and within that I want ot create a div class and that class will have some properties;

if (!isset('object')) {
    $object = new stdClass;
    $object->tags = new stdClass;
    $object->tags->div = new Div();
}
```

That was a lot of work for not doing that much, so now with dotData

```php
$this->data('object.tags.div',new Div);
```

You might think that you created an object against a long key of which is `object.tags.div` but if you do a `var_dump` of `$this->data('object')` then you will get the same output as you would get from var_dump($object);

What dotData does is to create objects on the fly and nest them accordingly. 

You can set any value to a dotData reference, including:

- string
- int
- float
- decimal
- object
- anonymous class / objects
- anonymous functions  / closures
- resources
- files
- API calls
- templates

I can't think of anything else but here is a nice use case:

## Nice Use case

You want to make an API call to a public API, you will notice there is an CallAPI function in the `http.php` helper file. 

The endpoint you wan to call is `https://fakerapi.it/api/v1/books?_quantity=5`

```php
$this->data('api.books',function() {
    return CallAPI('get', 'https://fakerapi.it/api/v1/books', ['_quantity',5]);
});
```

Now when you want to get the data from that api, you simply need to:

```php
foreach($this->data('api.books') as $book) {
    print_r($book);
};
```

The above will output all the books.

## A few last things you should know about dotData

The data to iterate through can also be specified by adding a 3rd param @param string $dotName is dot notation string, each '.' separates an object but in this notation you can also select arrays by adding a ':' So the string may look something like:

"person.hobbies:0.hobbyName" Equal to "$person->hobbies[0]->hobbyName"

