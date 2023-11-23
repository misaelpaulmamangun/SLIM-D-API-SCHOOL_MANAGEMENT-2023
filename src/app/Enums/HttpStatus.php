<?php

namespace App\Enums;

enum HttpStatus: int
{
	case OK = 200;
	case NOT_FOUND = 404;
	case SERVER = 500;
	case UNAUTHORIZED = 401;
}
