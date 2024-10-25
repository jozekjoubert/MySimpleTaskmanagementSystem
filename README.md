## CREATE ADMIN ACCOUNT
POST http://localhost/api/register

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password",
    "role": "admin" // This should be "admin" for admin role and user for normal user
}


## Get Bearer Token:


POST http://localhost/api/login

set parameters for the following:

[
    {
        "key":"email",
        "value":"jozeknjtaureg@gmail.com",
    },
    {
        "key":"password",
        "value":"12345678",
    }
]

response example:

{"access_token":"3|xqcFGN7DYHve0IhHBSMflmvLXuG3OHcjWWQhWouHc5912e1d","token_type":"Bearer"}


// --------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------

## Get All Tasks For User:

GET http://127.0.0.1/api/httpTasks

set parameters to 
[{"key":"authorization","value":"2|VWWs99RCZrkRkEcPi4VGGVw6wAGPQlyoxjPei70N3c8d6644",}]

response example:
{"data":[{"id":2,"title":"second","description":"description of thingy ma bopppy","created_at":"2024-10-23 13:09:34","updated_at":"2024-10-24 19:44:05"},{"id":4,"title":"123333","description":"asfgdbhasdgfbh","created_at":"2024-10-24 09:26:21","updated_at":"2024-10-24 11:06:41"},{"id":10,"title":"new task being created","description":"testing the description field","created_at":"2024-10-24 19:39:35","updated_at":"2024-10-24 19:39:35"},{"id":11,"title":"new task being created","description":"kjhghfkjghf","created_at":"2024-10-24 20:07:51","updated_at":"2024-10-24 20:07:51"}]}


// --------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------

## Create Task:
POST http://127.0.0.1/api/httpTasks

Parameters below as example:
[{"key":"authorization","value":"2|VWWs99RCZrkRkEcPi4VGGVw6wAGPQlyoxjPei70N3c8d6644",},{"key":"title","value":"api task",},{"key":"description","value":"123 descrioption",},{"key":"status","value":"pending",},{"key":"due_date","value":"2024-12-01",},{"key":"user_id","value":"2",}]

response example:

{"id":12,"title":"api task","description":"123 descrioption","created_at":"2024-10-24 22:31:15","updated_at":"2024-10-24 22:31:15"}


// --------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------


## Updating A Task:
PUT http://localhost/api/httpTasks/{id}

Set the following parameters:
[{"key":"authorization","value":"2|VWWs99RCZrkRkEcPi4VGGVw6wAGPQlyoxjPei70N3c8d6644",},    {"key":"title","value":"api task",},{
"key":"description","value":"123 descrioption",},{"key":"status","value":"pending",},{"key":"due_date","value":"2024-12-01",}]

response example:
{"id":4,"title":"api task","description":"123 descrioption","created_at":"2024-10-24 09:26:21","updated_at":"2024-10-24 22:59:10"}


// --------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------

## Delete A Task:

DELETE http://localhost/api/httpTasks/{id}  

(i.e. {id} = 4)

Set the following parameters:
[
    {
        "key":"authorization",
        "value":"2|VWWs99RCZrkRkEcPi4VGGVw6wAGPQlyoxjPei70N3c8d6644",
    }
]

{"message":"Task and related logs deleted successfully"}
