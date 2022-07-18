<?php
namespace Tests\Utilities;

class ValidationMessage
{
    const EMAIL_IS_REQUIRED = "The email field is required.";
    const EMAIL_IS_UNIQUE =  "The email has already been taken.";
    const EMAIL_HAS_FORMAT = "The email format is invalid.";
    const PASSWORD_IS_REQUIRED = "The password field is required.";
    const PASSWORD_SHOULD_BE_LEAST_6_CHAR = "The password must be at least 6 characters.";
    const CURRENCY_ID_IS_REQUIRED =  "The currency id field is required.";
    const CURRENCY_ID_SHOULD_EXIST_IN_TABLE =  "The selected currency id is invalid.";
    const CURRENY_ID_SHOULD_BE_INTEGER = "The currency id must be an integer.";
    const USERNAME_IS_REQUIRED =  "The username field is required.";
    const USERNAME_SHOULD_BE_LEAST_6_CHAR =  "The username must be at least 6 characters.";
}