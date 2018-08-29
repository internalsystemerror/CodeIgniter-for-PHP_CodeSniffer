<?php
/**
 * CodeIgniter_Sniffs_Whitespace_ScopeClosingBraceSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @author    Michał Śniatała <m.sniatala@gmail.com>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * CodeIgniter_Sniffs_Whitespace_ScopeClosingBraceSniff.
 *
 * Checks that the closing braces of scopes are aligned correctly.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @author    Michał Śniatała <m.sniatala@gmail.com>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

namespace CodeIgniter\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

class ScopeClosingBraceSniff implements Sniff
{

    /**
     * The number of tabs code should be indented.
     *
     * @var int
     */
    public $indent = 1;


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return Tokens::$scopeOpeners;

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File $phpcsFile All the tokens found in the document.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // If this is an inline condition (ie. there is no scope opener), then
        // return, as this is not a new scope.
        if (isset($tokens[$stackPtr]['scope_closer']) === false) {
            return;
        }

        $scopeStart = $tokens[$stackPtr]['scope_opener'];
        $scopeEnd   = $tokens[$stackPtr]['scope_closer'];

        // If the scope closer doesn't think it belongs to this scope opener
        // then the opener is sharing its closer with other tokens. We only
        // want to process the closer once, so skip this one.
        if (isset($tokens[$scopeEnd]['scope_condition']) === false
            || $tokens[$scopeEnd]['scope_condition'] !== $stackPtr
        ) {
            return;
        }

        // We need to actually find the first piece of content on this line,
        // because if this is a method with tokens before it (public, static etc)
        // or an if with an else before it, then we need to start the scope
        // checking from there, rather than the current token.
        $lineStart = ($stackPtr - 1);
        for ($lineStart; $lineStart > 0; $lineStart--) {
            if (strpos($tokens[$lineStart]['content'], $phpcsFile->eolChar) !== false) {
                break;
            }
        }

        $lineStart++;

        $startColumn = 1;
        if ($tokens[$lineStart]['code'] === T_WHITESPACE) {
            $startColumn = $tokens[($lineStart + 1)]['column'];
        } else if ($tokens[$lineStart]['code'] === T_INLINE_HTML) {
            $trimmed = ltrim($tokens[$lineStart]['content']);
            if ($trimmed === '') {
                $startColumn = $tokens[($lineStart + 1)]['column'];
            } else {
                $startColumn = (strlen($tokens[$lineStart]['content']) - strlen($trimmed));
            }
        }

        // Check that the closing brace is on it's own line.
        $lastContent = $phpcsFile->findPrevious(
            array(
             T_WHITESPACE,
             T_INLINE_HTML,
             T_OPEN_TAG,
            ),
            ($scopeEnd - 1),
            $scopeStart,
            true
        );

        if ($tokens[$lastContent]['line'] === $tokens[$scopeEnd]['line']) {
            $error = 'Closing brace must be on a line by itself';
            $phpcsFile->addError($error, $scopeEnd, 'Line');
            
            return;
        }

        // Check now that the closing brace is lined up correctly.
        $lineStart = ($scopeEnd - 1);
        for ($lineStart; $lineStart > 0; $lineStart--) {
            if (strpos($tokens[$lineStart]['content'], $phpcsFile->eolChar) !== false) {
                break;
            }
        }

        $lineStart++;

        $braceIndent = 0;
        if ($tokens[$lineStart]['code'] === T_WHITESPACE) {
            $braceIndent = ($tokens[($lineStart + 1)]['column'] - 1);
        } else if ($tokens[$lineStart]['code'] === T_INLINE_HTML) {
            $trimmed = ltrim($tokens[$lineStart]['content']);
            if ($trimmed === '') {
                $braceIndent = ($tokens[($lineStart + 1)]['column'] - 1);
            } else {
                $braceIndent = (strlen($tokens[$lineStart]['content']) - strlen($trimmed) - 1);
            }
        }

        if ($tokens[$stackPtr]['code'] === T_CASE
            || $tokens[$stackPtr]['code'] === T_DEFAULT
        ) {
            // BREAK statements should be indented n tabs from the
            // CASE or DEFAULT statement.
            $expectedIndent = ($startColumn + $this->indent - 1);
            if ($braceIndent !== $expectedIndent) {
                $error = 'Case breaking statement indented incorrectly; expected %s tabs, found %s';
                $data  = array(
                          $expectedIndent,
                          $braceIndent,
                         );
                $phpcsFile->addError($error, $scopeEnd, 'BreakIndent', $data);
            }
        } else {
            $expectedIndent = ($startColumn - 1);
            if ($braceIndent !== $expectedIndent) {
                $error = 'Closing brace indented incorrectly; expected %s tabs, found %s';
                $data  = array(
                          $expectedIndent,
                          $braceIndent,
                         );
                $phpcsFile->addError($error, $scopeEnd, 'Indent', $data);
            }
        }//end if


    }//end process()


}//end class