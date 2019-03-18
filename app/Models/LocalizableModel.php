<?php

namespace App\Models;

class LocalizableModel extends BaseModel
{
    /**
     * Localized attributes.
     *
     * @var array
     */
    protected $localizable = [];

    /**
     * Whether or not to hide translated attributes.
     *
     * @var boolean
     */
    protected $hideLocaleSpecificAttributes = false;

    /**
     * Whether or not to append translatable attributes to array.
     *
     * @var boolean
     */
    protected $appendLocalizedAttributes = true;

    /**
     * Make a new translatable model.
     *
     * @param  array $attributes
     * @return void
     */
    public function __construct($attributes = [])
    {
        // Dynamically append localizable attributes to array output
        // and hide the localized attributes from array output.
        foreach ($this->localizable as $localizableAttribute) {
            if ($this->appendLocalizedAttributes) {
                $this->appends[] = $localizableAttribute;
            }

            if ($this->hideLocaleSpecificAttributes) {
                foreach (config('app.supported_locales') as $locale) {
                    $this->hidden[] = $localizableAttribute . '_' . $locale;
                }
            }
        }

        parent::__construct($attributes);
    }

    /**
     * Magic method for retrieving a missing attribute.
     *
     * @param  string $attribute
     * @return mixed
     */
    public function __get($attribute)
    {
        // Determine the current locale and return the associated locale specific attribute.
        if (in_array($attribute, $this->localizable)) {
            $localeSpecificAttribute = $attribute . '_' . app()->getLocale();
            return $this->{$localeSpecificAttribute};
        }

        return parent::__get($attribute);
    }

    /**
     * Magic method for calling a missing instance method.
     *
     * @param  string $method
     * @param  array $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        // Handle the accessor calls for all the localizable attributes.
        foreach ($this->localizable as $localizableAttribute) {
            if ($method === 'get' . studly_case($localizableAttribute) . 'Attribute') {
                return $this->{$localizableAttribute};
            }
        }

        return parent::__call($method, $arguments);
    }

    public function getLocalizableAttributes()
    {
        return $this->localizable;
    }
}
