<?php

namespace App\Http\Enums;

enum Codes: string {
    case SUCCESS_USER_CREATE                = "0001";
    case ERROR_USER_EXIST                   = "0002";
    case ERROR_OCURRED                      = "0003";
    case ERROR_USER_NOT_EXIST               = "0004";
    case ERROR_USER_BLOCKED                 = "0005";
    case ERROR_OTP_INCORRECT                = "0006";
    case ERROR_LOGIN                        = "0007";
    case ERROR_NOT_FOUN_DATA                = "0008";
    case SUCCESS_UPDATE                     = "0009";
    case SUCCESS_CREATE                     = "0010";
    case SUCCESS_DELETED                    = "0011";
    case RECORD_ACTIVATE                    = "0078";
    case RECORD_DISABLED                    = "0079";

    public function getMessage(): array {
        return match($this) {
            self::SUCCESS_USER_CREATE           => ['code' => $this->value, 'message' => 'User created successfully'],
            self::ERROR_USER_EXIST              => ["code" => $this->value, 'message' => 'User already exists'],
            self::ERROR_OCURRED                 => ['code' => $this->value, 'message' => 'An error has occurred'],
            self::ERROR_USER_NOT_EXIST          => ['code' => $this->value, 'message' => 'User does not exist or has been deactivated'],
            self::ERROR_USER_BLOCKED            => ['code' => $this->value, 'message' => 'The user has been blocked for security'],
            self::ERROR_OTP_INCORRECT           => ['code' => $this->value, 'message' => 'The OTP number is expired or incorrect.'],
            self::ERROR_LOGIN                   => ['code' => $this->value, 'message' => 'Login error'],
            self::ERROR_NOT_FOUN_DATA           => ['code' => $this->value, 'message' => 'Not data found'],
            self::SUCCESS_UPDATE                => ['code' => $this->value, 'message' => 'Successfully updated'],
            self::SUCCESS_CREATE                => ['code' => $this->value, 'message' => 'Successfully created'],
            self::SUCCESS_DELETED               => ['code' => $this->value, 'message' => 'Successfully deleted'],
            self::RECORD_ACTIVATE               => ['code' => $this->value, 'message' => 'Record activate'],
            self::RECORD_DISABLED               => ['code' => $this->value, 'message' => 'Record disabled'],
        };
    }
}
