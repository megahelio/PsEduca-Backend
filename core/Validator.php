<?php

class Validator {
    public function validate($object): array
    {
        $reflection = new ReflectionClass($object);
        $errors = [];

        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($object);
            $docComment = $property->getDocComment();

            if (str_contains($docComment, '@NotNull') && empty($value)) {

                $errors[$property->getName()] = 'This field is required.';

            } else if (preg_match('/@Regex\s*\((.*?)\)/s', $docComment, $matches)) {

                preg_match('/pattern="([^"]+)"/', $matches[0], $pattern);
                preg_match('/message="([^"]+)"/', $matches[0], $message);

                if (!preg_match($pattern[1], $value)) {

                    $errors[$property->getName()] = $message[1] ?? 'Invalid format.';
                }
            }
        }

        return $errors;
    }
}