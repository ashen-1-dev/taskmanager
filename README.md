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

### `POST /login` - Register a new user

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