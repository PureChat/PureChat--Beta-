<?php
/**
 * PureChat (PC)
 *
 * @file ~./Includes/parse.php
 * @author The PureChat Team
 * @copyright 2012 PureChat.org <http://www.purechat.org>
 * @license GPL <http://www.gnu.org/licenses/>
 *
 * @version 0.0.9 (Alpha)
 */
/**
 * This file is part of PureChat.

 * PureChat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * PureChat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with PureChat.  If not, see <http://www.gnu.org/licenses/>.
 */

class parseObject extends PureChat
{
	public function __construct()
	{
		parent::__construct();
	}

    public function bbc($message, $user)
    {

        // The basic BBC tag searches.
        $searches = array(
            '/\[b\](.+?)\[\/b\]/',
            '/\[i\](.+?)\[\/i\]/',
            '/\[s\](.+?)\[\/s\]/',
            '/\[u\](.+?)\[\/u\]/',
            '/\[colou?r=(#[a-f\d]{3}|#[a-f\d]{6}|\w{1,20}|rgb\((\d|\d\d|1\d\d|2[0-4]\d|25[0-5]), ?(\d|\d\d|1\d\d|2[0-4]\d|25[0-5]), ?(\d|\d\d|1\d\d|2[0-4]\d|25[0-5])\))\](.+?)\[\/colou?r\]/i',
            '/\[font=([a-z]+)\](.+?)\[\/font\]/i',
            '/\[size=((\d|1\d|20+?)pt|(\d\d|1\d\d|200)%|(\d|1\d|2[0-6])px|(xx-small|x-small|small|medium|large))\](.+?)\[\/size\]/i',
            '/\[html\](.+?)\[\/html\]/i',
            // !! This regex was taken from SimpleMachines Forum 2.0.2 ./Sources/Subs.php for auto-linking URLs.
            '/(?<=[\s>\.(;\'"]|^)((?:http|https):\/\/[\w\-_%@:|]+(?:\.[\w\-_%]+)*(?::\d+)?(?:\/[\w\-_\~%\.@!,\?&;=#(){}+:\'\\\\]*)*[\/\w\-_\~%@\?;=#}\\\\])/i',
            '/^\/me(.+?)$/i',
            '/\[glow colou?r=(#[a-f\d]{3}|#[a-f\d]{6}|\w{1,20}|rgb\((\d|\d\d|1\d\d|2[0-4]\d|25[0-5]), ?(\d|\d\d|1\d\d|2[0-4]\d|25[0-5]), ?(\d|\d\d|1\d\d|2[0-4]\d|25[0-5])\))\](.+?)\[\/glow\]/i'
        );

        // The basic BBC tag replaces.
        $replaces = array(
            '<span class="bbcBold">$1</span>',
            '<span class="bbcItalic">$1</span>',
            '<span class="bbcStrike">$1</span>',
            '<span class="bbcUnderline">$1</span>',
            '<span style="color: $1;">$5</span>',
            '<span style=\'font-family: $1;\'>$2</span>',
            '<span style="font-size: $1;">$6</span>',
            '<code>$1</code>',
            '<a href="$1" target="_blank">$1</a>',
            '<span class="bbcItalic">' . $user . ' $1</span>',
            '<span style="text-shadow: 2px 2px 4px $1; filter: dropshadow(color=$1, offx=2px, offy=2px;);">$5</span>',
        );

        // Quote pattern is a little bit different, and requires some extra work.
        $quote_pattern = '/\[quote([ ]*?by=(.+?)|[ ]*?source=(.+?))?([ ]*?source=(.+?)|[ ]*?by=(.+?))?\](.+?)\[\/quote\]/i';

        // Populate Quote Pattern Matches -> Continue.
        preg_match($quote_pattern, $message, $quote_matches);
        if (isset($quote_matches))
        {
            // Get rid of empty array variables and generate two key arrays.
            $by_numbers = array();
            $source_numbers = array();
            foreach ($quote_matches as $key => $value)
            {
                if (empty($quote_matches[$key]))
                {
                    unset($quote_matches[$key]);
                    continue;
                }
            }

            // Now populate them.
            foreach ($quote_matches as $key => $value)
            {
                // Populate the "By Numbers" array with the appropriate keys (2).
                if ($key != 0 && !array_key_exists(1, $by_numbers) && stripos($value, 'by='))
                {
                    // Add the key, and create a clone variable.
                    $by_numbers[] = $key;
                    $skipky = $key;

                    // Begin the hunt for the second key!
                    while (!array_key_exists(1, $by_numbers))
                    {
                        // Assuming it exists still...
                        if (array_key_exists(++$skipky, $quote_matches))
                        {
                            // Aha! Add it quickly!
                            $by_numbers[] = $skipky;
                            break;
                        }
                        // Or keep looking...
                        else
                            continue;
                    }
                }
                // Populate the "Source Numbers" array with the appropriate keys, also 2.
                elseif ($key != 0 && !array_key_exists(1, $source_numbers) && stripos($value, 'source='))
                {

                    // Add the key, and create a clone variable.
                    $source_numbers[] = $key;
                    $skipky = $key;

                    // Begin the hunt for the second key!
                    while (!array_key_exists(1, $source_numbers))
                    {
                        // Assuming it exists still...
                        if (array_key_exists(++$skipky, $quote_matches))
                        {
                            // Aha! Add it quickly!
                            $source_numbers[] = $skipky;
                            break;
                        }
                        // Or keep looking...
                        else
                            continue;
                    }
                }
            }

            // A useful pointer.
            $end_key = (count(array_keys($quote_matches)) - 1);

            // Final Pattern & Final Replace Start.
            $final_pattern[] = '/\[quote';
            $replace_final[] = '<span class="bbcQuote">';

            // Who is the quote by?
            if (!empty($by_numbers) && !empty($quote_matches[$by_numbers[0]]) && !empty($quote_matches[$by_numbers[1]]))
            {
                // Add the appropriate pattern, and replace string.
                $final_pattern[] = '([ ]*?by=(.+?))?';
                $replace_final[] = $quote_matches[$by_numbers[1]] . ': ';
            }

            // Add the actual quote, here.
            $replace_final[] = '$' . $end_key;

            // Are we citing a source? We should be ;)
            if (!empty($source_numbers) && !empty($quote_matches[$source_numbers[0]]) && !empty($quote_matches[$source_numbers[1]]))
            {
                // Add the appropriate pattern, and replace string.
                $final_pattern[] = '([ ]*?source=(.+?))?';
                $replace_final[] = ' (' . self::$lang['bbc_source'] . ': ' . $quote_matches[$source_numbers[1]] . ')';
            }

            // Finish the quote search pattern, then do the work.
            $final_pattern = implode('', $final_pattern) . '\](.+?)\[\/quote\]/i';
            $replace_final = implode('', $replace_final) . '</span>';
            $message = preg_replace($final_pattern, $replace_final, $message);

        }

        // Parse the remaining BBC tags.
        $message = preg_replace($searches, $replaces, $message);

        // Return $messages.
        return $message;

    }

