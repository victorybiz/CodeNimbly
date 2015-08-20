<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 *  Takes a word, or list of words, and reduces them to their English stems.
 *
 *  This is a fairly faithful implementation of the Porter stemming algorithm that
 *  reduces English words to their stems, originally adapted from the ANSI-C code found
 *  on the official Porter Stemming Algorithm website, located at
 *  http://www.tartarus.org/~martin/PorterStemmer and later changed to conform
 *  more accurately to the algorithm itself.
 *
 *  There is a deviation in the way compound words are stemmed, such as
 *  hyphenated words and words starting with certain prefixes. For instance,
 *  "international" should be reduced to "internation" and not "intern," but
 *  an unmodified version of the alorithm will do just that. Currently, only
 *  hyphenated words are accounted for.
 * 
 * 
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Libraries
 * @since       Version 1.0
 */
class Stemmer
{
    /**
    private $stop_words = array(
                'i','a','about','an','and','are','as','at','be','by','com','de','en','for',
                'from','how','in','is','it','la','of','on','or','that','the','this','to','was','
                what','when','where','who','will','with','und','the','www'
        ); */
    private $stop_words = array(
                                    "a", "about", "above", "above", "across", "after", "afterwards", "again", "against", 
                                    "all", "almost", "alone", "along", "already", "also","although","always","am","among", 
                                    "amongst", "amoungst", "amount",  "an", "and", "another", "any","anyhow","anyone", 
                                    "anything","anyway", "anywhere", "are", "around", "as",  "at", "back","be","became", 
                                    "because","become","becomes", "becoming", "been", "before", "beforehand", "behind", 
                                    "being", "below", "beside", "besides", "between", "beyond", "bill", "both", "bottom",
                                    "but", "by", "call", "can", "cannot", "cant", "co", "con", "could", "couldnt", "cry", 
                                    "de", "describe", "detail", "do", "done", "down", "due", "during", "each", "eg", "eight", 
                                    "either", "eleven","else", "elsewhere", "empty", "enough", "etc", "even", "ever", "every",
                                     "everyone", "everything", "everywhere", "except", "few", "fifteen", "fify", "fill", "find", 
                                     "fire", "first", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front", 
                                     "full", "further", "get", "give", "go", "had", "has", "hasnt", "have", "he", "hence", "her", 
                                     "here", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "him", "himself", 
                                     "his", "how", "however", "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", 
                                     "is", "it", "its", "itself", "keep", "last", "latter", "latterly", "least", "less", "ltd", 
                                     "made", "many", "may", "me", "meanwhile", "might", "mill", "mine", "more", "moreover", "most", 
                                     "mostly", "move", "much", "must", "my", "myself", "name", "namely", "neither", "never", 
                                     "nevertheless", "next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", 
                                     "now", "nowhere", "of", "off", "often", "on", "once", "one", "only", "onto", "or", "other", 
                                     "others", "otherwise", "our", "ours", "ourselves", "out", "over", "own","part", "per", "perhaps", 
                                     "please", "put", "rather", "re", "same", "see", "seem", "seemed", "seeming", "seems", "serious", 
                                     "several", "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so", "some", 
                                     "somehow", "someone", "something", "sometime", "sometimes", "somewhere", "still", "such", "system", 
                                     "take", "ten", "than", "that", "the", "their", "them", "themselves", "then", "thence", "there", 
                                     "thereafter", "thereby", "therefore", "therein", "thereupon", "these", "they", "thickv", "thin", 
                                     "third", "this", "those", "though", "three", "through", "throughout", "thru", "thus", "to", "together", 
                                     "too", "top", "toward", "towards", "twelve", "twenty", "two", "un", "under", "until", "up", "upon", "us", 
                                     "very", "via", "was", "we", "well", "were", "what", "whatever", "when", "whence", "whenever", "where", 
                                     "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever", "whether", "which", "while", 
                                     "whither", "who", "whoever", "whole", "whom", "whose", "why", "will", "with", "within", "without", "would", 
                                     "yet", "you", "your", "yours", "yourself", "yourselves", "the",
                                     "nbsp"
                        );
    
