# Models, (Expected Inputs)

In most MVC type frameworks you will see the models are a description of the database, or a schema for the ORM used. 

> The submissions also uses models and for the same reason, to describe the data that can be expected on submission

The models directory takes json models to describe the expected data, the name of the `.json` file determines the endpoint that will test for that data

```json
Example: 
"Model": "/models/addPatient.json"

# Corresponds to
"URL": "https://domain.com/addPatient"
```

Here is some example JSON for the models

```json
{
    "auth": {
        "allowed_domains": ["cronje.me","*"],
        "allowed_ips": ["10.0.0.1"]
    },
    "meta": {
        "referrer": "${REFERRER}",
        "ip_address": "${IP_ADDRESS}",
        "date_time": "${DATETIME}",
        "duration": "${DURATION}"
    },
    "storage": {
        "endpoint": "Patient",
        "model_name": "Patient",
        "description": "Capture patient details with 'Health ID Number' as the unique ID",
        "id_field_name": "HIN",
        "id_field_required": true,
        "save_if_error": true,
        "save_file": "${HIN} ${last_name} ${DATETIME} ${REVISION}",
        "save_path": "patients/${health_card_number}/${save_file}"
    },
    "notify": {
        "success": ["charl@cronje.me"],
        "error": ["charl@cronje.me","admin@cronje.me"],
        "all": ["indo@cronje.me"]
    },
    "fields": [{
        "last_name": {
            "type": "text",
            "validate": {
                "required": true,
                "regex": "/^[a-zA-Z|\\D|\\W]{2,30}$/"
            },
            "hints": {
                "placeholder": "Last Name",
                "error": "This field is required, 2 or more and 30 or less Characters, A-Z and spaces allowed"
            }
        }
    },
    {
        "first_name": {
            "type": "text",
            "validate": {
                "required": true,
                "regex": "/^[a-zA-Z|\\D|\\W]{2,30}$/"
            },
            "hints": {
                "placeholder": "First Name",
                "hint": "This field is required, 2 or more and 30 or less Characters, A-Z and speces allowed"
            }
        }
    },
    {
        "date_of_birth": {
            "type": "date",
            "validate": {
                "required": true,
                "format": "mm/dd/yyyy"
            },
            "hints": {
                "placeholder": "Choose between M, F and Other",
                "error": "This field is required, Choose between M, F and Other"
            }
        }
    },
    {
        "sex": {
            "type": "enum",
            "validate": {
                "required": true,
                "options": [
                    "M",
                    "F",
                    "Other"
                ]
            },
            "hints": {
                "placeholder": "Choose between M, F and Other",
                "error" : "This field is required, Choose between M, F and Other"
            }
        }
    },
    {
        "phone": {
            "type": "phone_number",
            "validate": {
                "required": true,
                "regex": "/^\\(?([0-9]{3})\\)?[-.\\s]?([0-9]{3})[-.\\s]?([0-9]{4})$/"
            },
            "hints": {
                "placeholder": "123-123-1239",
                "error" : "The telephone number must be at least 10 digits and formatted like: 123-123-1239"
            }
        }
    },
    {
        "preferred_pharmacy": {
            "type": "enum",
            "validate": {
                "required": false,
                "options": [
                    "Y",
                    "N"
                ],
                "default": "N"
            },
            "hints": {
                "placeholder": "",
                "error" : "Choose between Y and N"
            }
        }
    },
    {
        "text_input": {
            "type": "text",
            "validate": {
                "required": true,
                "min_length": 3,
                "max_length": 10,
                "special_chars": false,
                "allowed_chars": [
                    ",",
                    "-"
                ],
                "disallowed_chars": [
                    "&"
                ],
                "allow_spaces": true,
                "default": "N"
            },
            "hints": {
                "placeholder": "",
                "error" : "This is a required field, Min 3 chars, Max 10 chars, No Special Characters except for ',' and '-'"
            }
        }
    }],
    "stats": {
        "field_count":"${FIELD_COUNT}",
        "field_success_count": "${FIELD_SUCCESS_COUNT}",
        "field_fail_count": "${FIELD_FAIL_COUNT}",
        "upload_count": "${UPLOAD_COUNT}",
        "upload_success_count": "${UPLOAD_SUCCESS_COUNT}",
        "upload_fail_count": "${UPLOAD_FAIL_COUNT}"
    },
    "errors": {
        "fields_failed": ["${FIELDS_FAILED}"],
        "field_fail_reasons": ["${FIELD_FAIL_REASONS}"],
        "uploads_failed": ["${UPLOAD_FAILED}"],
        "uploads_fail_reasons": ["${UPLOAD_FAIL_REASONS}"]
    }
}
```

This scheme got very long very quickly so to save you from repeating yourself all the time, I made it that you can extend schema's, by default all the sections of the schema will be added to the derived schema, but you can exclude some of them by using the exclude option in the extends object in as an array.

The extends must be the first object in the schema, after that you can go on as normal, if you define the same parts as the derived json then it will override al the same keys, but in case of the fields, it will also override the the same fields but if you don't use the same field names then the they will be added to the derived.

```json
{
    "extends": {
        "schema": "Base",
        "exclude": ["fields","auth"]
    }
}
```

I created a `base.json` that contains the following

```json
{
    "auth": {
        "allowed_domains": ["*"],
        "allowed_ips": ["*"]
    },
    "meta": {
        "referrer": "${REFERRER}",
        "ip_address": "${IP_ADDRESS}",
        "date_time": "${DATETIME}",
        "duration": "${DURATION}"
    },
    "storage": {
        "endpoint": "Patient",
        "model_name": "Patient",
        "description": "Capture patient details with 'Health ID Number' as the unique ID",
        "id_field_name": "HIN",
        "id_field_required": true,
        "save_if_error": true,
        "save_file": "${HIN} ${last_name} ${DATETIME} ${REVISION}",
        "save_path": "patients/${health_card_number}/${save_file}"
    },
    "notify": {
        "success": [],
        "error": [],
        "all": []
    },
    "fields": [
    {
        "HIN": {
            "type": "HIN"
            "validate": {
                "required": true,
                "regex": "/^[0-9]{10,10}$/"
            },
            "hints": {
                "placeholder": "Health ID Number",
                "error": "Must be 10 Digits"
            }
        }
    }],
    "stats": {
        "field_count":"${FIELD_COUNT}",
        "field_success_count": "${FIELD_SUCCESS_COUNT}",
        "field_fail_count": "${FIELD_FAIL_COUNT}",
        "upload_count": "${UPLOAD_COUNT}",
        "upload_success_count": "${UPLOAD_SUCCESS_COUNT}",
        "upload_fail_count": "${UPLOAD_FAIL_COUNT}"
    },
    "errors": {
        "fields_failed": ["${FIELDS_FAILED}"],
        "field_fail_reasons": ["${FIELD_FAIL_REASONS}"],
        "uploads_failed": ["${UPLOAD_FAILED}"],
        "uploads_fail_reasons": ["${UPLOAD_FAIL_REASONS}"]
    }
}
```

So now to have a complete schema you only need to specify the `fields`