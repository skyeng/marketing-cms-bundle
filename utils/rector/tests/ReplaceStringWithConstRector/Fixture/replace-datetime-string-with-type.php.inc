<?php

final class A
{
    public function supports(string $value): string
    {
        if ('DateTime' === $value) {
            return 'DateTime';
        }

        switch($value) {
            case 'DateTime': $a = 'DateTime';
        }

        return $a;
    }
}
?>
-----
<?php

final class A
{
    public function supports(string $value): string
    {
        if (\DateTime::ATOM === $value) {
            return \DateTime::ATOM;
        }

        switch($value) {
            case \DateTime::ATOM: $a = \DateTime::ATOM;
        }

        return $a;
    }
}
?>