    public function extract_keywords_from_url($url, $extract_as_comma_separated_string=true, $stem_words=true,  $keywords_limit=20, $stem_from_word_str_length=2, $keyword_prefix='', $keyword_suffix='')
    { 
        $ch = curl_init();
        $timeout = 15;
        curl_setopt ($ch, CURLOPT_URL,$url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);    // allow redirects
        $content_str = curl_exec($ch);
        curl_close($ch);
        
        $content_str =  strip_tags($content_str); //strip off html tags
        
        $extracted_keywords = $this->extract_keywords($content_str, $extract_as_comma_separated_string, $stem_words, $keywords_limit, $stem_from_word_str_length, $keyword_prefix, $keyword_suffix);
        return $extracted_keywords;
    }
    
    public function extract_keywords($string, $extract_as_comma_separated_string=true, $stem_words=true, $keywords_limit=20, $stem_from_word_str_length=2, $keyword_prefix='', $keyword_suffix='')
    {        
        $string = preg_replace('/ss+/i', '', $string);
        $string = trim($string); // trim the string
        $string = preg_replace('/[^a-zA-Z0-9 -]/', '', $string); // only take alphanumerical characters, but keep the spaces and dashes too…
        $string = mb_strtolower($string); // make it lowercase
        
        preg_match_all('/\b.*?\b/i', $string, $match_words);
        $match_words = $match_words[0];
        
        foreach ($match_words as $key => $item) 
        {
            $item = trim($item);
            if (empty($item) || in_array($item, $this->stop_words) || strlen($item) <= $stem_from_word_str_length) {
                unset($match_words[$key]);
            } else {
                //stem the words
                if ($stem_words === true) {
                    $match_words[$key] = $keyword_prefix . $this->stem($item) . $keyword_suffix;
                } else {
                    $match_words[$key] = $keyword_prefix . $item . $keyword_suffix;
                }             
            }
        }   
        $word_count_arr = array();
 
        if (is_array($match_words)) {
          foreach($match_words as $key => $val) 
          {
              if (isset($word_count_arr[$val])) {
                  $word_count_arr[$val]++;
              } else {
                  $word_count_arr[$val] = 1;
              }
          }
        }
        arsort($word_count_arr);
        if (is_numeric($keywords_limit) && $keywords_limit > 0) {
            $word_count_arr = array_slice($word_count_arr, 0, $keywords_limit);
        } else {
            $word_count_arr = array_slice($word_count_arr, 0);
        }        
        if ($extract_as_comma_separated_string === true) {
            $extracted_keywords = implode(',', array_keys($word_count_arr));
        } else {
            $extracted_keywords = $word_count_arr;
        }
        return $extracted_keywords;
    }
    

    /**
     *  Takes a word and returns it reduced to its stem.
     *
     *  Non-alphanumerics and hyphens are removed, except for dots and
	 *  apostrophes, and if the word is less than three characters in
	 *  length, it will be stemmed according to the five-step
     *  Porter stemming algorithm.
     *
     *  Note special cases here: hyphenated words (such as half-life) will
	 *  only have the base after the last hyphen stemmed (so half-life would
	 *  only have "life" subject to stemming). Handles multi-hyphenated
	 *  words, too.
     *
     *  @param string $word Word to reduce
     *  @access public
     *  @return string Stemmed word
     */
    function stem( $word )
    {
        if ( empty($word) ) {
            return false;
        }

        $result = '';

        $word = strtolower($word);

        // Strip punctuation, etc. Keep ' and . for URLs and contractions.
        if ( substr($word, -2) == "'s" ) {
            $word = substr($word, 0, -2);
        }
        $word = preg_replace("/[^a-z0-9'.-]/", '', $word);

        $first = '';
        if ( strpos($word, '-') !== false ) {
            //list($first, $word) = explode('-', $word);
            //$first .= '-';
            $first = substr($word, 0, strrpos($word, '-') + 1); // Grabs hyphen too
            $word = substr($word, strrpos($word, '-') + 1);
        }
        if ( strlen($word) > 2 ) {
            $word = $this->_step_1($word);
            $word = $this->_step_2($word);
            $word = $this->_step_3($word);
            $word = $this->_step_4($word);
            $word = $this->_step_5($word);
        }

        $result = $first . $word;

        return $result;
    }
    

    /**
     *  Takes a list of words and returns them reduced to their stems.
     *
     *  $words can be either a string or an array. If it is a string, it will
     *  be split into separate words on whitespace, commas, or semicolons. If
     *  an array, it assumes one word per element.
     *
     *  @param mixed $words String or array of word(s) to reduce
     *  @access public
     *  @return array List of word stems
     */
    function stem_list( $words )
    {
        if ( empty($words) ) {
            return false;
        }

        $results = array();

        if ( !is_array($words) ) {
            //$words = split("[ ,;\n\r\t]+", trim($words));
            $words = preg_split("[ ,;\n\r\t]", trim($words));
        }

        foreach ( $words as $word ) {
            if ( $result = $this->stem($word) ) {
                $results[] = $result;
            }
        }

        return $results;
    }

