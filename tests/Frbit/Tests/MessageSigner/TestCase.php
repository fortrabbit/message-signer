<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner;

/**
 * Class TestCase
 * @package Frbit\Tests\MessageSigner
 **/
class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }


    protected function getPrivateKeyPem()
    {
        $key = <<<KEY
-----BEGIN RSA PRIVATE KEY-----
MIICWwIBAAKBgQC7xi6nt14nKkN3OKGp4A3wtIaR65VsRL7XfaAKKnbytXIj5zMi
vQ+kM8Fg00VB2kIjDPWi9x4XjBigYiGZqPYPlAV/D9G4+0K3ciyNugZWG8K3r1Bd
uQzppKqlI9UEZH+20di64HyqWExhmAjMCnFXYDjwBhJTOiBGinuAIprXuQIDAQAB
AoGAX7GNH030vrLsNPr4cHFNyWjG1+CegtG6DGnqQKQJgIF1zNRLnB2LthvmJikl
N306jVBScp3LiSaNelboVr3jv4YY1177mgDljIrL5P98dre6uHK5ELRajEcPy66V
7G/FCsrYpnvyKLZPkqO2zJQ5MRquiqEkMjRrV3G9Y68xUzECQQDyBln1l3uPJxs4
FzlIF/A/c7GoeWjpuuhDc5Zrf3kQjM/gbFGJlfzRlD868DK+0++dyAIY4+lb5iMD
AazYZKR9AkEAxp3jm3NEUxXbtHcmoQdMsfrQymPtBONtPEzjnBeO2ytZjKwyPVVu
O8rtz5dMH/7khadNQ9OguJaYBwNwDj3Q7QJABtIlAOXnBo7MlpUY+S4riC5DiIL7
cPLijtwYFil+iPfe9+01kxIWEPYDni6cz4H7k8/KN1ddAUkIHZKTg3m6pQJAG8v1
EshrA4XHndYfHeI7pZ0Io4qgnVKG8y1/nVTetWW3vqxKy7KtHskjxy3RhZjSurHT
am6vy6Wn2TNt91BHcQJAdDnHNUERZvdVCv1fsm45Tq6gySjYgb3e/yDTtyTpgoFe
njtn0mmlayczkuhXgq2rd6/VtMdMDjcSv28Ub2C+Rw==
-----END RSA PRIVATE KEY-----
KEY;

        return $key;
    }

    protected function getPublicKeyPem()
    {
        $key = <<<KEY
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC7xi6nt14nKkN3OKGp4A3wtIaR
65VsRL7XfaAKKnbytXIj5zMivQ+kM8Fg00VB2kIjDPWi9x4XjBigYiGZqPYPlAV/
D9G4+0K3ciyNugZWG8K3r1BduQzppKqlI9UEZH+20di64HyqWExhmAjMCnFXYDjw
BhJTOiBGinuAIprXuQIDAQAB
-----END PUBLIC KEY-----
KEY;

        return $key;
    }

    public function getOtherPrivateKeyPem()
    {
        $key = <<<KEY
-----BEGIN RSA PRIVATE KEY-----
MIICXgIBAAKBgQC/Eg7TQGUCErrPKAxWiUcSY25s6SmK9ciUrdKKKr++PpDguebJ
WJ7pWWfkd6o9Df4giY/J1NrHsuS+qR5pJ9T+DsNOSEp8kDWk3nO8grpHRfsBpie+
YKiYA+ari90tL12s/+P5TVg6VhhhcPZqSc/SwFVxOydV0IEHCSzWTUADOwIDAQAB
AoGBAIlKvXJYG/xAXxEJMsxfeb0hyy/g3iPmdck3mUgEbaCSrmF70vQshIsh3gAR
aDbUvhy9G5+oHchOqATD2oEuyODA1rLjvB3wn/xtjFbUM7PBNtR0+5oCv7pnCcmN
ki5DDsAat8cADjwAkIvnI7rZ60OiamdOHWSmb8SGEFKqfrQ5AkEA+HeFlirIufaA
Al7mlLV88prx9AaXKM42KCh7Aaf0sie0jymfNuk2neB1teoftl2NFmrQwcOyFMTu
jin6dRratwJBAMTdDcW0jhxJwsDtrRK6H4uZeyqtwz+bSxp11bxWWau+zmeKkCWd
rIA/uYtq1AzzvVhiB+hS2obMpNXbk+E6J50CQQDW5TjqujJFdIcehcnUEGvywERH
lHOkXUXF0c0hj4w5kdG+iHcl3OrZ/UqRfd7TsXHXU6ceDw20nObemjv+kK1dAkA4
wHUw8p0pQIZZces8A9YGO4fLRO6Njqo3BZNJSSduoIIGTWbCwVKyYiyOKYzZ8wY5
zYD0E7aVt7cpQ7S88DPtAkEAtGXnJV5JVCvZu9WjpFaDLtN5rLH0F3TjHDOlEvAR
m7LcxLzgdBmXvZ0lXOfB6AcEjQfEqp8+7HW/fgSxQr/8sQ==
-----END RSA PRIVATE KEY-----
KEY;

        return $key;
    }

    public function getOtherPublicKeyPem()
    {
        $key = <<<KEY
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC/Eg7TQGUCErrPKAxWiUcSY25s
6SmK9ciUrdKKKr++PpDguebJWJ7pWWfkd6o9Df4giY/J1NrHsuS+qR5pJ9T+DsNO
SEp8kDWk3nO8grpHRfsBpie+YKiYA+ari90tL12s/+P5TVg6VhhhcPZqSc/SwFVx
OydV0IEHCSzWTUADOwIDAQAB
-----END PUBLIC KEY-----
KEY;

        return $key;
    }


    protected function getPrivateKeySsh()
    {
        return 'AAAAB3NzaC1yc2EAAAADAQABAAAAgQC7xi6nt14nKkN3OKGp4A3wtIaR65VsRL7XfaAKKnbytXIj5zMivQ+kM8Fg00VB2kIjDPWi9x4XjBigYiGZqPYPlAV/D9G4+0K3ciyNugZWG8K3r1BduQzppKqlI9UEZH+20di64HyqWExhmAjMCnFXYDjwBhJTOiBGinuAIprXuQ==';
    }

    protected function getPublicKeySsh()
    {
        return 'AAAAB3NzaC1yc2EAAAADAQABAAAAgQC7xi6nt14nKkN3OKGp4A3wtIaR65VsRL7XfaAKKnbytXIj5zMivQ+kM8Fg00VB2kIjDPWi9x4XjBigYiGZqPYPlAV/D9G4+0K3ciyNugZWG8K3r1BduQzppKqlI9UEZH+20di64HyqWExhmAjMCnFXYDjwBhJTOiBGinuAIprXuQ==';
    }

}