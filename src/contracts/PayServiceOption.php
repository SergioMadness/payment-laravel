<?php namespace professionalweb\payment\contracts;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Interface for pay service option
 * @package professionalweb\payment\contracts
 */
interface PayServiceOption extends Arrayable
{
    public const TYPE_STRING = 'string';

    public const TYPE_TEXT = 'text';

    public const TYPE_FILE = 'file';

    public const TYPE_BOOL = 'bool';

    /**
     * Option alias
     *
     * @return string
     */
    public function getAlias(): string;

    /**
     * Readable label
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Get field type
     *
     * @return string
     */
    public function getType(): string;
}