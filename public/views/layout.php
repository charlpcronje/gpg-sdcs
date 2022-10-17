<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= env('APP_TITLE') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css"/>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

<nav id="header" class="fixed w-full z-10 top-0">

    <div id="progress" class="h-1 z-20 top-0"
        style="background:linear-gradient(to right, #4dc0b5 var(--scroll), transparent 0);"></div>

    <div class="w-full md:max-w-4xl mx-auto flex flex-wrap items-center justify-between mt-0 py-3">

        <div class="pl-4">
            <a class="text-gray-900 text-base no-underline hover:no-underline font-extrabold text-xl" href="#">
                <?= App::data('model.json.model_name') ?>
            </a>
        </div>

        <div class="block lg:hidden pr-4">
            <button id="nav-toggle"
                    class="flex items-center px-3 py-2 border rounded text-gray-500 border-gray-600 hover:text-gray-900 hover:border-green-500 appearance-none focus:outline-none">
                <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <title>Menu</title>
                    <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/>
                </svg>
            </button>
        </div>

        <div class="w-full flex-grow lg:flex lg:items-center lg:w-auto hidden lg:block mt-2 lg:mt-0 bg-gray-100 md:bg-transparent z-20"
             id="nav-content">
            <ul class="list-reset lg:flex justify-end flex-1 items-center">
                <li class="mr-3">
                    <a class="inline-block py-2 px-4 text-gray-900 font-bold no-underline" href="#">Active</a>
                </li>
                <li class="mr-3">
                    <a class="inline-block text-gray-600 no-underline hover:text-gray-900 hover:text-underline py-2 px-4"
                       href="#">link</a>
                </li>
                <li class="mr-3">
                    <a class="inline-block text-gray-600 no-underline hover:text-gray-900 hover:text-underline py-2 px-4"
                       href="#">link</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!--Container-->
