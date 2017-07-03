<?php
namespace Application\View\Helper;

use Application\Entity\Widget\Widget;
use Zend\View\Helper\AbstractHelper;

class Shortcode extends AbstractHelper
{
    private $shortcode_tags = [
        'gallery' => 'galleryWidget'
    ];

    private $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * Whether a registered shortcode exists named $tag
     *
     *
     * @param string $tag Shortcode tag to check.
     * @return bool Whether the given shortcode exists.
     */
    public function shortcodeExists($tag)
    {
        return array_key_exists($tag, $this->shortcode_tags);
    }

    public function getShortcodeRegex($tagnames = null)
    {
        if ( empty( $tagnames ) ) {
            $tagnames = array_keys( $this->shortcode_tags );
        }
        $tagregexp = join( '|', array_map('preg_quote', $tagnames) );

        // WARNING! Do not change this regex without changing do_shortcode_tag() and strip_shortcode_tag()
	    // Also, see shortcode_unautop() and shortcode.js.
        return
            '\\['                              // Opening bracket
            . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
            . "($tagregexp)"                     // 2: Shortcode name
            . '(?![\\w-])'                       // Not followed by word character or hyphen
            . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
            .     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
            .     '(?:'
            .         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
            .         '[^\\]\\/]*'               // Not a closing bracket or forward slash
            .     ')*?'
            . ')'
            . '(?:'
            .     '(\\/)'                        // 4: Self closing tag ...
            .     '\\]'                          // ... and closing bracket
            . '|'
            .     '\\]'                          // Closing bracket
            .     '(?:'
            .         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
            .             '[^\\[]*+'             // Not an opening bracket
            .             '(?:'
            .                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
            .                 '[^\\[]*+'         // Not an opening bracket
            .             ')*+'
            .         ')'
            .         '\\[\\/\\2\\]'             // Closing shortcode tag
            .     ')?'
            . ')'
            . '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
    }