    /**
     *  Performs the functions of steps 1a and 1b of the Porter Stemming Algorithm.
     *
     *  First, if the word is in plural form, it is reduced to singular form.
     *  Then, any -ed or -ing endings are removed as appropriate, and finally,
     *  words ending in "y" with a vowel in the stem have the "y" changed to "i".
     *
     *  @param string $word Word to reduce
     *  @access private
     *  @return string Reduced word
     */
    private function _step_1( $word )
    {
		// Step 1a
		if ( substr($word, -1) == 's' ) {
            if ( substr($word, -4) == 'sses' ) {
                $word = substr($word, 0, -2);
            } elseif ( substr($word, -3) == 'ies' ) {
                $word = substr($word, 0, -2);
            } elseif ( substr($word, -2, 1) != 's' ) {
                // If second-to-last character is not "s"
                $word = substr($word, 0, -1);
            }
        }
		// Step 1b
        if ( substr($word, -3) == 'eed' ) {
			if ($this->count_vc(substr($word, 0, -3)) > 0 ) {
	            // Convert '-eed' to '-ee'
	            $word = substr($word, 0, -1);
			}
        } else {
            if ( preg_match('/([aeiou]|[^aeiou]y).*(ed|ing)$/', $word) ) { // vowel in stem
                // Strip '-ed' or '-ing'
                if ( substr($word, -2) == 'ed' ) {
                    $word = substr($word, 0, -2);
                } else {
                    $word = substr($word, 0, -3);
                }
                if ( substr($word, -2) == 'at' || substr($word, -2) == 'bl' ||
                     substr($word, -2) == 'iz' ) {
                    $word .= 'e';
                } else {
                    $last_char = substr($word, -1, 1);
                    $next_to_last = substr($word, -2, 1);
                    // Strip ending double consonants to single, unless "l", "s" or "z"
                    if ( $this->is_consonant($word, -1) &&
                         $last_char == $next_to_last &&
                         $last_char != 'l' && $last_char != 's' && $last_char != 'z' ) {
                        $word = substr($word, 0, -1);
                    } else {
                        // If VC, and cvc (but not w,x,y at end)
                        if ( $this->count_vc($word) == 1 && $this->_o($word) ) {
                            $word .= 'e';
                        }
                    }
                }
            }
        }
        // Step 1c
        // Turn y into i when another vowel in stem
        if ( preg_match('/([aeiou]|[^aeiou]y).*y$/', $word) ) { // vowel in stem
            $word = substr($word, 0, -1) . 'i';
        }
        return $word;
    }

    /**
     *  Performs the function of step 2 of the Porter Stemming Algorithm.
     *
     *  Step 2 maps double suffixes to single ones when the second-to-last character
     *  matches the given letters. So "-ization" (which is "-ize" plus "-ation"
     *  becomes "-ize". Mapping to a single character occurence speeds up the script
     *  by reducing the number of possible string searches.
     *
     *  Note: for this step (and steps 3 and 4), the algorithm requires that if
     *  a suffix match is found (checks longest first), then the step ends, regardless
     *  if a replacement occurred. Some (or many) implementations simply keep
     *  searching though a list of suffixes, even if one is found.
     *
     *  @param string $word Word to reduce
     *  @access private
     *  @return string Reduced word
     */
    private function _step_2( $word )
    {
        switch ( substr($word, -2, 1) ) {
            case 'a':
                if ( $this->_replace($word, 'ational', 'ate', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'tional', 'tion', 0) ) {
                    return $word;
                }
                break;
            case 'c':
                if ( $this->_replace($word, 'enci', 'ence', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'anci', 'ance', 0) ) {
                    return $word;
                }
                break;
            case 'e':
                if ( $this->_replace($word, 'izer', 'ize', 0) ) {
                    return $word;
                }
                break;
            case 'l':
                // This condition is a departure from the original algorithm;
                // I adapted it from the departure in the ANSI-C version.
				if ( $this->_replace($word, 'bli', 'ble', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'alli', 'al', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'entli', 'ent', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'eli', 'e', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'ousli', 'ous', 0) ) {
                    return $word;
                }
                break;
            case 'o':
                if ( $this->_replace($word, 'ization', 'ize', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'isation', 'ize', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'ation', 'ate', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'ator', 'ate', 0) ) {
                    return $word;
                }
                break;
            case 's':
                if ( $this->_replace($word, 'alism', 'al', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'iveness', 'ive', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'fulness', 'ful', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'ousness', 'ous', 0) ) {
                    return $word;
                }
                break;
            case 't':
                if ( $this->_replace($word, 'aliti', 'al', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'iviti', 'ive', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'biliti', 'ble', 0) ) {
                    return $word;
                }
                break;
            case 'g':
                // This condition is a departure from the original algorithm;
                // I adapted it from the departure in the ANSI-C version.
                if ( $this->_replace($word, 'logi', 'log', 0) ) { //*****
                    return $word;
                }
                break;
        }
        return $word;
    }

