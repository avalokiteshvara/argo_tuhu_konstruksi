<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class SesItem {
	/**
	 * Reference to CodeIgniter instance
	 *
	 * @var object
	 */
	protected $CI;

	/**
	 * Contents of the cart
	 *
	 * @var array
	 */
	protected $_ses_contents = array();

	/**
	 * Shopping Class Constructor
	 *
	 * The constructor loads the Session class, used to store the shopping cart contents.
	 *
	 * @param	array
	 * @return	void
	 */
	public function __construct($params = array())
	{
		// Set the super object to a local variable for use later
		$this->CI =&get_instance();

		// Are any config settings being passed manually?  If so, set them
		$config = is_array($params) ? $params : array();

		// Load the Sessions class
		$this->CI->load->driver('session', $config);

		// Grab the shopping cart array from the session table
		$this->_ses_contents = $this->CI->session->userdata('ses_contents');

	}

	// --------------------------------------------------------------------

	/**
	 * Insert items into the cart and save it to the session table
	 *
	 * @param	array
	 * @return	bool
	 */
	public function insert($items = array())
	{
		// Was any cart data passed? No? Bah...
		if ( ! is_array($items) OR count($items) === 0)
		{
			log_message('error', 'The insert method must be passed an array containing data.');
			return FALSE;
		}

		// You can either insert a single product using a one-dimensional array,
		// or multiple products using a multi-dimensional one. The way we
		// determine the array type is by looking for a required array key named "id"
		// at the top level. If it's not found, we will assume it's a multi-dimensional array.

		$save_ses = FALSE;
		if (isset($items['id']))
		{
			if (($rowid = $this->_insert($items)))
			{
				$save_ses = TRUE;
			}
		}
		else
		{
			foreach ($items as $val)
			{
				if (is_array($val) && isset($val['id']))
				{
					if ($this->_insert($val))
					{
						$save_ses = TRUE;
					}
				}
			}
		}

		// Save the cart data if the insert was successful
		if ($save_ses === TRUE)
		{
			$this->_save_ses();
			return isset($rowid) ? $rowid : TRUE;
		}

		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * Insert
	 *
	 * @param	array
	 * @return	bool
	 */
	protected function _insert($items = array())
	{
		// Was any cart data passed? No? Bah...
		if ( ! is_array($items) OR count($items) === 0)
		{
			log_message('error', 'The insert method must be passed an array containing data.');
			return FALSE;
		}



		// we simply MD5 the product ID.
		// Technically, we don't need to MD5 the ID in this case, but it makes
		// sense to standardize the format of array indexes for both conditions
		$rowid = md5($items['id']);

		// Re-create the entry, just to make sure our index contains only the data from this submission
		$items['rowid'] = $rowid;
		$this->_ses_contents[$rowid] = $items;

		return $rowid;
	}

	// --------------------------------------------------------------------

	/**
	 * Update the cart
	 *
	 * This function permits the quantity of a given item to be changed.
	 * Typically it is called from the "view cart" page if a user makes
	 * changes to the quantity before checkout. That array must contain the
	 * product ID and quantity for each item.
	 *
	 * @param	array
	 * @return	bool
	 */
	public function update($items = array())
	{
		// Was any cart data passed?
		if ( ! is_array($items) OR count($items) === 0)
		{
			return FALSE;
		}

		// You can either update a single product using a one-dimensional array,
		// or multiple products using a multi-dimensional one.  The way we
		// determine the array type is by looking for a required array key named "rowid".
		// If it's not found we assume it's a multi-dimensional array
		$save_ses = FALSE;
		if (isset($items['rowid']))
		{
			if ($this->_update($items) === TRUE)
			{
				$save_ses = TRUE;
			}
		}
		else
		{
			foreach ($items as $val)
			{
				if (is_array($val) && isset($val['rowid']))
				{
					if ($this->_update($val) === TRUE)
					{
						$save_ses = TRUE;
					}
				}
			}
		}

		// Save the cart data if the insert was successful
		if ($save_ses === TRUE)
		{
			$this->_save_ses();
			return TRUE;
		}

		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * Update the cart
	 *
	 * This function permits changing item properties.
	 * Typically it is called from the "view cart" page if a user makes
	 * changes to the quantity before checkout. That array must contain the
	 * rowid and quantity for each item.
	 *
	 * @param	array
	 * @return	bool
	 */
	protected function _update($items = array())
	{
		// Without these array indexes there is nothing we can do
		if ( ! isset($items['rowid'], $this->_ses_contents[$items['rowid']]))
		{
			return FALSE;
		}


		// find updatable keys
		$keys = array_intersect(array_keys($this->_ses_contents[$items['rowid']]), array_keys($items));

		// product id & name shouldn't be changed
		foreach (array_diff($keys, array('id')) as $key)
		{
			$this->_ses_contents[$items['rowid']][$key] = $items[$key];
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Save the cart array to the session DB
	 *
	 * @return	bool
	 */
	protected function _save_ses()
	{


		// Is our cart empty? If so we delete it from the session
		if (count($this->_ses_contents) <= 0)
		{
			$this->CI->session->unset_userdata('ses_contents');

			// Nothing more to do... coffee time!
			return FALSE;
		}

		// If we made it this far it means that our cart has data.
		// Let's pass it to the Session class so it can be stored
		$this->CI->session->set_userdata(array('ses_contents' => $this->_ses_contents));

		// Woot!
		return TRUE;
	}

	/**
	 * Remove Item
	 *
	 * Removes an item from the cart
	 *
	 * @param	int
	 * @return	bool
	 */
	 public function remove($items)
	 {
		// unset($this->_cart_contents[$items['rowid']]);
		// unset & save
		unset($this->_ses_contents[$items['rowid']]);
		$this->_save_ses();
		return TRUE;
	 }

	// --------------------------------------------------------------------


	/**
	 * Cart Contents
	 *
	 * Returns the entire cart array
	 *
	 * @param	bool
	 * @return	array
	 */
	public function contents($newest_first = FALSE)
	{
		// do we want the newest first?
		$item = ($newest_first) ? array_reverse($this->_ses_contents) : $this->_ses_contents;

		return $item;
	}

	// --------------------------------------------------------------------

	/**
	 * Get cart item
	 *
	 * Returns the details of a specific item in the cart
	 *
	 * @param	string	$row_id
	 * @return	array
	 */
	public function get_item($row_id)
	{
		return (! isset($this->_ses_contents[$row_id]))
			? FALSE
			: $this->_ses_contents[$row_id];
	}


	/**
	 * Destroy the cart
	 *
	 * Empties the cart and kills the session
	 *
	 * @return	void
	 */
	public function destroy()
	{
		if(count($this->contents()) > 0){

			foreach ($this->_ses_contents as $item) {
				$this->remove(array('rowid' => $item['rowid']));
			}

			$this->CI->session->unset_userdata('ses_contents');

		}


	}

}
