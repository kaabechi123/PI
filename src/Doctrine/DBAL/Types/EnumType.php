<?php
namespace App\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use App\Enum\TypeMatiereEnum;

class EnumType extends Type
{
    const ENUM = 'enum'; // Type custom

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return TypeMatiereEnum::from($value); // Convert from database to Enum value
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return "ENUM('plastique', 'verre', 'bois', 'textile')"; // Define the Enum values in SQL
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return $value->value; // Convert from Enum to database value
    }

    public function getName(): string
    {
        return self::ENUM;
    }
}
