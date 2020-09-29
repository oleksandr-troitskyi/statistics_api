## Set up
1. Install Docker
1. Pull project
1. Add `statistics_api.loc` to `hosts` file
1. Run `make build` at project root directory to build containers and install dependencies

## How it works
- Run `make tests` for tests
- Run `make fill-tables` to fill tables with random data
- Make HTTP request as in example `http://statistics_api.loc/api/score?dateFrom=2019-11-15&dateTo=2020-12-25&hotelId=4`

## Clarifications
- Just for simplification, I write DB access line right into .env file. That should not be done for real world apps. 
- I made Functional and Unit tests for manually created components. I did not write tests for automatically generated Entities.
- I did not cover with tests the Command that fills DB with fake data, as it is more fake than business case.
- I created Functional, Unit and Integration tests (for Repositories).
- As the implementation should ideally work with larger amount of hotels and reviews, I decided to create an additional table with aggregated data. It contains summ of reviews for hotel per each day with average score for this date. Table can be extended to contain such data for months and weeks. Or another tables can be created.
- For requests that group data by calendar week or month, script takes only records that are included into date range. For example, for request `http://statistics_api.loc/api/score?dateFrom=2019-11-15&dateTo=2020-12-25&hotelId=4` only records with `created_date` field equals or more than `2019-11-15` will take a part in first week calculation.