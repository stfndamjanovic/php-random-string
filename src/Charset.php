<?php

namespace Stfn\RandomHash;

enum Charset: string
{
    case UPPERCASE = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    case LOWERCASE = "abcdefghijklmnopqrstuvwxyz";
    case NUMERIC = "0123456789";
}
