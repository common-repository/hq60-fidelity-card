<?php

/**
* HQ60 integration
* 
 * @version 1.0
 * @since 1.0
 * 
 */

namespace Hq60;

class Hq60 extends \Hq60\Base {
	
	public function __construct( $token = null ) {
		
		if ( $token === null ) {
			
			$admin = new \Hq60fidelitycard\Admin\Admin();
			$token = get_option($admin->get_prefix().'option_token');
			
		}
		
		parent::__construct($token);
		
	}
	
	/**
	 * Get membercard data
	 * 
	 * @since 1.0 
	 */
	public function set_member_card_data ( $id_member_card , $data ) {
		
		$url = $this->get_base_api_url().'update?token='.$this->get_token();
		$data['id_member_card'] = $id_member_card;
		
		$call = $this->perform_post_request($url , $data);
		
		if ( $call != null ) {
			
			if ( is_object($call) ) {
				
				if ( isset ( $call->code ) ) {
					
					if ( $call->code == '-1' ) {
						
						$error = $call->message;
						
						return $error;
						
					}
					
				}
				
				if ( isset ( $call->result ) ) {
					
					return true;

				} else {
					
					return false;
					
				}
				
			}
			
		}
		
	}
	
	
	/**
	 * Get membercard data
	 * 
	 * @since 1.0 
	 */
	public function get_membercard_data ( $id_member_card ) {
		
		$url = $this->get_base_api_url().'member-card/'.$id_member_card.'?token='.$this->get_token();
		
		$response = $this->perform_get_request( $url );
		
		if ( is_object( $response ) ) {
				
			if ( isset ( $response->code ) ) {
				
				return false;
				
			} else {
				
				return $response;
				
			}
		}
		
	}
	
	
	/**
	 * Get balance
	 * 
	 * @param int the id_member_card
	 * @param int the id_campaign
	 * 
	 * @since 1.0
	 * 
	 * @return object if balance is found
	 */
	public function get_balance ( $id_member_card , $id_campaign ) {
		
		$url = $this->get_base_api_url().'balance/'.$id_member_card.'?campaign='.$id_campaign.'&token='.$this->get_token();
		
		$response = $this->perform_get_request( $url );
		
		if ( is_object( $response ) ) {
				
			if ( isset ( $response->code ) ) {
				
				return false;
				
			} else {
				
				return $response;
				
			}
		}
		
	}
	
	/**
	 * Get transaction
	 * 
	 * @param int the id of member_card
	 * @param int the id campaign
	 * 
	 * @since 1.0
	 * 
	 * @return object if campaign is found or false if nothing found
	 */
	public function get_transaction ( $id_member_card , $id_campaign ) {
		
		$url = $this->get_base_api_url().'transaction/'.$id_member_card.'?id_campaign='.$id_campaign.'&token='.$this->get_token();
		
		$response = $this->perform_get_request( $url );
		
		if ( is_object( $response ) ) {
				
			if ( isset ( $response->code ) ) {
				
				return false;
				
			} else {
				
				return $response;
				
			}
		}
		
	}
	
	
	/**
	 * Get single campaign
	 * 
	 * @param int the id campaign
	 * 
	 * @since 1.0
	 * 
	 * @return object if campaign is found or false if nothing found
	 */
	public function get_campaign ( $id_campaign ) {
		
		$url = $this->get_base_api_url().'campaign/'.$id_campaign.'?token='.$this->get_token();
			
		$response = $this->perform_get_request( $url );
		
		if ( is_object( $response ) ) {
				
			if ( isset ( $response->code ) ) {
				
				return false;
				
			} else {
				
				return $response;
				
			}
		}
		
	}
	
	/**
	 * Get the campaigns
	 * 
	 * @since 1.0
	 * 
	 * @return object if campaign is found
	 */
	public function get_campaign_list () {
		
		$url = $this->get_base_api_url().'campaign?token='.$this->get_token();
			
		$response = $this->perform_get_request( $url );
		
		return $response;
		
	}
	
	/**
	 * Perform the login.
	 * 
	 * @param string the card number
	 * @param string the password
	 * @param string the id of member_card
	 * 
	 * @since 1.0
	 * 
	 * @return todo
	 */
	public function login($card = null , $password , $id_member_card = null) {
		
		if ( $id_member_card === null ) {
		
			// 1 - get the ID from card number
			$id_member_card = $this->get_id_member_card_from_card_number($card);
			
		}
		
		if ( $id_member_card != null ) {
			
			// we have only 1 id. We can try the login
			$logged = $this->perform_login( $id_member_card , $password );
			
			if ( $logged === true ) {
				
				return true;
				
			} else {
				
				return $logged;
				
			}
			
		} else {
			
			return false;
			
		}
		
	}
	
	/**
	 * Perform the login
	 * 
	 * Error from API could be:
	 * 
	 * - "Member Card not registered"
	 * 
	 * @param string the id of member card
	 * @param string the password
	 * 
	 * @return false if not logged, true if it is, string if it was an error
	 * 
	 * @since 1.0
	 * 
	 */
	private function perform_login($id_member_card , $password) {
		
		$url = $this->get_base_api_url().'validate?token='.$this->get_token();
		$data['id_member_card'] = $id_member_card;
		$data['password'] = $password;
		
		$call = $this->perform_post_request($url , $data);
		
		if ( $call != null ) {
			
			if ( is_object($call) ) {
				
				if ( isset ( $call->code ) ) {
					
					if ( $call->code == '-1' ) {
						
						$error = $call->message;
						
						return $error;
						
					}
					
				}
				
				if ( isset ( $call->member_card[0] ) ) {
					
					$got_id_member_card = $call->member_card[0]->id;
					
					if ( $got_id_member_card == $id_member_card ) {
						
						return true;
						
					}
					
				} else {
					
					return false;
					
				}
				
			}
			
		}
		
	}
	
