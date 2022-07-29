<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Pagination Class
 */
class IMC_Pagination extends CI_Pagination
{
	protected $num_links = 10;
    protected $use_page_numbers = TRUE;
    protected $first_link = '<i class="fa fa-angle-double-left"></i>';
    protected $next_link = '<i class="fa fa-angle-left"></i>';
    protected $prev_link = '<i class="fa fa-angle-right"></i>';
    protected $last_link = '<i class="fa fa-angle-double-right"></i>';
    protected $first_tag_open = '<li class="first">';
    protected $first_tag_close = '</li>';
    protected $last_tag_open = '<li class="last">';
    protected $last_tag_close = '</li>';
    protected $cur_tag_open = '<li class="active"><a>';
    protected $cur_tag_close = '</a></li>';
    protected $next_tag_open = '<li class="next">';
    protected $next_tag_close = '</li>';
    protected $prev_tag_open = '<li class="prev">';
    protected $prev_tag_close = '</li>';
    protected $full_tag_open = '<ul class="pagination">';
    protected $full_tag_close = '</ul>';
    protected $num_tag_open = '<li>';
    protected $num_tag_close = '</li>';
    protected $page_query_string = TRUE;
    protected $query_string_segment = 'page';

	protected $use_move_to_block = FALSE;
	protected $mixed_query_string = FALSE;

    public function __construct()
    {
        parent::__construct();
    }

	public function pages_info($total, $page, $list_size = 0, $block_size = 10) {
		$list_size = $list_size < 1 ? $this->per_page : $list_size;

		$total_page = ceil($total / $list_size);
		
		if($total_page <= 0) $total_page = 1;
		if($page <= 0) $page = 1;
		if($page > $total_page) $page = $total_page;

		$page_block = floor(($page - 1) / $block_size);
		$start_page = $page_block * $block_size + 1;
		$end_page = ($page_block + 1) * $block_size;

		if($end_page > $total_page) $end_page = $total_page;

		$pages['start'] = $start_page;
		$pages['end'] = $end_page;

		$pages['list'] = array();
		for($i = $start_page; $i <= $end_page; $i++) {
			$pages['list'][] = $i;
		}
	
		$pages['list_count'] = $list_size;
		$pages['list_total'] = $total;
		$pages['total'] = $total_page;
		$pages['page'] = $page;
		$pages['start_num'] = ($page - 1) * $list_size + 1;

		$pages['prev'] = $start_page - 1 <= 0 ? 1 : $start_page - 1;
		$pages['next'] = $end_page + 1 > $total_page ? $total_page : $end_page + 1;

		$pages['prev_list_size'] = $page - $list_size <= 0 ? 1 : $page - $list_size;
		$pages['next_list_size'] = $page + $list_size > $total_page ? $total_page : $page + $list_size;
		
		return $pages;
	}


