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

//-- TODO: Get rid of the extends here...
class MessageParser extends PureChat
{

	public $message, $user;

	public function __construct()
	{
		$this->message = '';
		$this->user    = '';
	}

	public function setMessage($message, $user)
	{
		$this->message = $message;
		$this->user    = $user;
	}

	public function getMessage()
	{
		return $this->message;
	}

	public function formatVulgar()
	{
		if (empty($this->message))
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

		foreach ($words_to_censor as $naughty_word)
		{
			preg_match($expr = '/(^|[^a-zA-Z0-9]?)(' . $naughty_word . ')($|[^a-zA-Z0-9])/iU', $this->message, $matches);
			$this->message = preg_replace($expr, ' !@#$ ', $this->message);
		}
		return trim($this->message);
	}

    public function formatText()
    {

        // The basic BBC tag searches.
        $searches = array(
            '/\*(.+?)\*/i',
            '/\_(.+?)\_/i',
            // !! This regex was taken from SimpleMachines Forum 2.0.2 ./Sources/Subs.php for auto-linking URLs.
            '/(?<=[\s>\.(;\'"]|^)((?:http|https):\/\/[\w\-_%@:|]+(?:\.[\w\-_%]+)*(?::\d+)?(?:\/[\w\-_\~%\.@!,\?&;=#(){}+:\'\\\\]*)*[\/\w\-_\~%@\?;=#}\\\\])/i',
            '/^\/me(.+?)$/i',
        );

        // The basic BBC tag replaces.
        $replaces = array(
            '<span class="bbcBold">$1</span>',
            '<span class="bbcItalic">$1</span>',
            '<a href="$1" target="_blank">$1</a>',
            '<span class="bbcItalic">' . $this->user . ' $1</span>',
        );

        // Parse the remaining BBC tags.
        $this->message = preg_replace($searches, $replaces, $this->message);

        // Return $messages.
        return $this->message;

    }

    public function formatSmileys()
	{
        $patterns = array();
        $replaces = array();
        $illegal = array(
            '.', '$',
            '^', '[',
            ']', '?',
            '+', '(',
            ')', '*',
            '|', '\\',
            '<', '>',
        );
        foreach (PureChat::$globals['smilies'] as $smiley)
        {
            if ($smiley['enabled'] == false)
			{
                continue;
			}
            else if ($smiley['id'] === 'glare')
			{
                $smiley['code'] = htmlspecialchars($smiley['code']);
			}
            $pattern_tag = '/(';
            foreach ($illegal as $value)
            {
                $smiley['code'] = str_replace($value, stripslashes('\\\\' . $value), $smiley['code']);
            }
            $pattern_tag .= $smiley['code'];
            $patterns[] = $pattern_tag . ')(\b|$)/' . ($smiley['case'] == 'i' ? 'i' : '');
            $replaces[] = '<img src="' . $smiley['img'] . '" alt="' . $smiley['id'] . '" title="' . $smiley['id'] . '" />';
        }

        // Parse any matches, and return the finished product :) (pun intended)
        $this->message = preg_replace($patterns, $replaces, $this->message);

        return $this->message;
    }

	//-- TODO - This method should use PHP's built in function to parse URLs to shorten them, not unnecessarily complicated regex.
	public function formatURLs()
	{
		if (empty($this->message))
			return false;

		preg_match('/>(.+?)<\/a>/', $this->message, $link_matches);
		if (!empty($link_matches))
		{
			foreach($link_matches as $value)
			{
				if (strpos($value, '>') === false)
				{
					continue;
				}
				$tmp_output = '';
				$tmp_arr = explode('/', $value);
				if (count($tmp_arr) < 6)
				{
					if (count($tmp_arr) < 4)
					{
						continue;
					}
					$compressed_second = '';
					for ($i = 3; $i < count($tmp_arr); $i++)
					{
						$compressed_second .= '/' . $tmp_arr[$i];
					}
					if (strlen($compressed_second) >= 35)
					{
						$tmp_output .= $tmp_arr[0] . '/' . $tmp_arr[1] . '/' . $tmp_arr[2] . substr($compressed_second, 0, 11) . '......' . substr($compressed_second, -14);
						$this->message = str_replace($value, $tmp_output, $this->message);
						continue; 
					}
					else
					{
						continue;
					}
				}
				$tmp_output .= $tmp_arr[0] . '/' . $tmp_arr[1] . '/' . $tmp_arr[2] . '/' . $tmp_arr[3] . '/';
				for ($i = 4; $i < (count($tmp_arr) - 2); $i++)
				{
					$tmp_output .= '.../';
				}
				$tmp_output .= str_replace('<', '', $tmp_arr[count($tmp_arr) - 2]) . '';
				$this->message = preg_replace('~' . $value . '~i', $tmp_output, $this->message);
			}
		}
		return $this->message;
	}
}