	/**
	 * Set a (new) password (or change) on member_card
	 * 
	 * Error from API could be:
	 * 
	 * 
	 * @param string the id of member card
	 * @param string the password
	 * 
	 * @return true if all ok
	 * 
	 * @since 1.0
	 * 
	 */
	public function set_password( $id_member_card , $password ) {
		
		$url = $this->get_base_api_url().'register?token='.$this->get_token();
		$data['id_member_card'] = $id_member_card;
		$data['password'] = $password;
		
		$call = $this->perform_post_request($url , $data);
		
		if ( $call != null ) {
			
			if ( is_object($call) ) {
				
				if ( isset ( $call->code ) ) {
					
					if ( $call->code == '-1' ) {
						
						$error = $call->message;
						
						return $error;
						
					}
					
				}
				
				if ( isset ( $call->member_card[0] ) ) {
					
					$got_id_member_card = $call->member_card[0]->id;
					
					if ( $got_id_member_card == $id_member_card ) {
						
						return true;
						
					}
					
				} else {
					
					return false;
					
				}
				
			}
			
		}
		
	}
	
	/**
	 * Set or update new email on id member card
	 * 
	 * Error from API could be: ?
	 * 
	 * 
	 * @param string the id of member card
	 * @param string the email
	 * 
	 * @return true if all ok
	 * 
	 * @since 1.0
	 * 
	 */
	public function set_email( $id_member_card , $email ) {
		
		$url = $this->get_base_api_url().'update?token='.$this->get_token();
		$data['id_member_card'] = $id_member_card;
		$data['email'] = $email;
		
		$call = $this->perform_post_request($url , $data);
		
		if ( $call != null ) {
			
			if ( is_object($call) ) {
				
				if ( isset ( $call->code ) ) {
					
					if ( $call->code == '-1' ) {
						
						$error = $call->message;
						
						return $error;
						
					}
					
				}
				
				if ( isset ( $call->result ) ) {
					
					return true;

				} else {
					
					return false;
					
				}
				
			}
			
		}
		
	}
	
	/**
	 * Return the email from id_member_card
	 * 
	 * @param int the id of member card
	 * 
	 * @return null if not found or the email
	 * 
	 * @since 1.0
	 * 
	 */
	public function get_email_from_id_member_card ( $id_member_card ) {
		
		$url = $this->get_base_api_url().'member-card/'.$id_member_card.'?token='.$this->get_token();
		$response = $this->perform_get_request( $url );
		
		if ( is_object( $response ) ) {
			
			if ( isset ( $response->member_card ) ) {
				
				$email = $response->member_card[0]->email;
				
				if ( !is_email ( $email ) ) {
					
					return false;
					
				} else {
					
					return $email;
					
				}
				
			}
			
		} else {
			
			return false;
			
		}
		
	}
	
	
	
	
	/**
	 * Return the UNIQUE id from card number.
	 * 
	 * @param string card the card number
	 * @return null if doesn't found or id
	 * 
	 * @since 1.0
	 */
	public function get_id_member_card_from_card_number( $card ) {
		
		if ( $card != null ) {
				
			$url = $this->get_base_api_url().'member-card?card='.$card.'&token='.$this->get_token();
			
			$response = $this->perform_get_request( $url );
			
			if ( $response != null ) {
				
				if ( is_object($response) ) {
					
					if ( isset ( $response->member_card ) ) {
						
						if ( count ( $response->member_card ) == 1 ) {
							
							$id = $response->member_card[0]->id;
							return $id;
							
						}
						
						if ( count ( $response->member_card ) == 0 ) {
							
							// ma esiste?
							
						}
						
						if ( count ( $response->member_card ) > 1 ) {
							
							return null;
							
						}
												
					} else {
						
						return null;
						
					}
					
				}
				
			} else {
				
				echo 'Si è verificato un errore. Codice errore 116664';
				
			}
			
		}
		
	}
	
	
	/**
	 * Return the customer name.
	 * I.e. the licence owner.
	 * 
	 * @since 1.0
	 * 
	 * @return string if any or null if empty
	 * 
	 */
	
	public function get_customer_name() {
		
		$customer_name = null;
		
		$call = $this->get_base_api_url().'account/'.$this->get_token().'?token='.$this->get_token();
		
		$response = wp_remote_get($call);
		
		if ( is_array($response) ) {
			
			if ( isset ( $response['body'] ) ) {
					
				$call = json_decode($response['body']);
					
				if ( isset ( $call->code ) ) {
						
					if ( $call->code == '-1' ) {
							
						// token is wrong
							
					}
						
				} elseif ( isset( $call->account[0]->id ) ) {
						
					// token is valid
						
					$customer_name = $call->account[0]->name_shop;
						
				}
					
					
			}
			
		} else {
			
			if ( is_wp_error( $response ) ) {
				
				$error_message = $response->get_error_message();
   				echo "Something went wrong: $error_message";
				
			}
			
		}
		
		return $customer_name;
		
	}
	
}	