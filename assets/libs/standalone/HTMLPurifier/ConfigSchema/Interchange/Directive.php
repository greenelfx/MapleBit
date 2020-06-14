<?php

/**
 * Interchange component class describing configuration directives.
 */
class HTMLPurifier_ConfigSchema_Interchange_Directive
{
    /**
     * ID of directive.
     *
     * @var HTMLPurifier_ConfigSchema_Interchange_Id
     */
    public $id;

    /**
     * Type, e.g. 'integer' or 'istring'.
     *
     * @var string
     */
    public $type;

    /**
     * Default value, e.g. 3 or 'DefaultVal'.
     *
     * @var mixed
     */
    public $default;

    /**
     * HTML description.
     *
     * @var string
     */
    public $description;

    /**
     * Whether or not null is allowed as a value.
     *
     * @var bool
     */
    public $typeAllowsNull = false;

    /**
     * Lookup table of allowed scalar values.
     * e.g. array('allowed' => true).
     * Null if all values are allowed.
     *
     * @var array
     */
    public $allowed;

    /**
     * List of aliases for the directive.
     * e.g. array(new HTMLPurifier_ConfigSchema_Interchange_Id('Ns', 'Dir'))).
     *
     * @var HTMLPurifier_ConfigSchema_Interchange_Id[]
     */
    public $aliases = [];

    /**
     * Hash of value aliases, e.g. array('alt' => 'real'). Null if value
     * aliasing is disabled (necessary for non-scalar types).
     *
     * @var array
     */
    public $valueAliases;

    /**
     * Version of HTML Purifier the directive was introduced, e.g. '1.3.1'.
     * Null if the directive has always existed.
     *
     * @var string
     */
    public $version;

    /**
     * ID of directive that supercedes this old directive.
     * Null if not deprecated.
     *
     * @var HTMLPurifier_ConfigSchema_Interchange_Id
     */
    public $deprecatedUse;

    /**
     * Version of HTML Purifier this directive was deprecated. Null if not
     * deprecated.
     *
     * @var string
     */
    public $deprecatedVersion;

    /**
     * List of external projects this directive depends on, e.g. array('CSSTidy').
     *
     * @var array
     */
    public $external = [];
}

// vim: et sw=4 sts=4
