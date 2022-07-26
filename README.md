# Expenses Management

## User Stories

- [x] As a user, I want to Sign up
- [x] As a user, I want to sign in
- [ ] As a user, I want to reset passsword if I forget
- [ ] As a signed in user, I want to get list of all currencies
- [x] As a signed in user, I want to select my currency
- [ ] As a signed in user, I want to change password
- [x] As a signed in user, I want to sign out
- [x] As a signed in user, I want to add a new category
- [x] As a signed in user, I want to update a category
- [x] As a signed in user, I want to delete a category
- [x] As a signed in user, I want to get a list of my categories
- [x] As a signed in user, I want to add different wallets
- [x] As a signed in user, I want to update my wallets
- [x] As a signed in user, I want to delete my wallets
- [x] As a signed in user, I want to get a list of my wallets
- [x] As a signed in user, I want to enter my transactions based on related category and related wallet
- [x] As a signed in user, I want to delete my transactions
- [x] As a signed in user, I want to update my transactions
- [x] As a signed in user, I want to get list of my transactions
- [x] As a signed in user, I want to get list of my wallet's transactions
- [ ] As a signed in user, I want to get a Monthly report
- [ ] As a signed in user, I want to get a Weekly report
- [ ] As a signed in user, I want to get a Daily report

## Running Project
1. ``` git clone git@github.com:hanieas/Expense-Mgmt-Backend.git ```
2. ``` cd src ```
3. ``` cp .env.example .env ```
4. ``` docker-compose exec php composer install ```
5. ``` docker-compose build ```
6. ``` docker-compose up -d ```
7. ``` docker-compose exec php php artisan key:generate ```
8. ``` docker-compose exec php php artisan migrate ```
9. ``` docker-compose exec php php artisan db:seed ```
10. ``` docker-compose exec php php artisan passport:install ```
