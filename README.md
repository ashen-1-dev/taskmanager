# Deployment

1. Clone project

```shell 
git clone git@github.com:ashen-1-dev/taskmanager.git
```

2. Fill .env file (database connection, jwt, etc..)

3. Install dependencies

```shell 
composer install
```

4. Create SSL keys for using JWT
   token [(more info)](https://github.com/lexik/LexikJWTAuthenticationBundle/blob/2.x/Resources/doc/index.rst#generate-the-ssl-keys)

5. Start server would you like to use (e.g nginx or Apache) <br>
 ...or execute local symfony server: 
```bash
symfony server:start
```
(requires Symfony CLI)
___

# API

## Tasks

### `GET /tasks` - Get all users tasks

Returns array of [Tasks](#task)

### `GET /tasks/:taskId` - Return task with specific id

Return [Task](#task)

### `POST /tasks` - Create task

Request body

|    field    | type   | constraints                 |
|:-----------:|--------|-----------------------------|
|    title    | string | required, min: 1, max: 100  |
| description | string | required, min: 1, max: 1000 |
| completedAt | ?date  |                             |

Return [Task](#task)

### `PUT /tasks/:taskId` - Edit task

Request body

|    field    | type   | constraints                 |
|:-----------:|--------|-----------------------------|
|    title    | string | required, min: 1, max: 100  |
| description | string | required, min: 1, max: 1000 |
| completedAt | ?date  |                             |

Return [Task](#task)

### `DELETE /tasks/:taskId` - Delete task

If success return empty result

### `POST /tasks/:taskId` - Mark task as completed

If success return empty result

___

## Auth

### `POST /register` - Register a new user

Request body

|  field   | type   | constraints                |
|:--------:|--------|----------------------------|
| username | string | required, email            |
| password | string | required, min: 6, max: 100 |

Return [User](#user)

### `POST /login` - Login user

Request body

|  field   | type   | constraints                |
|:--------:|--------|----------------------------|
| username | string | required, email            |
| password | string | required, min: 6, max: 100 |

Return [Token](#token)
___

## Types

### Task

|    field    | type   |
|:-----------:|--------|
|     id      | string |
|    title    | string |
| description | string |
|   status    | string |
| completedAt | ?date  |
|  createdAt  | date   |

### User

| field | type   |
|:-----:|--------|
|  id   | string |
| email | string |

### Token

| field | type   |
|:-----:|--------|
| token | string |

___

# TODO:

- DTO's
- Add multiple locale support
- Single response format for all types of errors
- Add interfaces to entity repositories