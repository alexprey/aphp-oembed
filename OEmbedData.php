<?php
/**
 * @author Alexey Prey Mulyukin
 * Date: 09.09.2015
 * Time: 0:22
 */

/**
 * Enum class for representing a type for oEmbed content.
 */
final class OEmbedDataType {
    /**
     * This type is used for representing static photos.
     */
    const Photo = 'photo';

    /**
     * This type is used for representing playable videos.
     */
    const Video = 'video';

    /**
     * Responses of this type allow a provider to return any generic embed data.
     */
    const Link = 'link';

    /**
     * This type is used for rich HTML content that does not fall under one of the other categories.
     */
    const Rich = 'rich';
}

/**
 * Represents a base embed data received from media content provider.
 */
class OEmbedData {
    /**
     * The resource type.
     * @see OEmbedDataType
     * @var string
     */
    public $type;

    /**
     * The oEmbed version number. This must be 1.0
     * @var string
     */
    public $version;

    /**
     * The text title describing the resource.
     * @var string
     */
    public $title;

    /**
     * The name of the author/owner of the resource.
     * @var string
     */
    public $author_name;

    /**
     * A URL for the author/owner of the resource.
     * @var string
     */
    public $author_url;

    /**
     * The name of the resource provider.
     * @var string
     */
    public $provider_name;

    /**
     * The url of the resource provider.
     * @var string
     */
    public $provider_url;

    /**
     * The suggested cache lifetime for this resource, in seconds. Consumers may choose to use this value or not.
     * @var int
     */
    public $cache_age;

    /**
     * A URL to a thumbnail image representing the resource.
     * The thumbnail must respect any $maxwidth and $maxheight parameters. If this parameter is present, @see thumbnail_width and @see thumbnail_height must also be present.
     * @var string
     */
    public $thumbnail_url;

    /**
     * The width of the optional thumbnail. If this parameter is present, @see thumbnail_url and @see thumbnail_height must also be present.
     * @var int
     */
    public $thumbnail_width;

    /**
     * The height of the optional thumbnail. If this parameter is present, @see thumbnail_url and @see thumbnail_width must also be present.
     * @var int
     */
    public $thumbnail_height;

    /**
     * The source URL of the resource.
     * For @see OEmbedDataType::Photo
     *      The source URL of the image. Consumers should be able to insert this URL into an <img> element. Only HTTP and HTTPS URLs are valid.
     * @var string
     */
    public $url;

    /**
     * The width in pixels required to display the HTML or Image content.
     * @var int
     */
    public $width;

    /**
     * The height in pixels required to display the HTML or Image content.
     * @var int
     */
    public $height;

    /**
     * The HTML required to display the resource. The HTML should have no padding or margins. Consumers may wish to load the HTML in an off-domain iframe to avoid XSS vulnerabilities. The markup should be valid XHTML 1.0 Basic.
     * @var string
     */
    public $html;
}