<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
 /**
 * Pagination Class
 * 
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Libraries
 * @since       Version 1.0
 */
class Pagination {
    
    var $base_url			       = ''; // The page we are linking to
	var $prefix                    = ''; // A custom prefix added to the path.
	var $suffix                    = ''; // A custom suffix added to the path.

	var $total_rows		      	   =  0; // Total number of items (database results)
	var $per_page			       = 10; // Max number of items you want shown per page
	var $records_offset			   =  0; // record offset
	var $num_links			       =  5; // Number of "digit" links to show before/after the currently viewed page
	var $cur_page			       =  0; // The current page being viewed
	var $use_page_numbers	       = true; // Use page number for paging instead of offset
	var $first_link			       = '<span aria-hidden="true">&larr; First</span>';
	var $prev_link                 = '<span aria-hidden="true"> &laquo; Prev</span>';
	var $next_link			       = '<span aria-hidden="true">Next &raquo;</span>';
	var $last_link			       = '<span aria-hidden="true">Last &rarr;</span>';
	var $full_tag_open		       = '<ul class="pagination">'; //'<ul class="pagination pagination-lg">';
	var $full_tag_close		       = '</ul>'; //'</ul>';
    var $statistic_tag_open	       = '<div class="pagination_statistic">';
	var $statistic_tag_close       = '</div>';
	var $first_tag_open		       = '<li class="first">'; 
	var $first_tag_close	       = '</li>';  
	var $last_tag_open		       = '<li class="last">';
	var $last_tag_close		       = '</li>'; 
	var $first_url			       = ''; // Alternative URL for the First Page.
	var $cur_tag_open		       = '<li class="active current"><a>'; // <li class="active current"><span>
	var $cur_tag_close		       = '</a></li>';
	var $next_tag_open		       = '<li class="next">';  
	var $next_tag_close		       = '</li>';
	var $prev_tag_open		       = '<li class="prev">';
	var $prev_tag_close		       = '</li>';
	var $num_tag_open		       = '<li class="numbers">'; 
	var $num_tag_close		       = '</li>'; 
	var $page_query_string	       = false; // set FALSE for friendly rewrite url or TRUE to use querystring type paging
	var $paging_query_string_var   = 'page';
	var $paging_query_string_var_value = 0;
	var $display_pages		       = true;
	var $anchor_class		       = '';
    var $skip_query_string_var_array = array('pager', 'page');
    
    
    /**
    *  Join up the key value pairs in $_GET into a single query string
    */
    public function get_page_query_strings()
    {
        $qString = array();
        
        foreach($_GET as $key => $value) {
            //check for querystring variable to skip
            if (!in_array($key, $this->skip_query_string_var_array)) { 
                if (trim($value) != '') {
                    $qString[] = $key. '=' . trim($value);    
                } else {                   
                    //accept this to be added
                    $qString[] = $key;
                }
            }
        }        
        $qString = implode('&', $qString);   
        
        return $qString;
    }
    
