<?php
/**
 * @author Alexey Prey Mulyukin
 * Date: 09.09.2015
 * Time: 0:25
 */

/**
 * Class OEmbedProviderFactory
 *
 * Provider configuration Example:
 * {
 *      'name': string,
 *      'endpoint': endpoint,
 *      'schemas': [
 *          url-schema,
 *          url-schema
 *      ]
 * }
 */
class OEmbedProviderFactory {
    private static $instance;

    public static function &GetInst() {
        return self::$instance;
    }

    private $schemaRegexStorage = array();
    private $providerStorage = array();

    private $providerConfiguration;

    public function __construct($providerConfiguration) {
        if (self::$instance != null) {
            throw new ErrorException("OEmbedProviderFactory already exists in this application context!");
        }

        $this->providerConfiguration = $providerConfiguration;

        self::$instance = &$this;
    }

    /**
     * Compile a schema pattern to php regex expression.
     * @param $schema
     * @return string
     */
    private function GetSchemaRegex($schema) {
        if (is_null($this->schemaRegexStorage[$schema])) {

            $regex = preg_replace(
                array("/\*/", "/\//", "/\.\*\./"),
                array(".*", "\/", ".*"),
                $schema
            );

            $regex = "/" . $regex . "/";
            $this->schemaRegexStorage[$schema] = $regex;
        }

        return $this->schemaRegexStorage[$schema];
    }

    /**
     * Match url to this schema pattern.
     * @param $schema
     * @param $url
     * @return int
     */
    private function MatchSchema($schema, $url) {
        $regex = self::GetSchemaRegex($schema);
        return preg_match($regex, $url);
    }

    /**
     * Match url to provider schema patterns.
     * @param $provider - The provider configuration.
     * @param $url
     * @return bool
     */
    private function MatchProviderSchemas($provider, $url) {
        $schemas = $provider['schemas'];
        foreach ($schemas as $schema) {
            if ($this->MatchSchema($schema, $url)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Create new or find exists @see OEmbedProvider which can process this $url
     * @param $url
     * @return OEmbedProvider|null
     */
    public function GetProvider($url) {
        foreach($this->providerConfiguration as $provider) {
            if ($this->MatchProviderSchemas($provider, $url)) {
                $name = $provider['name'];
                if ($this->providerStorage[$name] == null) {
                    $this->providerStorage[$name] = new OEmbedProvider($provider);
                }
                return $this->providerStorage[$name];
            }
        }
    }

    /**
     * Return a @see OEmbedData for this $url is possible
     * @param $url
     * @return OEmbedData|null
     */
    public function GetEmbedData($url) {
        $provider = $this->GetProvider($url);
        if ($provider == null) {
            return null;
        }

        return $provider->GetEmbedData($url);
    }
}