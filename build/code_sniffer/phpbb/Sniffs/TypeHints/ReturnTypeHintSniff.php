<?php
/**
 *
 * This file is part of the phpBB Forum Software package.
 *
 * @copyright (c) phpBB Limited <https://www.phpbb.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * For full copyright and license information, please see
 * the docs/CREDITS.txt file.
 *
 */

namespace PHP_CodeSniffer\Standards\phpbb\Sniffs\TypeHints;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Checks that there is exactly one space between the keyword and the opening
 * parenthesis of a control structures.
 */
class ReturnTypeHintSniff implements Sniff
{
	/**
	 * Registers the tokens that this sniff wants to listen for.
	 */
	public function register()
	{
		return [
			T_FUNCTION,
			T_CLOSURE,
			T_FN,
		];
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param int                  $stackPtr  The position of the current token in the
	 *                                        stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process(File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		if (!isset($tokens[$stackPtr]['scope_opener']))
		{
			$colon_token = $phpcsFile->findNext(T_COLON, $tokens[$stackPtr]['parenthesis_closer'] + 1);
		}
		else
		{
			$colon_token = $phpcsFile->findNext(T_COLON, $tokens[$stackPtr]['parenthesis_closer'] + 1, $tokens[$stackPtr]['scope_opener'] - 1);
		}

		if (!$colon_token)
		{
			return;
		}

		if ($colon_token != $tokens[$stackPtr]['parenthesis_closer'] + 2)
		{
			$error = 'There should be exactly one space between the closing parenthesis and return type hint colon';
			$phpcsFile->addError($error, $stackPtr, 'NoSpaceBeforeReturnTypeHintColon');
		}
		else if ($tokens[$colon_token - 1]['content'] !== ' ')
		{
			$error = 'There should be exactly one space between the closing parenthesis and return type hint colon';
			$phpcsFile->addError($error, $stackPtr, 'IncorrectSpaceBeforeReturnTypeHintColon');
		}
		else if ($tokens[$colon_token + 1]['content'] !== ' ')
		{
			$error = 'There should be exactly one space between return type hint colon and type specifier';
			$phpcsFile->addError($error, $stackPtr, 'NoSpaceAfterReturnTypeHintColon');
		}
		else if ($tokens[$colon_token + 2]['content'] === ' ')
		{
			$error = 'There should be exactly one space between return type hint colon and type specifier';
			$phpcsFile->addError($error, $stackPtr, 'IncorrectSpaceAfterReturnTypeHintColon');
		}
	}
}
