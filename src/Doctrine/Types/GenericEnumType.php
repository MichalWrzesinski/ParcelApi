<?php

declare(strict_types=1);

namespace App\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use UnitEnum;
use InvalidArgumentException;

class GenericEnumType extends Type
{
    public const GENERIC_ENUM = 'generic_enum';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'VARCHAR(30)';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?UnitEnum
    {
        if ($value === null) {
            return null;
        }

        $enumClass = $fieldDeclaration['enumType'] ?? throw new InvalidArgumentException('Enum class not specified.');

        if (!class_exists($enumClass)) {
            throw new InvalidArgumentException(sprintf('Enum class %s not found.', $enumClass));
        }

        return $enumClass::from($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value?->value ?? null;
    }

    public function getName(): string
    {
        return self::GENERIC_ENUM;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return false;
    }
}
