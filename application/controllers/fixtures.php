<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fixtures extends CI_Controller {

  /**
   * Populate db.
   */
	public function index() {    
		if (ENVIRONMENT == 'development') {
		  // Down with the DB.
      $this->_tear_down();
      
		  if ($this->mongo_db->count('surveys') === 0) {
		    $this->_fix_surveys();
		  }
      
      print "Done fixing data.";
		}
	}
  
  /**
   * Drop database.
   * Start with clean slate.
   */
  private function _tear_down() {
    $this->mongo_db->dropDb('aw_datacollection');
  }
  
  /**
   * Fixtures
   * Sets up demo surveys.
   */
  private function _fix_surveys() {
    $this->mongo_db->batchInsert('surveys', array(
      array(
        'sid' => increment_counter('survey_sid'),
        'title' => 'Meteor usage',
        'status' => 1,
        
        'created' => Mongo_db::date()
      ),
      array(
        'sid' => increment_counter('survey_sid'),
        'title' => 'Handlebars vs something else',
        'status' => 1,
        
        'created' => Mongo_db::date()
      ),
      array(
        'sid' => increment_counter('survey_sid'),
        'title' => 'Cat ladies around the neighborhood',
        'status' => 2,
        
        'created' => Mongo_db::date()
      ),
      array(
        'sid' => increment_counter('survey_sid'),
        'title' => 'Knowledge of html',
        'status' => 2,
        
        'created' => Mongo_db::date()
      ),
      array(
        'sid' => increment_counter('survey_sid'),
        'title' => 'Running out of titles',
        'status' => 3,
        
        'created' => Mongo_db::date()
      ),
      array(
        'sid' => increment_counter('survey_sid'),
        'title' => 'Another survey',
        'status' => 99,
        
        'created' => Mongo_db::date()
      ),
    ));
  }
}

/* End of file fixtures.php */
/* Location: ./application/controllers/fixtures.php */