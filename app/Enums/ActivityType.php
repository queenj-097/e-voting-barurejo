<?php

namespace App\Enums;

class ActivityType
{
    public const LOGIN = 'Login';
    public const LOGOUT = 'Logout';
    public const VOTING = 'Voting';
    public const VERIFICATION = 'Verifikasi';
    public const SCAN = 'Scan QR';
    public const BOOTH = 'Status Bilik';
    public const RESET = 'Reset Pemilu';
    public const SETTINGS = 'Pengaturan';
}