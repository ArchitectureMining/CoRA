# Coverability OZP
This is the repository for the Information Science research project by
Lucas Steehouwer (4172248, l.steehouwer@students.uu.nl).

# Installation
To install the server you need to have
[composer](https://www.getcomposer.org "get composer") installed. Once
installed, you must run `composer install` to install the required
dependencies. Then, you must set up the project's namespaces by
running `composer dumpautoload -o`. In the `deploy/` folder an
sql-file is included containing commands to set up the database.

## Configuration 
All of the configuration takes place in the files in the `config/`
folder. The only file that you **need** to set up is the
`database.php` file. In this file you have to specify the database's
dsn, user and password. All other settings aim to be sensible
defaults.

# Api
Currently CoRA can only return responses in JSON format, with the only
exception being images of Petri nets.

## Users
There are 3 routes regarding the management of users. These are as
follows:

1. GET: `user/{id}`: gets a specific user
2. GET: `user/[{limit}/{page}]`: list registered users
3. POST: `user/new`: register a new user. The request format can be
   FormData or JSON, as long as it has a `name` field containing the
   user name for the user.

## Petri nets
1. GET `petrinet/{id}`: get a specific Petri net
2. GET `petrinet/[{limit}/{page}]`: list Petri nets
3. GET `petrinet/{id}/image`: get an SVG image of a specific Petri net
4. POST `petrinet/{uid}/new`: upload a new Petri net. The Petri net is
   associated with the user with `user_id=uid`. Upload a file with key
   `petrinet`. Use FormData for this.
5. POST `petrinet/feedback` Receive feedback for a coverability
   graph. Only JSON formats are accepted.
   
### Feedback Request Format
A feedback request consists of five fields: `user_id`, `petrinet_id`,
`session_id`, `initial_marking_id`, and `graph`. All the `id`-fields
should be integers.

A graph should have three fields: `states`, `edges`, and
`initial`. 

The `states` field should be a list of objects. Each state
object has two fields, `state` and `id`. The `id` field should be a
unique integer. **NOTE**: The id's for vertexes and edges should be
disjoint. The `state` field should be a string describing a
marking. For example, the string `p1: 1, p2: 2` assigns 1 tokens to
place `p1` and 2 tokens to place `p2`. When the token-count is
non-numerical, it is assumed to mean ω.

The `edges` field should be a list of objects. Each edge object has
four fields: `from_id`, `to_id`, `transition`, `id`. The `from_id` and
`to_id` fields specify the source and target of the edge. The
`transition` field specifies the label of the edge. The `id` field
specifies the identifier for the edge itself. **NOTE**: The id's for
vertexes and edges should be disjoint.

The `initial` field specifies the initial state of the graph. It needs
to have an `id` field specifying the id of the vertex that is the
initial state. The value of this field should be an integer.

The following is an example of a valid feedback request body.
```json
{
	"user_id": 1570,
	"petrinet_id": 1560,
	"session_id": 5,
	"initial_marking_id": 1557,
	"graph": {
		"states": [
			{
				"state": "p1: 2",
				"id": 0
			},
			{
				"state": "p1: 1, p2: ω",
				"id": 1
			}
		],
		"edges": [
			{
				"fromId": 0,
				"toId": 10,
				"transition": "t1",
				"id": 2
			}
		],
		"initial": {
			"id": 0
		}
	}
}
```

## Sessions
1. GET `session/current`: get the current session for the user. Supply
   the user id by putting it in the body of the request. The
   multipart/form-data media type does not work unfortunately. This is
   because of a limitation in both PHP and Slim.
2. POST `session/new`: create a new session for the user. A session
   describes how a user creates a coverability graph for a certain
   petrinet. As such, you need to supply the `user_id` and
   `petrinet_id` in the body of the request.