    public function smileys($message, $user)
    {
        $patterns = array();
        $replaces = array();
        $illegal = array(
            '.',
            '$',
            '^',
            '[',
            ']',
            '?',
            '+',
            '(',
            ')',
            '*',
            '|',
            '\\',
            '<',
            '>'
        );
        foreach (self::$globals['smilies'] as $key => $smiley)
        {
            if ($smiley['enabled'] == false)
                continue;
            elseif ($smiley['id'] === 'glare')
                $smiley['code'] = htmlspecialchars($smiley['code']);
            $pattern_tag = '/(';
            foreach ($illegal as $key => $value)
            {
                $smiley['code'] = str_replace($value, stripslashes('\\\\' . $value), $smiley['code']);
            }
            $pattern_tag .= $smiley['code'];
            $patterns[] = $pattern_tag . ')(\b|$)/' . ($smiley['case'] == 'i' ? 'i' : '');
            $replaces[] = '<img src="' . $smiley['img'] . '" alt="' . $smiley['id'] . '" title="' . $smiley['id'] . '" />';
        }

        // Parse any matches, and return the finished product :) (pun intended)
        $message = preg_replace($patterns, $replaces, $message);
		$message = $this->censor_message($message);
		$message = $this->shorten_urls($message);
        return $message;
    }

	public function censor_message($message = '')
	{
		if (empty($message))
			return false;
		$words_to_censor = array(
			'\bass\b',
			'\bfuck\b',
			'\bshit\b',
			'\bdamn\b',
			'\bbitch\b',
			'\bpenis\b',
			'\bvagina\b',
			'\bpussy\b',
			'\bdouchebag\b',
			'\bhell\b',
		);
		foreach ($words_to_censor as $key => $naughty_word)
		{
			preg_match($expr = '/(^|[^a-zA-Z0-9]?)(' . $naughty_word . ')($|[^a-zA-Z0-9])/iU', $message, $matches);
			$message = preg_replace($expr, ' !@#$ ', $message);
		}
		return trim($message);
	}

	public function shorten_urls($message = '')
	{
		if (empty($message))
			return false;
		preg_match('/>(.+?)<\/a>/', $message, $link_matches);
		if (!empty($link_matches))
		{
			foreach($link_matches as $key => $value)
			{
				if (strpos($value, '>') === false)
					continue;
				$tmp_output = '';
				$tmp_arr = explode('/', $value);
				if (count($tmp_arr) < 6)
				{
					if (count($tmp_arr) < 4)
						continue;
					$compressed_second = '';
					for ($i = 3; $i < count($tmp_arr); $i++)
						$compressed_second .= '/' . $tmp_arr[$i];
					if (strlen($compressed_second) >= 35)
					{
						$tmp_output .= $tmp_arr[0] . '/' . $tmp_arr[1] . '/' . $tmp_arr[2] . substr($compressed_second, 0, 11) . '......' . substr($compressed_second, -14);
						$message = str_replace($value, $tmp_output, $message);
						continue; 
					}
					else
						continue;
				}
				$tmp_output .= $tmp_arr[0] . '/' . $tmp_arr[1] . '/' . $tmp_arr[2] . '/' . $tmp_arr[3] . '/';
				for ($i = 4; $i < (count($tmp_arr) - 2); $i++)
					$tmp_output .= '.../';
				$tmp_output .= str_replace('<', '', $tmp_arr[count($tmp_arr) - 2]) . '';
				$message = preg_replace('~' . $value . '~i', $tmp_output, $message);				
			}
		}
		return $message;
	}
}