    /**
     *  Performs the function of step 3 of the Porter Stemming Algorithm.
     *
     *  Step 3 works in a similar stragegy to step 2, though checking the
     *  last character.
     *
     *  @param string $word Word to reduce
     *  @access private
     *  @return string Reduced word
     */
    private function _step_3( $word )
    {
        switch ( substr($word, -1) ) {
            case 'e':
                if ( $this->_replace($word, 'icate', 'ic', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'ative', '', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'alize', 'al', 0) ) {
                    return $word;
                }
                break;
            case 'i':
                if ( $this->_replace($word, 'iciti', 'ic', 0) ) {
                    return $word;
                }
                break;
            case 'l':
                if ( $this->_replace($word, 'ical', 'ic', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'ful', '', 0) ) {
                    return $word;
                }
                break;
            case 's':
                if ( $this->_replace($word, 'ness', '', 0) ) {
                    return $word;
                }
                break;
        }
        return $word;
    }

    /**
     *  Performs the function of step 4 of the Porter Stemming Algorithm.
     *
     *  Step 4 works similarly to steps 3 and 2, above, though it removes
     *  the endings in the context of VCVC (vowel-consonant-vowel-consonant
     *  combinations).
     *
     *  @param string $word Word to reduce
     *  @access private
     *  @return string Reduced word
     */
    private function _step_4( $word )
    {
        switch ( substr($word, -2, 1) ) {
            case 'a':
                if ( $this->_replace($word, 'al', '', 1) ) {
                    return $word;
                }
                break;
            case 'c':
                if ( $this->_replace($word, 'ance', '', 1) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'ence', '', 1) ) {
                    return $word;
                }
                break;
            case 'e':
                if ( $this->_replace($word, 'er', '', 1) ) {
                    return $word;
                }
                break;
            case 'i':
                if ( $this->_replace($word, 'ic', '', 1) ) {
                    return $word;
                }
                break;
            case 'l':
                if ( $this->_replace($word, 'able', '', 1) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'ible', '', 1) ) {
                    return $word;
                }
                break;
            case 'n':
                if ( $this->_replace($word, 'ant', '', 1) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'ement', '', 1) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'ment', '', 1) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'ent', '', 1) ) {
                    return $word;
                }
                break;
            case 'o':
                // special cases
                if ( substr($word, -4) == 'sion' || substr($word, -4) == 'tion' ) {
                    if ( $this->_replace($word, 'ion', '', 1) ) {
                        return $word;
                    }
                }
                if ( $this->_replace($word, 'ou', '', 1) ) {
                    return $word;
                }
                break;
            case 's':
                if ( $this->_replace($word, 'ism', '', 1) ) {
                    return $word;
                }
                break;
            case 't':
                if ( $this->_replace($word, 'ate', '', 1) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'iti', '', 1) ) {
                    return $word;
                }
                break;
            case 'u':
                if ( $this->_replace($word, 'ous', '', 1) ) {
                    return $word;
                }
                break;
            case 'v':
                if ( $this->_replace($word, 'ive', '', 1) ) {
                    return $word;
                }
                break;
            case 'z':
                if ( $this->_replace($word, 'ize', '', 1) ) {
                    return $word;
                }
                break;
        }
        return $word;
    }

    /**
     *  Performs the function of step 5 of the Porter Stemming Algorithm.
     *
     *  Step 5 removes a final "-e" and changes "-ll" to "-l" in the context
     *  of VCVC (vowel-consonant-vowel-consonant combinations).
     *
     *  @param string $word Word to reduce
     *  @access private
     *  @return string Reduced word
     */
    private function _step_5( $word )
    {
        if ( substr($word, -1) == 'e' ) {
            $short = substr($word, 0, -1);
            // Only remove in vcvc context...
            if ( $this->count_vc($short) > 1 ) {
                $word = $short;
            } elseif ( $this->count_vc($short) == 1 && !$this->_o($short) ) {
                $word = $short;
            }
        }
        if ( substr($word, -2) == 'll' ) {
            // Only remove in vcvc context...
            if ( $this->count_vc($word) > 1 ) {
                $word = substr($word, 0, -1);
            }
        }
        return $word;
    }

    /**
     *  Checks that the specified letter (position) in the word is a consonant.
     *
     *  Handy check adapted from the ANSI C program. Regular vowels always return
     *  FALSE, while "y" is a special case: if the prececing character is a vowel,
     *  "y" is a consonant, otherwise it's a vowel.
     *
     *  And, if checking "y" in the first position and the word starts with "yy",
     *  return true even though it's not a legitimate word (it crashes otherwise).
     *
     *  @param string $word Word to check
     *  @param integer $pos Position in the string to check
     *  @access public
     *  @return boolean
     */
    private function is_consonant( $word, $pos )
    {
        // Sanity checking $pos
        if ( abs($pos) > strlen($word) ) {
            if ( $pos < 0 ) {
                // Points "too far back" in the string. Set it to beginning.
                $pos = 0;
            } else {
                // Points "too far forward." Set it to end.
                $pos = -1;
            }
        }
        $char = substr($word, $pos, 1);
        switch ( $char ) {
            case 'a':
            case 'e':
            case 'i':
            case 'o':
            case 'u':
                return false;
            case 'y':
                if ( $pos == 0 || strlen($word) == -$pos ) {
                    // Check second letter of word.
                    // If word starts with "yy", return true.
                    if ( substr($word, 1, 1) == 'y' ) {
                        return true;
                    }
                    return !($this->is_consonant($word, 1));
                } else {
                    return !($this->is_consonant($word, $pos - 1));
                }
            default:
                return true;
        }
    }

    /**
     *  Counts (measures) the number of vowel-consonant occurences.
     *
     *  Based on the algorithm; this handy function counts the number of
     *  occurences of vowels (1 or more) followed by consonants (1 or more),
     *  ignoring any beginning consonants or trailing vowels. A legitimate
     *  VC combination counts as 1 (ie. VCVC = 2, VCVCVC = 3, etc.).
     *
     *  @param string $word Word to measure
     *  @access public
     *  @return integer
     */
    private function count_vc( $word )
    {
        $m = 0;
        $length = strlen($word);
        $prev_c = false;
        for ( $i = 0; $i < $length; $i++ ) {
            $is_c = $this->is_consonant($word, $i);
            if ( $is_c ) {
                if ( $m > 0 && !$prev_c ) {
                    $m += 0.5;
                }
            } else {
                if ( $prev_c || $m == 0 ) {
                    $m += 0.5;
                }
            }
            $prev_c = $is_c;
        }
        $m = floor($m);
        return $m;
    }

    /**
     *  Checks for a specific consonant-vowel-consonant condition.
     *
     *  This function is named directly from the original algorithm. It
     *  looks the last three characters of the word ending as
     *  consonant-vowel-consonant, with the final consonant NOT being one
     *  of "w", "x" or "y".
     *
     *  @param string $word Word to check
     *  @access private
     *  @return boolean
     */
    private function _o( $word )
    {
        if ( strlen($word) >= 3 ) {
            if ( $this->is_consonant($word, -1) && !$this->is_consonant($word, -2) &&
                 $this->is_consonant($word, -3) ) {
		        $last_char = substr($word, -1);
		        if ( $last_char == 'w' || $last_char == 'x' || $last_char == 'y' ) {
		            return false;
		        }
                return true;
            }
        }
        return false;
    }

    /**
     *  Replaces suffix, if found and word measure is a minimum count.
     *
     *  @param string $word Word to check and modify
     *  @param string $suffix Suffix to look for
     *  @param string $replace Suffix replacement
     *  @param integer $m Word measure value that the word must be greater
     *                    than to replace
     *  @access private
     *  @return boolean
     */
    private function _replace( &$word, $suffix, $replace, $m = 0 )
    {
        $sl = strlen($suffix);
        if ( substr($word, -$sl) == $suffix ) {
            $short = substr_replace($word, '', -$sl);
            if ( $this->count_vc($short) > $m ) {
                $word = $short . $replace;
            }
            // Found this suffix, doesn't matter if replacement succeeded
            return true;
        }
        return false;
    }

}

?>
