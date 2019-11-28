<?php
/**
 * CodeIgniter_Sniffs_Files_AbstractClosingCommentSniff.
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Thomas Ernest <thomas.ernest@baobaz.com>
 * @copyright 2006 Thomas Ernest
 * @license   http://thomas.ernest.fr/developement/php_cs/licence GNU General Public License
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * CodeIgniter_Sniffs_Files_AbstractClosingCommentSniff.
 * Defines some methods used by
 * CodeIgniter_Sniffs_Files_ClosingFileCommentSniff
 * and CodeIgniter_Sniffs_Files_ClosingLocationCommentSniff.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Thomas Ernest <thomas.ernest@baobaz.com>
 * @copyright 2006 Thomas Ernest
 * @license   http://thomas.ernest.fr/developement/php_cs/licence GNU General Public License
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

namespace CodeIgniter\Sniffs\Files;

use PHP_CodeSniffer\Exceptions\TokenizerException;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class AbstractClosingCommentSniff implements Sniff
{

    /**
     * Returns the comment without its delimiter(s) as well as leading
     * and traling whitespaces.
     * It removes the first #, the two first / (i.e. //) or the first /*
     * and last \*\/. If a comment starts with /**, then the last * will remain
     * as well as whitespaces between this star and the comment content.
     *
     * @param string $comment Comment containing either comment delimiter(s) and
     *                        trailing or leading whitspaces to clean.
     *
     * @return string Comment without comment delimiter(s) and whitespaces.
     */
    protected static function _getCommentContent($comment)
    {
        if (self::_stringStartsWith($comment, '#')) {
            $comment = substr($comment, 1);
        } else {
            if (self::_stringStartsWith($comment, '//')) {
                $comment = substr($comment, 2);
            } else {
                if (self::_stringStartsWith($comment, '/*')) {
                    $comment = substr($comment, 2, strlen($comment) - 2 - 2);
                }
            }
        }
        $comment = trim($comment);

        return $comment;
    }

    /**
     * Binary safe string comparison between $needle and
     * the beginning of $haystack. Returns true if $haystack starts with
     * $needle, false otherwise.
     *
     * @param string $haystack The string to search in.
     * @param string $needle   The string to search for.
     *
     * @return bool true if $haystack starts with $needle, false otherwise.
     */
    protected static function _stringStartsWith($haystack, $needle)
    {
        $startsWith = false;
        if (strlen($needle) <= strlen($haystack)) {
            $haystackBeginning = substr($haystack, 0, strlen($needle));
            if (0 === strcmp($haystackBeginning, $needle)) {
                $startsWith = true;
            }
        }

        return $startsWith;
    }

    /**
     * As an abstract class, this sniff is not associated to any token.
     *
     * @return array
     */
    public function register()
    {
        return [];
    }//_getCommentContent()

    /**
     * As an abstract class, this sniff is not dedicated to process a token.
     *
     * @param File  $phpcsFile
     * @param mixed $stackPtr
     *
     * @return void
     * @throws TokenizerException
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $error = __CLASS__ . '::' . __METHOD__ . ' is abstract. Please develop this method in a child class.';
        throw new TokenizerException($error);
    }//_stringStartsWith()
}//end class