    public function __invoke($content, $ignore_html = false)
    {
        if ( false === strpos( $content, '[' ) ) {
            return $content;
        }

        if (empty($this->shortcode_tags) || !is_array($this->shortcode_tags))
            return $content;

        // Find all registered tag names in $content.
        preg_match_all( '@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches );
        $tagnames = array_intersect( array_keys( $this->shortcode_tags ), $matches[1] );

        if ( empty( $tagnames ) ) {
            return $content;
        }

        //$content = $this->doShortcodesInHtmlTags($content, $ignore_html, $tagnames);

        $pattern = $this->getShortcodeRegex( $tagnames );
        $content = preg_replace_callback( "/$pattern/", [$this, 'doShortcodeTag'], $content );

        return $content;
    }

    /**
     * Search only inside HTML elements for shortcodes and process them.
     *
     * Any [ or ] characters remaining inside elements will be HTML encoded
     * to prevent interference with shortcodes that are outside the elements.
     * Assumes $content processed by KSES already.  Users with unfiltered_html
     * capability may get unexpected output if angle braces are nested in tags.
     *
     * @since 4.2.3
     *
     * @param string $content Content to search for shortcodes
     * @param bool $ignore_html When true, all square braces inside elements will be encoded.
     * @param array $tagnames List of shortcodes to find.
     * @return string Content with shortcodes filtered out.
     */
    public function doShortcodesInHtmlTags($content, $ignore_html, $tagnames)
    {
        $trans = array( '&#91;' => '&#091;', '&#93;' => '&#093;' );
        $content = strtr( $content, $trans );
        $trans = array( '[' => '&#91;', ']' => '&#93;' );

        $pattern = $this->getShortcodeRegex($tagnames);
        $textarr = $this->wp_html_split($content);

        foreach ( $textarr as &$element ) {
            if ( '' == $element || '<' !== $element[0] ) {
                continue;
            }

            $noopen = false === strpos( $element, '[' );
            $noclose = false === strpos( $element, ']' );
            if ( $noopen || $noclose ) {
                // This element does not contain shortcodes.
                if ( $noopen xor $noclose ) {
                    // Need to encode stray [ or ] chars.
                    $element = strtr( $element, $trans );
                }
                continue;
            }

            if ( $ignore_html || '<!--' === substr( $element, 0, 4 ) || '<![CDATA[' === substr( $element, 0, 9 ) ) {
                // Encode all [ and ] chars.
                $element = strtr( $element, $trans );
                continue;
            }

            $attributes = $this->wp_kses_attr_parse( $element );
            if ( false === $attributes ) {
                // Some plugins are doing things like [name] <[email]>.
                if ( 1 === preg_match( '%^<\s*\[\[?[^\[\]]+\]%', $element ) ) {
                    $element = preg_replace_callback( "/$pattern/", 'do_shortcode_tag', $element );
                }

                // Looks like we found some crazy unfiltered HTML.  Skipping it for sanity.
                $element = strtr( $element, $trans );
                continue;
            }

            // Get element name
            $front = array_shift( $attributes );
            $back = array_pop( $attributes );
            $matches = array();
            preg_match('%[a-zA-Z0-9]+%', $front, $matches);
            $elname = $matches[0];

            // Look for shortcodes in each attribute separately.
            foreach ( $attributes as &$attr ) {
                $open = strpos( $attr, '[' );
                $close = strpos( $attr, ']' );
                if ( false === $open || false === $close ) {
                    continue; // Go to next attribute.  Square braces will be escaped at end of loop.
                }
                $double = strpos( $attr, '"' );
                $single = strpos( $attr, "'" );
                if ( ( false === $single || $open < $single ) && ( false === $double || $open < $double ) ) {
                    // $attr like '[shortcode]' or 'name = [shortcode]' implies unfiltered_html.
                    // In this specific situation we assume KSES did not run because the input
                    // was written by an administrator, so we should avoid changing the output
                    // and we do not need to run KSES here.
                    $attr = preg_replace_callback( "/$pattern/", 'do_shortcode_tag', $attr );
                } else {
                    // $attr like 'name = "[shortcode]"' or "name = '[shortcode]'"
                    // We do not know if $content was unfiltered. Assume KSES ran before shortcodes.
                    $count = 0;
                    $new_attr = preg_replace_callback( "/$pattern/", 'do_shortcode_tag', $attr, -1, $count );
                    if ( $count > 0 ) {
                        // Sanitize the shortcode output using KSES.
                        $new_attr = wp_kses_one_attr( $new_attr, $elname );
                        if ( '' !== trim( $new_attr ) ) {
                            // The shortcode is safe to use now.
                            $attr = $new_attr;
                        }
                    }
                }
            }
            $element = $front . implode( '', $attributes ) . $back;

            // Now encode any remaining [ or ] chars.
            $element = strtr( $element, $trans );
        }

        $content = implode( '', $textarr );

        return $content;
    }

    /**
     * Regular Expression callable for do_shortcode() for calling shortcode hook.
     * @see get_shortcode_regex for details of the match array contents.
     *
     * @since 2.5.0
     * @access private
     *
     * @param array $m Regular expression match array
     * @return string|false False on failure.
     */
    public function doShortcodeTag($m)
    {
        $shortcode_tags = $this->shortcode_tags;

        // allow [[foo]] syntax for escaping a tag
        if ( $m[1] == '[' && $m[6] == ']' ) {
            return substr($m[0], 1, -1);
        }

        $tag = $m[2];
        $attr = $this->shortcodeParseAtts( $m[3] );

        if ( ! is_callable( $shortcode_tags[ $tag ] ) ) {
            /* translators: %s: shortcode tag */
            //$message = sprintf( __( 'Attempting to parse a shortcode without a valid callback: %s' ), $tag );
            //_doing_it_wrong( __FUNCTION__, $message, '4.3.0' );
            //return $m[0];
        }

        if ( isset( $m[5] ) ) {
            // enclosing tag - extra parameter
            return $m[1] . call_user_func( [$this, $shortcode_tags[$tag]], $attr, $m[5], $tag ) . $m[6];
        } else {
            // self-closing tag
            return $m[1] . call_user_func( [$this, $shortcode_tags[$tag]], $attr, null,  $tag ) . $m[6];
        }

    }

    /***************************************************************************************************************************************
     *
     * funcoes auxiliares
     *
     ***************************************************************************************************************************************/

    /**
     * Separate HTML elements and comments from the text.
     *
     * @since 4.2.4
     *
     * @param string $input The text which has to be formatted.
     * @return array The formatted text.
     */
    public function wp_html_split( $input ) {
        return preg_split( $this->get_html_split_regex(), $input, -1, \PREG_SPLIT_DELIM_CAPTURE );
    }

    /**
     * Retrieve the regular expression for an HTML element.
     *
     * @since 4.4.0
     *
     * @return string The regular expression
     */
    public function get_html_split_regex() {
        static $regex;

        if ( ! isset( $regex ) ) {
            $comments =
                '!'           // Start of comment, after the <.
                . '(?:'         // Unroll the loop: Consume everything until --> is found.
                .     '-(?!->)' // Dash not followed by end of comment.
                .     '[^\-]*+' // Consume non-dashes.
                . ')*+'         // Loop possessively.
                . '(?:-->)?';   // End of comment. If not found, match all input.

            $cdata =
                '!\[CDATA\['  // Start of comment, after the <.
                . '[^\]]*+'     // Consume non-].
                . '(?:'         // Unroll the loop: Consume everything until ]]> is found.
                .     '](?!]>)' // One ] not followed by end of comment.
                .     '[^\]]*+' // Consume non-].
                . ')*+'         // Loop possessively.
                . '(?:]]>)?';   // End of comment. If not found, match all input.

            $escaped =
                '(?='           // Is the element escaped?
                .    '!--'
                . '|'
                .    '!\[CDATA\['
                . ')'
                . '(?(?=!-)'      // If yes, which type?
                .     $comments
                . '|'
                .     $cdata
                . ')';

            $regex =
                '/('              // Capture the entire match.
                .     '<'           // Find start of element.
                .     '(?'          // Conditional expression follows.
                .         $escaped  // Find end of escaped element.
                .     '|'           // ... else ...
                .         '[^>]*>?' // Find end of normal element.
                .     ')'
                . ')/';
        }

        return $regex;
    }

    public function wp_kses_attr_parse( $element ) {
        $valid = preg_match('%^(<\s*)(/\s*)?([a-zA-Z0-9]+\s*)([^>]*)(>?)$%', $element, $matches);
        if ( 1 !== $valid ) {
            return false;
        }

        $begin =  $matches[1];
        $slash =  $matches[2];
        $elname = $matches[3];
        $attr =   $matches[4];
        $end =    $matches[5];

        if ( '' !== $slash ) {
            // Closing elements do not get parsed.
            return false;
        }

        // Is there a closing XHTML slash at the end of the attributes?
        if ( 1 === preg_match( '%\s*/\s*$%', $attr, $matches ) ) {
            $xhtml_slash = $matches[0];
            $attr = substr( $attr, 0, -strlen( $xhtml_slash ) );
        } else {
            $xhtml_slash = '';
        }

        // Split it
        $attrarr = $this->wp_kses_hair_parse( $attr );
        if ( false === $attrarr ) {
            return false;
        }

        // Make sure all input is returned by adding front and back matter.
        array_unshift( $attrarr, $begin . $slash . $elname );
        array_push( $attrarr, $xhtml_slash . $end );

        return $attrarr;
    }

    public function wp_kses_hair_parse( $attr ) {
        if ( '' === $attr ) {
            return array();
        }

        $regex =
            '(?:'
            .     '[-a-zA-Z:]+'   // Attribute name.
            . '|'
            .     '\[\[?[^\[\]]+\]\]?' // Shortcode in the name position implies unfiltered_html.
            . ')'
            . '(?:'               // Attribute value.
            .     '\s*=\s*'       // All values begin with '='
            .     '(?:'
            .         '"[^"]*"'   // Double-quoted
            .     '|'
            .         "'[^']*'"   // Single-quoted
            .     '|'
            .         '[^\s"\']+' // Non-quoted
            .         '(?:\s|$)'  // Must have a space
            .     ')'
            . '|'
            .     '(?:\s|$)'      // If attribute has no value, space is required.
            . ')'
            . '\s*';              // Trailing space is optional except as mentioned above.

        // Although it is possible to reduce this procedure to a single regexp,
        // we must run that regexp twice to get exactly the expected result.

        $validation = "%^($regex)+$%";
        $extraction = "%$regex%";

        if ( 1 === preg_match( $validation, $attr ) ) {
            preg_match_all( $extraction, $attr, $attrarr );
            return $attrarr[0];
        } else {
            return false;
        }
    }

    /**
     * Retrieve all attributes from the shortcodes tag.
     *
     * The attributes list has the attribute name as the key and the value of the
     * attribute as the value in the key/value pair. This allows for easier
     * retrieval of the attributes, since all attributes have to be known.
     *
     * @param string $text
     * @return array|string List of attribute values.
     *                      Returns empty array if trim( $text ) == '""'.
     *                      Returns empty string if trim( $text ) == ''.
     *                      All other matches are checked for not empty().
     */
    public function shortcodeParseAtts($text)
    {
        $atts = array();
        $pattern = $this->getShortcodeAttsRegex();
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
        if ( preg_match_all($pattern, $text, $match, PREG_SET_ORDER) ) {
            foreach ($match as $m) {
                if (!empty($m[1]))
                    $atts[strtolower($m[1])] = stripcslashes($m[2]);
                elseif (!empty($m[3]))
                    $atts[strtolower($m[3])] = stripcslashes($m[4]);
                elseif (!empty($m[5]))
                    $atts[strtolower($m[5])] = stripcslashes($m[6]);
                elseif (isset($m[7]) && strlen($m[7]))
                    $atts[] = stripcslashes($m[7]);
                elseif (isset($m[8]))
                    $atts[] = stripcslashes($m[8]);
            }

            // Reject any unclosed HTML elements
            foreach( $atts as &$value ) {
                if ( false !== strpos( $value, '<' ) ) {
                    if ( 1 !== preg_match( '/^[^<]*+(?:<[^>]*+>[^<]*+)*+$/', $value ) ) {
                        $value = '';
                    }
                }
            }
        } else {
            $atts = ltrim($text);
        }
        return $atts;
    }

    /**
     * Retrieve the shortcode attributes regex.
     *
     * @since 4.4.0
     *
     * @return string The shortcode attribute regular expression
     */
    public function getShortcodeAttsRegex()
    {
        return '/([\w-]+)\s*=\s*"([^"]*)"(?:\s|$)|([\w-]+)\s*=\s*\'([^\']*)\'(?:\s|$)|([\w-]+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
    }

    /**
     * @return mixed
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param mixed $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }

    public function galleryWidget($attr=[])
    {
        $gallery = $this->getEm()->getRepository(Widget::class)->find($attr['id']);
        if(!$gallery) {
            return "";
        }

        $thumbor = $this->getView()->plugin('thumbor');

        $value = json_decode($gallery->getValue());
        $return = "";
        foreach ($value as $v) {
            $return.= '<div class="project-item">
                  <img src="'.$thumbor()->url($v->image)->resize(640, 427).'" alt="">
                  <div class="project-desc">
                    <p>'.$v->title.'</p>
                  </div></div>';
        }


        return '<div class="project project--caroucel">
            <div class="project-wrapper"><div class="project-list owl-carousel">'.$return.'</div></div>
            </div>';
    }
}