	/**
	 * Generate the pagination links
	 *
	 * @return	string
	 */
	public function create_links()
	{
		// If our item count or per-page total is zero there is no need to continue.
		// Note: DO NOT change the operator to === here!
		if ($this->total_rows == 0 OR $this->per_page == 0)
		{
			return '';
		}

		// Calculate the total number of pages
		$num_pages = (int) ceil($this->total_rows / $this->per_page);

		// Is there only one page? Hm... nothing more to do here then.
		if ($num_pages === 1)
		{
			return '';
		}

		// Check the user defined number of links.
		$this->num_links = (int) $this->num_links;

		if ($this->num_links < 0)
		{
			show_error('Your number of links must be a non-negative number.');
		}

		// Keep any existing query string items.
		// Note: Has nothing to do with any other query string option.
		if ($this->reuse_query_string === TRUE)
		{
			$get = $this->CI->input->get();

			// Unset the controll, method, old-school routing options
			unset($get['c'], $get['m'], $get[$this->query_string_segment]);
		}
		else
		{
			$get = array();
		}

		// Put together our base and first URLs.
		// Note: DO NOT append to the properties as that would break successive calls
		$base_url = trim($this->base_url);
		$first_url = $this->first_url;

		$query_string = '';
		$query_string_sep = (strpos($base_url, '?') === FALSE) ? '?' : '&amp;';
		
		// Are we using query strings?
		if ($this->page_query_string === TRUE)
		{
			// If a custom first_url hasn't been specified, we'll create one from
			// the base_url, but without the page item.
			if ($first_url === '')
			{
				$first_url = $base_url;

				// If we saved any GET items earlier, make sure they're appended.
				if ( ! empty($get))
				{
					$first_url .= $query_string_sep.http_build_query($get);
				}
			}

			// Add the page segment to the end of the query string, where the
			// page number will be appended.
			$base_url .= $query_string_sep.http_build_query(array_merge($get, array($this->query_string_segment => '')));
		}
		else
		{
			if($this->mixed_query_string == TRUE) {
				$get = $this->CI->input->get();
			}
			
			// Standard segment mode.
			// Generate our saved query string to append later after the page number.
			if ( ! empty($get))
			{
				$query_string = $query_string_sep.http_build_query($get);
				$this->suffix .= $query_string;
			}

			// Does the base_url have the query string in it?
			// If we're supposed to save it, remove it so we can append it later.
			if ($this->reuse_query_string === TRUE && ($base_query_pos = strpos($base_url, '?')) !== FALSE)
			{
				$base_url = substr($base_url, 0, $base_query_pos);
			}

			if ($first_url === '')
			{
				$first_url = $base_url.$query_string;
			}

			$base_url = rtrim($base_url, '/').'/';
		}

		$first_url = preg_replace("/[?]$/", "", $first_url);

		// Determine the current page number.
		$base_page = ($this->use_page_numbers) ? 1 : 0;

		// Are we using query strings?
		if ($this->page_query_string === TRUE)
		{
			$this->cur_page = $this->CI->input->get($this->query_string_segment);
		}
		else
		{
			// Default to the last segment number if one hasn't been defined.
			if ($this->uri_segment === 0)
			{
				$this->uri_segment = count($this->CI->uri->segment_array());
			}
			$this->cur_page = $this->CI->uri->segment($this->uri_segment);

			// Remove any specified prefix/suffix from the segment.
			if ($this->prefix !== '' OR $this->suffix !== '')
			{
				$this->cur_page = str_replace(array($this->prefix, $this->suffix), '', $this->cur_page);
			}
		}
		// If something isn't quite right, back to the default base page.
		if ( ! ctype_digit($this->cur_page) OR ($this->use_page_numbers && (int) $this->cur_page === 0))
		{
            //print_r("111");
            //print_r($this->cur_page);
            //print_r($this->use_page_numbers);
			$this->cur_page = $base_page;
            //print_r($this->cur_page);
		}
		else
		{
			// Make sure we're using integers for comparisons later.
			$this->cur_page = (int) $this->cur_page;
		}

		// Is the page number beyond the result range?
		// If so, we show the last page.
		if ($this->use_page_numbers)
		{
			if ($this->cur_page > $num_pages)
			{
				$this->cur_page = $num_pages;
			}
		}
		elseif ($this->cur_page > $this->total_rows)
		{
			$this->cur_page = ($num_pages - 1) * $this->per_page;
		}

		$uri_page_number = $this->cur_page;

		// If we're using offset instead of page numbers, convert it
		// to a page number, so we can generate the surrounding number links.
		if ( ! $this->use_page_numbers)
		{
			$this->cur_page = (int) floor(($this->cur_page/$this->per_page) + 1);
		}

		// Calculate the start and end numbers. These determine
		// which number to start and end the digit links with.
		$start = (ceil($this->cur_page / $this->num_links) - 1) * $this->num_links + 1;
		$end   = (($start + $this->num_links - 1) < $num_pages) ? $start + $this->num_links - 1 : $num_pages;
		$block = ($this->use_page_numbers) ? $this->num_links : $this->per_page * $this->num_links;
		
		// And here we go...
		$output = '';

		// Render the "First" link.
		if ($this->first_link !== FALSE)
		{
			if($this->cur_page > $base_page) {
				// Take the general parameters, and squeeze this pagination-page attr in for JS frameworks.
				$attributes = sprintf('%s %s="%d"', $this->_attributes, $this->data_page_attr, 1);

				$output .= $this->first_tag_open.'<a href="'.$first_url.'"'.$attributes.$this->_attr_rel('start').' class="first">'
					.$this->first_link.'</a>'.$this->first_tag_close;
			} else {
				$output .= $this->first_tag_open.'<a '.$attributes.$this->_attr_rel('start').' class="first">'
					.$this->first_link.'</a>'.$this->first_tag_close;
			}
		}
		
		// Render the "Previous" link.
		if ($this->prev_link !== FALSE)
		{
			if($this->cur_page >= $this->num_links) {
				$i = ($this->use_page_numbers) ? $start - 1 : ($start - 1) * $this->per_page - $this->per_page;
				$attributes = sprintf('%s %s="%d"', $this->_attributes, $this->data_page_attr, ($this->cur_page - 1));

				if ($i === $base_page)
				{
					// First page
					$output .= $this->prev_tag_open.'<a href="'.$first_url.'"'.$attributes.$this->_attr_rel('prev').'>'
							.$this->prev_link.'</a><span class="num">'.$this->prev_tag_close;
				}
				else
				{
					$append = $this->prefix.$i.$this->suffix;
					$output .= $this->prev_tag_open.'<a href="'.$base_url.$append.'"'.$attributes.$this->_attr_rel('prev').' class="pre">'
							.$this->prev_link.'</a><span class="num">'.$this->prev_tag_close;
				}
			} else {
				$output .= $this->prev_tag_open.'<a href="'.$first_url.'"'.$attributes.$this->_attr_rel('prev').' class="pre">'
						.$this->prev_link.'</a><span class="num">'.$this->prev_tag_close;
			
			}
		}
		// Render the pages
		if ($this->display_pages !== FALSE)
		{
			// Write the digit links
			for ($loop = (int)$start; $loop <= $end; $loop++)
			{
				$i = ($this->use_page_numbers) ? $loop : ($loop * $this->per_page) - $this->per_page;

				$attributes = sprintf('%s %s="%d"', $this->_attributes, $this->data_page_attr, $loop);

				if ($i >= $base_page)
				{
					if ($this->cur_page === $loop)
					{
						// Current page
						$output .= $this->cur_tag_open.$loop.$this->cur_tag_close;
					}
					elseif ($i === $base_page)
					{
						// First page
						$output .= $this->num_tag_open.'<a href="'.$first_url.'"'.$attributes.$this->_attr_rel('start').'>'
							.$loop.'</a>'.$this->num_tag_close;
					}
					else
					{
						$append = $this->prefix.$i.$this->suffix;
						$output .= $this->num_tag_open.'<a href="'.$base_url.$append.'"'.$attributes.'>'
							.$loop.'</a>'.$this->num_tag_close;
					}
				}
			}
		}
		

		// Render the "next" link
		if ($this->next_link !== FALSE)
		{
			if($end + 1 <= $num_pages) {
				$i = ($this->use_page_numbers) ? $end + 1 : $end * $this->per_page;

				$attributes = sprintf('%s %s="%d"', $this->_attributes, $this->data_page_attr, $this->cur_page + 1);

				$output .= $this->next_tag_open.'</span><a href="'.$base_url.$this->prefix.$i.$this->suffix.'"'.$attributes
					.$this->_attr_rel('next').'  class="next">'.$this->next_link.'</a>'.$this->next_tag_close;
			} else {
				$output .= $this->next_tag_open.'</span><a href="'.$base_url.$this->prefix.$end.$this->suffix.'"'.$attributes
					.$this->_attr_rel('next').' class="next">'.$this->next_link.'</a>'.$this->next_tag_close;
			}
		}

		// Render the "Last" link
		if ($this->last_link !== FALSE)
		{
			if($this->cur_page < $num_pages) {
				$i = ($this->use_page_numbers) ? $num_pages : ($num_pages * $this->per_page) - $this->per_page;

				$attributes = sprintf('%s %s="%d"', $this->_attributes, $this->data_page_attr, $num_pages);

				$output .= $this->last_tag_open.'<a href="'.$base_url.$this->prefix.$i.$this->suffix.'"'.$attributes.' class="last">'
					.$this->last_link.'</a>'.$this->last_tag_close;
			} else {
				$output .= $this->last_tag_open.'<a '.$attributes.' class="last">'
					.$this->last_link.'</a>'.$this->last_tag_close;
			}


		}

		// Kill double slashes. Note: Sometimes we can end up with a double slash
		// in the penultimate link so we'll kill all double slashes.
		$output = preg_replace('#([^:"])//+#', '\\1/', $output);

		// Add the wrapper HTML if exists
		return $this->full_tag_open.$output.$this->full_tag_close;
	}
}
