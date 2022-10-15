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
  "from_domain": ["cronje.me"],
  "model_url": "addPatient",
  "model_name": "Add Patient",
  "description": "Capture patient details with 'Health ID Number' as the unique ID",
  "id_field_name": "HIN",
  "id_field_required": true,
  "save_path": "patients/${health_card_number}/${save_file}",
  "save_file": "${HIN} ${last_name} ${DATETIME} ${REVISION}",
  "referrer": "${REFERRER}",
  "ip_address": "${IP_ADDRESS},"
  "fields": [{
    "last_name": {
        "type": "text",
        "validate": {
            "required" : true,    
            "regex": "/^[a-zA-Z|\D|\W]{2,30}$/",
        }
        
    },
    "first_name": {
        "type": "text",
        "validate": {
            "required" : true,    
            "regex": "/^[a-zA-Z|\D|\W]{2,30}$/",
        }
    },
    "date_of_birth": {
        "type": "date",
        "validate": {
            "required" : true,
            "format" : "mm/dd/yyyy"
        }
    },
    "sex": {
        "type": "enum",
        "validate": {
            "required" : true,
            "options" : ["M","F","Other"]
        }
    },
    "phone": {
        "type": "phone_number",
        "validate": {
            "required" : true,
            "regex" : "/^\\(?([0-9]{3})\\)?[-.\\s]?([0-9]{3})[-.\\s]?([0-9]{4})$/",
        }
    },
    "preferred_pharmacy": {
        "type": "enum"
        "validate": {
            "required" : false,
            "options" : ["Y","N"],
            "default" : "N"
        }
    },
    "text_input": {
        "type": "text"
        "validate": {
            "required" : true,
            "min_length" : 3,
            "max_length" : 10,
            "special_chars" : false,
            "allowed_chars" : [",","-"],
            "disallowed_chars" : ["&"],
            "allow_spaces": true,
            "default" : "N"
        }
    }]
}
```