<div class="container w-full md:max-w-3xl mx-auto pt-20">

    <div class="w-full px-4 md:px-6 text-xl text-gray-800 leading-normal" style="font-family:Georgia,serif;">

        <!--Title-->
        <div class="font-sans">
            <p class="text-base md:text-sm text-green-500 font-bold">
                &lt; <a href="#" class="text-base md:text-sm text-green-500 font-bold no-underline hover:underline">BACK TO DEFAULT SCHEMA</a></p>
            <h1 class="font-bold font-sans break-normal text-gray-900 pt-6 pb-2 text-3xl md:text-4xl">
                Welcome to <span><?= env('APP_TITLE') ?></span>
            </h1>
            <p class="text-sm md:text-base font-normal text-gray-600">Published <?= date("F j, Y, g:i a");  ?></p>
        </div>

        <p class="py-6">
            👋 Welcome fellow <a class="text-green-500 no-underline hover:underline"
                                href="https://www.tailwindcss.com">Data Capturer</a> and data security fan.

            You are currently viewing the expected POST data when you to a POST request to this current url. You did a GET request this time, try updating the method="POST" on the html FORM tag
        </p>

        <p class="py-6">
            Every schema has the following sections, most of the schema will be defined and but there are some fields at the end under the stats and errors that wil be dynamically populated when a submission is made
            <ul>
                <li>
                    <b>auth:</b>
                    <span> This is not a user name and password kind of auth but just a domain name and ip address lookup of the server sending the data</span>
                </li>
                <li>
                    <b>meta:</b>
                    <span>  This sections collect meta data with every post of request, and it saves the the referrer, ip_address, date, time and execution </span>
                </li>
                <li>
                    <b>storage:</b>
                    <span>In this section all the info ae grouped to save the incoming data to the correct place, and what to do in the case of a error. The fields in this section is:</span>
                    <ul>
                        <li>
                             "endpoint": "Patient",
                        </li>
                        <li>
                            "model_name": "Patient",
                        </li>
                        <li>
                            "description": "Capture patient details with 'Health ID Number' as the unique ID",
                        </li>
                        <li>
                            "id_field_name": "HIN"
                        </li>
                        <li>
                             "id_field_required": true
                        </li>
                        <li>
                            "save_if_error": true
                        </li>
                        <li>
                            "save_file": "${HIN} ${last_name} ${DATETIME} ${REVISION}"
                        </li>
                        <li>
                            "save_path": "patients/${health_card_number}/${save_file}"
                        </li>
                    </ul>
                </li>
                <li>
                    <b>notify:</b>
                    <span>Here you can add email addresses to be notified of success, errors or all submissions</span>
                </li>
                <li>
                    <b>fields:</b>
                    <span>Each field can have the following options / attributes</span>
                    <ul>
                        <li>
                            <b>name: </b>
                            <span>This is the name of the input field</span>
                        </li>
                        <li>
                            <b>type: </b>
                            <span>This can be any one of the basic scalar types plus some more like email, here is a list of all the types supported here</span>
                            <ul>
                                <li>"int"</li>
                                <li>"boolean"</li>
                                <li>"float"</li>
                                <li>"domain"</li>
                                <li>"url"</li>
                                <li>"email"</li>
                                <li>"ip_address"</li>
                                <li>"mac_address"</li>
                                <li>"string"</li>
                            </ul>
                        </li>
                        <li>
                            <b>validate: </b>
                            <span>The validation servers two purposes:</span>
                            <br/>
                            1. To tell the application what data to expect
                            <br/>
                            2. To help the users enter valid data
                            <br/>
                            The following validation can be specified
                            <ul>
                                <li>
                                    "format": "mm/dd/yyyy" (This is for when the type is defined as a date or datetime, then you can use the format validator to specify the format)
                                </li>
                                <li>
                                    "required": true or false
                                </li>
                                <li>
                                    "min_length": 5 (define in amount of characters)
                                </li>
                                <li>
                                    "max_length": 10 (define in amount of characters)
                                </li>
                                <li>
                                    "options": ["Y","N"] Define a list of options
                                </li>
                                <li>
                                    "special_chars": true or false
                                </li>
                                <li>
                                    "allowed_chars": ["-","!"]  (define a list of allowed characters that will override the previous rule)
                                </li>
                                <li>
                                    "disallowed_chars": ["-","&"]  (define a list of disallowed characters? that will override both the prev 2 rules)
                                </li>
                                <li>
                                    "allow_spaces": true or false
                                </li>
                                <li>
                                    "default": "N"  (Specify a default value, if a field is required and have a default value then it will pass)
                                </li>
                                <li>
                                    "regex": for any validation that is not covered by any of the previous rules
                                </li>
                            </ul>
                        </li>
                        <li>
                            <b>hints: </b>
                            <span>Under hints there are two properties to set</span>
                            <ul>
                                <li>
                                    "placeholder": "First Name"    (This is when you are also using the JSON to generate your forms)
                                </li>
                                <li>
                                    "error": "Error Message when the validation did not pass"
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <b>stats: </b>
                    <span>Stats on the form submission</span>
                    <ul>
                        <li>
                            "field_count": amount of fields received in te POST
                        </li>
                        <li>"field_count": received in the POST</li>
                        <li>"field_success_count": successful fields received in the POST</li>
                        <li>"field_fail_count": failed fields received in the POST</li>
                        <li>"upload_count": File uploads received in the POST</li>
                        <li>"upload_success_count": Successful uploads received in the POST</li>
                        <li>"upload_fail_count": Failed uploads received in the POST</li>
                    </ul>
                </li>
                <li>
                    <b>errors: </b>
                    <span>List of errors you will receive after a submission if any</span>
                    <ul>
                        <li>
                            "fields_failed": Count of fields failed
                        </li>
                        <li>
                            "field_fail_reasons": The reasons for failing some fields
                        </li>
                        <li>
                            "uploads_failed": Count of uploads failed
                        </li>
                        <li>
                            "uploads_fail_reasons": Reasons for uploads failed
                        </li>
                    </ul>
                </li>
            </ul>
        </p>


       <blockquote>
            Using a Schema to receive a post your simply need to do a POST httpd request to the endpoint specified by die name of the json model, example: `patient.json` will be used when you POST to https://example.com/patient, this is case sensitive
       </blockquote>


    </div>

    <hr class="border-b-2 border-gray-400 mb-8 mx-4">

    <div class="flex w-full items-center font-sans px-4 py-12">
        <div class="flex-1 px-2">
            <p class="text-base font-bold text-base md:text-xl leading-none mb-2">Charl Cronje</p>
            <p class="text-gray-600 text-xs md:text-base">
                Secure Data Capture & Storage by
                <a class="text-green-500 no-underline hover:underline"
                    href="https://cronje.me">
                    https://blog.cronje.me
                </a>
            </p>
        </div>
    </div>
    <hr class="border-b-2 border-gray-400 mb-8 mx-4">
</div>

<script>
    /* Progress bar */
    //Source: https://alligator.io/js/progress-bar-javascript-css-variables/
    var h = document.documentElement,
        b = document.body,
        st = 'scrollTop',
        sh = 'scrollHeight',
        progress = document.querySelector('#progress'),
        scroll;
    var scrollpos = window.scrollY;
    var header = document.getElementById("header");
    var navcontent = document.getElementById("nav-content");

    document.addEventListener('scroll', function () {

        /*Refresh scroll % width*/
        scroll = (h[st] || b[st]) / ((h[sh] || b[sh]) - h.clientHeight) * 100;
        progress.style.setProperty('--scroll', scroll + '%');

        /*Apply classes for slide in bar*/
        scrollpos = window.scrollY;

        if (scrollpos > 10) {
            header.classList.add("bg-white");
            header.classList.add("shadow");
            navcontent.classList.remove("bg-gray-100");
            navcontent.classList.add("bg-white");
        } else {
            header.classList.remove("bg-white");
            header.classList.remove("shadow");
            navcontent.classList.remove("bg-white");
            navcontent.classList.add("bg-gray-100");

        }

    });


    //Javascript to toggle the menu
    document.getElementById('nav-toggle').onclick = function () {
        document.getElementById("nav-content").classList.toggle("hidden");
    }
</script>

</body>

</html>
<body>

</body>
</html>