    /**
	 * Generate the pagination links
	 *
	 * @access	public
	 * @return	string
	 */
	public function create_links()
	{
		// If our item count or per-page total is zero there is no need to continue.
		if ($this->total_rows == 0 OR $this->per_page == 0)
		{
			return '';
		}

		// Calculate the total number of pages
		$num_pages = ceil($this->total_rows / $this->per_page);

		// Is there only one page? Hm... nothing more to do here then.
		if ($num_pages == 1)
		{
			return '';
		}

		// Set the base page index for starting page number
		if ($this->use_page_numbers)
		{
			$base_page = 1;
		}
		else
		{
			$base_page = 0;
		}

		if (isset($this->paging_query_string_var_value) && $this->paging_query_string_var_value != $base_page) {
			$this->cur_page = trim($this->paging_query_string_var_value);

			// Prep the current page - no funny business!
			$this->cur_page = (int) $this->cur_page;
		}
		
		// Set current page to 1 if using page numbers instead of offset
		if ($this->use_page_numbers AND $this->cur_page == 0)
		{
			$this->cur_page = $base_page;
		}

		$this->num_links = (int)$this->num_links;

		if ($this->num_links < 1)
		{
			echo 'Your number of links must be a positive number.';
		}

		if ( ! is_numeric($this->cur_page))
		{
			$this->cur_page = $base_page;
		}

		// Is the page number beyond the result range?
		// If so we show the last page
		if ($this->use_page_numbers)
		{
			if ($this->cur_page > $num_pages)
			{
				$this->cur_page = $num_pages;
			}
		}
		else
		{
			if ($this->cur_page > $this->total_rows)
			{
				$this->cur_page = ($num_pages - 1) * $this->per_page;
			}
		}

		$uri_page_number = $this->cur_page;
		
		if ( ! $this->use_page_numbers)
		{
			$this->cur_page = floor(($this->cur_page/$this->per_page) + 1);
		}

		// Calculate the start and end numbers. These determine
		// which number to start and end the digit links with
		$start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
		$end   = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;

		// Is pagination being used over GET or POST?  If get, add a per_page query
		// string. If post, add a trailing slash to the base URL if needed
		if ($this->page_query_string === true)
		{
			$this->base_url = rtrim($this->base_url).'&amp;'.$this->paging_query_string_var.'=';
		}
		else
		{
			$this->base_url = rtrim($this->base_url, '/') .'/';
		}

		// And here we go...
		$output = '';

		// Render the "First" link
		if  ($this->first_link !== FALSE AND $this->cur_page > ($this->num_links + 1))
		{
            $a = ''; //added
			$first_url = (($this->first_url == '') ? $this->base_url : $this->first_url) . $this->prefix.$a.$this->suffix; //modified
			$output .= $this->first_tag_open.'<a '.$this->anchor_class.'href="'.$first_url.'" aria-label="First">'.$this->first_link.'</a>'.$this->first_tag_close;
		}

		// Render the "previous" link
		if  ($this->prev_link !== FALSE AND $this->cur_page != 1)
		{
			if ($this->use_page_numbers)
			{
				$i = $uri_page_number - 1;
			}
			else
			{
				$i = $uri_page_number - $this->per_page;
			}

			if ($i == 0 && $this->first_url != '')
			{
				$output .= $this->prev_tag_open.'<a '.$this->anchor_class.'href="'.$this->first_url.'" aria-label="Previous">'.$this->prev_link.'</a>'.$this->prev_tag_close;
			}
			else
			{
				$i = ($i == 0) ? '' . $this->prefix.$i.$this->suffix : $this->prefix.$i.$this->suffix; //modified
				$output .= $this->prev_tag_open.'<a '.$this->anchor_class.'href="'.$this->base_url.$i.'" aria-label="Previous">'.$this->prev_link.'</a>'.$this->prev_tag_close;
			}

		}

		// Render the pages
		if ($this->display_pages !== false)
		{
			// Write the digit links
			for ($loop = $start -1; $loop <= $end; $loop++)
			{
				if ($this->use_page_numbers)
				{
					$i = $loop;
				}
				else
				{
					$i = ($loop * $this->per_page) - $this->per_page;
				}

				if ($i >= $base_page)
				{
					if ($this->cur_page == $loop)
					{
						$output .= $this->cur_tag_open.$loop.$this->cur_tag_close; // Current page
					}
					else
					{
						$n = ($i == $base_page) ? '' : $i;

						if ($n == '' && $this->first_url != '')
						{
							$output .= $this->num_tag_open.'<a '.$this->anchor_class.'href="'.$this->first_url.'">'.$loop.'</a>'.$this->num_tag_close;
						}
						else
						{
							$n = ($n == '') ? ''.$this->prefix.$n.$this->suffix : $this->prefix.$n.$this->suffix; //modified

							$output .= $this->num_tag_open.'<a '.$this->anchor_class.'href="'.$this->base_url.$n.'">'.$loop.'</a>'.$this->num_tag_close;
						}
					}
				}
			}
		}

		// Render the "next" link
		if ($this->next_link !== FALSE AND $this->cur_page < $num_pages)
		{
			if ($this->use_page_numbers)
			{
				$i = $this->cur_page + 1;
			}
			else
			{
				$i = ($this->cur_page * $this->per_page);
			}

			$output .= $this->next_tag_open.'<a '.$this->anchor_class.'href="'.$this->base_url.$this->prefix.$i.$this->suffix.'" aria-label="Next">'.$this->next_link.'</a>'.$this->next_tag_close;
		}

		// Render the "Last" link
		if ($this->last_link !== FALSE AND ($this->cur_page + $this->num_links) < $num_pages)
		{
			if ($this->use_page_numbers)
			{
				$i = $num_pages;
			}
			else
			{
				$i = (($num_pages * $this->per_page) - $this->per_page);
			}
			$output .= $this->last_tag_open.'<a '.$this->anchor_class.'href="'.$this->base_url.$this->prefix.$i.$this->suffix.'" aria-label="Last">'.$this->last_link.'</a>'.$this->last_tag_close;
		}

		// Kill double slashes.  Note: Sometimes we can end up with a double slash
		// in the penultimate link so we'll kill all double slashes.
		$output = preg_replace("#([^:])//+#", "\\1/", $output);

		// Add the wrapper HTML if exists
		$output = $this->full_tag_open.$output.$this->full_tag_close;

		return $output;
	}
    
    public function pagination_statistics($extra_text='', $query_criteria_text='')
    {
        $records_offset = $this->records_offset;
        $records_limit = $this->per_page;
        $total_records = $this->total_rows;
        
        $query_criteria_text =  trim($query_criteria_text);
        
        $showing_rec_to = ($records_offset+$records_limit);
        
        if ($showing_rec_to > $total_records) { 
            $showing_rec_to = $total_records; 
        }
        
        if ($extra_text == '') {
            $extra_text = 'records';
        }
        
        if ($query_criteria_text != '') {
            $query_criteria_text = "for <strong>\"$query_criteria_text\"</strong>";
        }
        
        $showing_rec_from = $records_offset+1;
        
        if ($showing_rec_from <= $total_records) {
            
            if ($showing_rec_to > $total_records && $records_offset > $total_records) {
                
                return $this->statistic_tag_open . "No $extra_text found $query_criteria_text" . $this->statistic_tag_close;
               
            } else {                
                return $this->statistic_tag_open . "Showing " . $showing_rec_from . " to " . $showing_rec_to .  " of $total_records $extra_text $query_criteria_text" . $this->statistic_tag_close;
            }    
               
       } else {        
            return $this->statistic_tag_open . "No more $extra_text found $query_criteria_text" . $this->statistic_tag_close;
       }   
             
    }
 
}// end of class