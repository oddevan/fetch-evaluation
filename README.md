# Fetch Receipt Processor

An implementation of the [Fetch Receipt Processor challenge][cha].

[cha]: https://github.com/fetch-rewards/receipt-processor-challenge

## Run the application

First build the image:

```bash
docker build -t oddevan/fetch-evaluation .
```

The image's default command will start the PHP web server on port 80. To map this to port 8080 on
the host computer, use the command:

```bash
docker run --rm -it -p 8080:80 oddevan/fetch-evaluation 
```

### Bonus: Run unit tests

```bash
docker run --rm oddevan/fetch-evaluation composer test-and-lint
```

## Endpoints

### Process Receipt

`POST /receipts/process`

Send the receipt JSON object in the body.

```bash
curl -X 'POST' \
  'http://localhost:8080/receipts/process' \
  -H 'accept: application/json' \
  -H 'Content-Type: application/json' \
  -d '{
  "retailer": "M&M Corner Market",
  "purchaseDate": "2022-01-01",
  "purchaseTime": "13:01",
  "items": [
    {
      "shortDescription": "Mountain Dew 12PK",
      "price": "6.49"
    }
  ],
  "total": "6.49"
}'
```

The response will be a JSON object with the ID of the created entry:

```json
{"id":"01936e61-9e57-73c7-9eb7-230252d3e448"}
```

### Get Points

`GET /receipts/{id}/points`

Use the ID given from the Process step.

```bash
curl -X 'GET' \
  'http://localhost:8080/receipts/01936e61-9e57-73c7-9eb7-230252d3e448/points' \
  -H 'accept: application/json'
```

The response will be a JSON object with the points for the given receipt:

```json
{"points":20}
```

## Application notes

- Written in PHP because that is where I am currently most comfortable.
- A lot of the general architectural decisions are based on my work in [Smolblog][sb], particularly the
  use of `readonly` objects for translating to/from JSON. Ask me about it and I won't stop talking.
- I recognize the challenge allowed the use of in-memory data storage; however, PHP objects only exist
  during the particular web request. For this reason, I used a SQLite database stored in the Docker
	container. I believe this meets the spirit of the challenge in that
	1. the data is tied to the lifecycle of the container, and
	2. no extra services are installed or required

Thanks for the opportunity!

— Evan

[sb]: https://github.com/smolblog/smolblog

## License

**This license is in place as this code is, by request, in a public repository.
For exceptions to this license, please contact me.**

&copy; 2024 Evan Hildreth

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
[GNU Affero General Public License](LICENSE.md) for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.